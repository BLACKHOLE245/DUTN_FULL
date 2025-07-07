<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách tất cả thương hiệu
     */
    public function index(): JsonResponse
    {
        try {
            $brands = Brand::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách thương hiệu thành công',
                'data' => $brands,
                'total' => $brands->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách thương hiệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo thương hiệu mới
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'brand_name' => 'required|string|max:100|unique:brands,brand_name',
                'brand_logo' => 'required|string|max:100'
            ], [
                'brand_name.required' => 'Tên thương hiệu là bắt buộc',
                'brand_name.unique' => 'Tên thương hiệu đã tồn tại',
                'brand_name.max' => 'Tên thương hiệu không được vượt quá 100 ký tự',
                'brand_logo.required' => 'Logo thương hiệu là bắt buộc',
                'brand_logo.max' => 'Logo thương hiệu không được vượt quá 100 ký tự'
            ]);

            $brand = Brand::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tạo thương hiệu thành công',
                'data' => $brand
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
                'message' => 'Có lỗi xảy ra khi tạo thương hiệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết một thương hiệu
     */
    public function show($id): JsonResponse
    {
        try {
            $brand = Brand::with('products')->find($id);

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thương hiệu'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin thương hiệu thành công',
                'data' => $brand
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin thương hiệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thương hiệu
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $brand = Brand::find($id);

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thương hiệu'
                ], 404);
            }

            $validated = $request->validate([
                'brand_name' => 'sometimes|required|string|max:100|unique:brands,brand_name,' . $id,
                'brand_logo' => 'sometimes|required|string|max:100'
            ], [
                'brand_name.required' => 'Tên thương hiệu là bắt buộc',
                'brand_name.unique' => 'Tên thương hiệu đã tồn tại',
                'brand_name.max' => 'Tên thương hiệu không được vượt quá 100 ký tự',
                'brand_logo.required' => 'Logo thương hiệu là bắt buộc',
                'brand_logo.max' => 'Logo thương hiệu không được vượt quá 100 ký tự'
            ]);

            $brand->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thương hiệu thành công',
                'data' => $brand
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
                'message' => 'Có lỗi xảy ra khi cập nhật thương hiệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa thương hiệu
     */
    public function destroy($id): JsonResponse
    {
        try {
            $brand = Brand::find($id);

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thương hiệu'
                ], 404);
            }

            if ($brand->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa thương hiệu vì còn có sản phẩm liên quan'
                ], 400);
            }

            $brand->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa thương hiệu thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa thương hiệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}