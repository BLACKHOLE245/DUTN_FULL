<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    /**
     * Lấy danh sách tất cả users
     * GET /api/users
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::with('role')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách users thành công',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin user theo ID
     * GET /api/users/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::with('role')->find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User không tồn tại'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin user thành công',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo user mới
     * POST /api/users
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name_user' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'role_id' => 'required|exists:roles,id',
                'images_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userData = $request->except('images_user');
            $userData['password'] = Hash::make($request->password);

            // Xử lý upload ảnh
            if ($request->hasFile('images_user')) {
                $image = $request->file('images_user');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('users', $imageName, 'public');
                $userData['images_user'] = $imagePath;
            }

            $user = User::create($userData);
            $user->load('role');

            return response()->json([
                'success' => true,
                'message' => 'Tạo user thành công',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin user
     * PUT/PATCH /api/users/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User không tồn tại'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name_user' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|nullable|string|min:8',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'role_id' => 'sometimes|required|exists:roles,id',
                'images_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userData = $request->except('images_user');
            
            // Mã hóa password nếu có
            if ($request->has('password') && $request->password) {
                $userData['password'] = Hash::make($request->password);
            } else {
                unset($userData['password']);
            }

            // Xử lý upload ảnh mới
            if ($request->hasFile('images_user')) {
                // Xóa ảnh cũ nếu có
                if ($user->images_user && Storage::disk('public')->exists($user->images_user)) {
                    Storage::disk('public')->delete($user->images_user);
                }
                
                $image = $request->file('images_user');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('users', $imageName, 'public');
                $userData['images_user'] = $imagePath;
            }

            $user->update($userData);
            $user->load('role');

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật user thành công',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa user
     * DELETE /api/users/{id}
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User không tồn tại'
                ], 404);
            }

            // Xóa ảnh nếu có
            if ($user->images_user && Storage::disk('public')->exists($user->images_user)) {
                Storage::disk('public')->delete($user->images_user);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa user thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['Hoạt Động', 'Khóa'])->default('Hoạt Động')->after('role_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Tìm kiếm users
     * GET /api/users/search?keyword=...
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $keyword = $request->get('keyword', '');
            $roleId = $request->get('role_id');

            $query = User::with('role');

            if ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('name_user', 'LIKE', "%{$keyword}%")
                      ->orWhere('email', 'LIKE', "%{$keyword}%")
                      ->orWhere('phone', 'LIKE', "%{$keyword}%");
                });
            }

            if ($roleId) {
                $query->where('role_id', $roleId);
            }

            $users = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm thành công',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách users với phân trang
     * GET /api/users/paginate?page=1&per_page=10
     */
    public function paginate(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $users = User::with('role')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách users thành công',
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

}