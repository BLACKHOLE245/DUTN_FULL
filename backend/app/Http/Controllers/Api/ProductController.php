<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
{
    try {
        $query = Product::with(['category', 'brand']);

        if ($request->has('category_id') && $request->category_id) {
            $query->byCategory($request->category_id);
        }

        if ($request->has('brand_id') && $request->brand_id) {
            $query->byBrand($request->brand_id);
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'Còn hàng') {
                $query->inStock();
            } elseif ($request->status === 'Hết hàng') {
                $query->outOfStock();
            }
        }

        if ($request->has('visibility')) {
            if ($request->visibility === 'visible') {
                $query->where('is_active', 1);
            } elseif ($request->visibility === 'hidden') {
                $query->where('is_active', 0);
            }
        }

        if ($request->has('search') && $request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort_by_price')) {
            $direction = $request->sort_by_price === 'desc' ? 'desc' : 'asc';
            $query->orderBy('sale_price', $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $products->getCollection(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi lấy danh sách sản phẩm',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request): JsonResponse
    {
        try {
            \Log::info('Store request data:', $request->all());
            \Log::info('Store request files:', $request->allFiles());

            $validated = $request->validate([
                'product_name' => 'required|string|max:100|unique:products,product_name',
                'price_original' => 'required|integer|min:0',
                'sale_price' => 'required|integer|min:0',
                'status' => ['required', Rule::in(['Còn hàng', 'Hết hàng'])],
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'is_active' => 'sometimes|boolean',
                'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'hinh_anh.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($validated['sale_price'] > $validated['price_original']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => [
                        'sale_price' => ['Giá khuyến mãi không được lớn hơn giá gốc']
                    ]
                ], 422);
            }

            $imagePaths = [];

            if ($request->hasFile('hinh_anh')) {
                $files = $request->file('hinh_anh');

                if (is_array($files)) {
                    foreach ($files as $image) {
                        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('products', $fileName, 'public');
                        $imagePaths[] = $fileName;
                    }
                } else {
                    $fileName = time() . '_' . uniqid() . '.' . $files->getClientOriginalExtension();
                    $files->storeAs('products', $fileName, 'public');
                    $imagePaths[] = $fileName;
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('products', $fileName, 'public');
                    $imagePaths[] = $fileName;
                }
            }

            $validated['hinh_anh'] = !empty($imagePaths) ? json_encode($imagePaths) : null;

            $product = Product::create($validated)->load(['category', 'brand']);

            return response()->json([
                'success' => true,
                'message' => 'Tạo sản phẩm thành công',
                'data' => $product
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Store product error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $product = Product::with(['category', 'brand'])->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin sản phẩm thành công',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            \Log::info('Update request data:', $request->all());
            \Log::info('Update request files:', $request->allFiles());

            $validated = $request->validate([
                'product_name' => 'sometimes|required|string|max:100|unique:products,product_name,' . $id,
                'price_original' => 'sometimes|required|integer|min:0',
                'sale_price' => 'sometimes|required|integer|min:0',
                'status' => ['sometimes', 'required', Rule::in(['Còn hàng', 'Hết hàng'])],
                'category_id' => 'sometimes|required|exists:categories,id',
                'brand_id' => 'sometimes|required|exists:brands,id',
                'is_active' => 'sometimes|boolean',
                'hinh_anh' => 'nullable|array',
                'hinh_anh.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'delete_images' => 'sometimes|boolean'
            ]);

            $priceOriginal = $validated['price_original'] ?? $product->price_original;
            $salePrice = $validated['sale_price'] ?? $product->sale_price;

            if ($salePrice > $priceOriginal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => [
                        'sale_price' => ['Giá khuyến mãi không được lớn hơn giá gốc']
                    ]
                ], 422);
            }

            if ($request->hasFile('hinh_anh') || $request->hasFile('images') || $request->get('delete_images')) {
                if ($request->get('delete_images') || $request->hasFile('hinh_anh') || $request->hasFile('images')) {
                    $product->deleteOldImages();
                }

                $imagePaths = [];

                if ($request->hasFile('hinh_anh')) {
                    foreach ($request->file('hinh_anh') as $image) {
                        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('products', $fileName, 'public');
                        $imagePaths[] = $fileName;
                    }
                }

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('products', $fileName, 'public');
                        $imagePaths[] = $fileName;
                    }
                }

                $validated['hinh_anh'] = !empty($imagePaths) ? json_encode($imagePaths) : null;
            }

            $product->update($validated);
            $product->load(['category', 'brand']);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Update product error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            $product->deleteOldImages();
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function toggleVisibility($id): JsonResponse
{
    try {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        $product->is_active = !$product->is_active;
        $product->save();
        $product->load(['category', 'brand']);

        $statusText = $product->is_active ? 'hiển thị' : 'ẩn';

        return response()->json([
            'success' => true,
            'message' => "Đã {$statusText} sản phẩm thành công",
            'data' => $product
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi thay đổi trạng thái hiển thị sản phẩm',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getVisibleProducts(): JsonResponse
{
    try {
        $products = Product::with(['category', 'brand'])
            ->where('is_active', 1)
            ->orderBy('product_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sản phẩm hiển thị thành công',
            'data' => $products,
            'total' => $products->count()
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi lấy danh sách sản phẩm',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function toggleActive($id)
{
    $product = Product::findOrFail($id);
    $product->is_active = $product->is_active ? 0 : 1;
    $product->save();
    
    return response()->json([
        'message' => 'Cập nhật trạng thái thành công',
        'data' => $product
    ]);
}
    public function getInStockProducts(): JsonResponse
    {
        try {
            $products = Product::with(['category', 'brand'])
                ->inStock()
                ->orderBy('product_name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách sản phẩm còn hàng thành công',
                'data' => $products,
                'total' => $products->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            $product->status = $product->status === 'Còn hàng' ? 'Hết hàng' : 'Còn hàng';
            $product->save();
            $product->load(['category', 'brand']);

            return response()->json([
                'success' => true,
                'message' => 'Thay đổi trạng thái sản phẩm thành công',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFormData(): JsonResponse
    {
        try {
            $categories = Category::active()->orderBy('category_name', 'asc')->get(['id', 'category_name']);
            $brands = Brand::orderBy('brand_name', 'asc')->get(['id', 'brand_name']);

            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form thành công',
                'data' => [
                    'categories' => $categories,
                    'brands' => $brands
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu form',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadImageToBackend(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $image = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $fileName, 'public');

            return response()->json([
                'success' => true,
                'message' => 'Upload hình ảnh thành công',
                'data' => [
                    'filename' => $fileName,
                    'url' => Storage::url('products/' . $fileName)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi upload hình ảnh',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function validateImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $image = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            return response()->json([
                'success' => true,
                'message' => 'File hợp lệ',
                'data' => [
                    'suggested_filename' => $fileName,
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime_type' => $image->getMimeType()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File không hợp lệ',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}