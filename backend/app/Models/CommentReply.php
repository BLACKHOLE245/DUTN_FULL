<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'content',
        'status',
    ];

    const STATUS_SHOW = 'Hiện thị';
    const STATUS_HIDE = 'Ẩn';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship với Comment
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope để lọc theo status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope để lọc theo comment
     */
    public function scopeByComment($query, $commentId)
    {
        return $query->where('comment_id', $commentId);
    }

    /**
     * Scope để lọc theo user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Kiểm tra reply có hiển thị không
     */
    public function isVisible()
    {
        return $this->status === self::STATUS_SHOW;
    }

    /**
     * Kiểm tra reply có bị ẩn không
     */
    public function isHidden()
    {
        return $this->status === self::STATUS_HIDE;
    }

    /**
     * Hiển thị reply
     */
    public function show()
    {
        $this->update(['status' => self::STATUS_SHOW]);
    }

    /**
     * Ẩn reply
     */
    public function hide()
    {
        $this->update(['status' => self::STATUS_HIDE]);
    }
}