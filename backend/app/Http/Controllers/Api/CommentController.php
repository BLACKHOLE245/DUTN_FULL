<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Comment::with(['user', 'product']);
            if ($request->has('status') && is_numeric($request->status)) {
                $status = (int) $request->status;
                if (in_array($status, [Comment::STATUS_SHOW, Comment::STATUS_HIDE, Comment::STATUS_PENDING])) {
                    $query->where('status', $status);
                }
            }
            
            if ($request->has('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            $comments = $query->orderBy('created_at', 'desc')
                            ->paginate($request->get('per_page', 15));
            
            return response()->json([
                'success' => true,
                'data' => $comments,
                'status_options' => Comment::getStatusOptions()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $comment = Comment::with(['user', 'product', 'replies'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }
    }

    public function approve($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->update(['status' => Comment::STATUS_SHOW]);
            
            return response()->json([
                'success' => true,
                'message' => 'Comment approved successfully',
                'data' => $comment->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve comment'
            ], 500);
        }
    }

    public function hide($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->update(['status' => Comment::STATUS_HIDE]);
            
            return response()->json([
                'success' => true,
                'message' => 'Comment hidden successfully',
                'data' => $comment->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error hiding comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to hide comment'
            ], 500);
        }
    }

    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reply_content' => 'required|string|max:1000',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $comment = Comment::findOrFail($id);
            
            $reply = CommentReply::create([
                'comment_id' => $comment->id,
                'user_id' => $request->user_id,
                'reply_content' => $request->reply_content,
            ]);

            $reply->load('user');
            
            return response()->json([
                'success' => true,
                'message' => 'Reply added successfully',
                'data' => $reply
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding reply: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add reply'
            ], 500);
        }
    }

    public function deleteReply($replyId)
    {
        try {
            $reply = CommentReply::findOrFail($replyId);
            $reply->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Reply deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting reply: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete reply'
            ], 404);
        }
    }

    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = Comment::whereIn('id', $request->comment_ids)
                             ->update(['status' => Comment::STATUS_SHOW]);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully approved {$updated} comments"
            ]);
        } catch (\Exception $e) {
            Log::error('Error bulk approving comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve comments'
            ], 500);
        }
    }

    public function bulkHide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'integer|exists:comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = Comment::whereIn('id', $request->comment_ids)
                             ->update(['status' => Comment::STATUS_HIDE]);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully hidden {$updated} comments"
            ]);
        } catch (\Exception $e) {
            Log::error('Error bulk hiding comments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to hide comments'
            ], 500);
        }
    }



    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            
            $comment->replies()->delete();
            
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Comment and its replies deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment'
            ], 500);
        }
    }

    public function statistics()
    {
        try {
            $stats = [
                'total_comments' => Comment::count(),
                'visible_comments' => Comment::where('status', Comment::STATUS_SHOW)->count(),
                'hidden_comments' => Comment::where('status', Comment::STATUS_HIDE)->count(),
                'pending_comments' => Comment::where('status', Comment::STATUS_PENDING)->count(),
                'recent_comments' => Comment::where('created_at', '>=', now()->subDays(7))->count(),
                'today_comments' => Comment::whereDate('created_at', today())->count(),
                'this_week_comments' => Comment::where('created_at', '>=', now()->startOfWeek())->count(),
                'this_month_comments' => Comment::where('created_at', '>=', now()->startOfMonth())->count(),
            ];
            
            $statusBreakdown = Comment::selectRaw('status, COUNT(*) as count')
                                   ->groupBy('status')
                                   ->get()
                                   ->mapWithKeys(function ($item) {
                                       $statusLabel = Comment::getStatusOptions()[$item->status] ?? 'Unknown';
                                       return [$statusLabel => $item->count];
                                   });
            
            $stats['status_breakdown'] = $statusBreakdown;
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching comment statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    public function autoModerate(Request $request)
    {
        try {
            $commentsToCheck = Comment::whereIn('status', [Comment::STATUS_SHOW, Comment::STATUS_PENDING])->get();
            
            $approvedCount = 0;
            $hiddenCount = 0;
            
            $suspiciousWords = [
                'spam', 'fake', 'scam',
                'tệ', 'rác', 'lừa đảo', 'kém', 'dở', 'tồi tệ'
            ];
            
            foreach ($commentsToCheck as $comment) {
                $containsSuspiciousWords = false;
                
                foreach ($suspiciousWords as $word) {
                    if (stripos($comment->comment_content, $word) !== false) {
                        $containsSuspiciousWords = true;
                        break;
                    }
                }
                
                $isShortAndUseless = strlen(trim($comment->comment_content)) < 5;
                $hasRepeatedChars = preg_match('/(.)\1{4,}/', $comment->comment_content);
                
                if ($containsSuspiciousWords || $isShortAndUseless || $hasRepeatedChars) {
                    if ($comment->status !== Comment::STATUS_HIDE) {
                        $comment->update(['status' => Comment::STATUS_HIDE]);
                        $hiddenCount++;
                    }
                } else {
                    if ($comment->status !== Comment::STATUS_SHOW) {
                        $comment->update(['status' => Comment::STATUS_SHOW]);
                        $approvedCount++;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Auto moderation completed. Approved: {$approvedCount}, Hidden: {$hiddenCount}",
                'data' => [
                    'approved_count' => $approvedCount,
                    'hidden_count' => $hiddenCount,
                    'total_processed' => $commentsToCheck->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Auto moderation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Auto moderation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}