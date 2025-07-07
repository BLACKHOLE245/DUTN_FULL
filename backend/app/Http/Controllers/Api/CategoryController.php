<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả danh mục
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách danh mục thành công',
                'data' => $categories,
                'total' => $categories->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo danh mục mới
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'category_name' => 'required|string|max:100|unique:categories,category_name',
                'description' => 'required|string|max:255',
                'status' => ['sometimes', 'integer', Rule::in([0, 1])]
            ], [
                'category_name.required' => 'Tên danh mục là bắt buộc',
                'category_name.unique' => 'Tên danh mục đã tồn tại',
                'category_name.max' => 'Tên danh mục không được vượt quá 100 ký tự',
                'description.required' => 'Mô tả là bắt buộc',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự',
                'status.in' => 'Trạng thái chỉ được là "Hiện thị" hoặc "Ẩn"'
            ]);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tạo danh mục thành công',
                'data' => $category
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết một danh mục
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin danh mục thành công',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ], 404);
            }

            $validated = $request->validate([
                'category_name' => 'sometimes|required|string|max:100|unique:categories,category_name,' . $id,
                'description' => 'sometimes|required|string|max:255',
                'status' => ['sometimes', 'integer', Rule::in([0, 1])]

            ], [
                'category_name.required' => 'Tên danh mục là bắt buộc',
                'category_name.unique' => 'Tên danh mục đã tồn tại',
                'category_name.max' => 'Tên danh mục không được vượt quá 100 ký tự',
                'description.required' => 'Mô tả là bắt buộc',
                'description.max' => 'Mô tả không được vượt quá 255 ký tự',
                'status.in' => 'Trạng thái chỉ được là "Hiện thị" hoặc "Ẩn"'
            ]);

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật danh mục thành công',
                'data' => $category
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ], 404);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách danh mục đang hiển thị
     */
    public function getActiveCategories(): JsonResponse
    {
        try {
            $categories = Category::active()->orderBy('category_name', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách danh mục đang hiển thị thành công',
                'data' => $categories,
                'total' => $categories->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thay đổi trạng thái danh mục
     */
    public function toggleStatus($id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ], 404);
            }

            $category->status = $category->status == 0 ? 1 : 0;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Thay đổi trạng thái danh mục thành công',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}