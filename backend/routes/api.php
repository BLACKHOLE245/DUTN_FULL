<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\VoucherController;
// use App\Http\Controllers\Admin\CommentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Đây là các API routes cho ứng dụng
|
*/

// Test routes
Route::get('/test-user', function () {
    try {
        $users = \App\Models\User::select('id', 'name', 'email')->take(5)->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::post('/{id}/ban', [UserController::class, 'banUser']);
    Route::post('/{id}/toggle-ban', [UserController::class, 'toggleBan']);
});

// Public comment routes
// Route::post('/comments/{product_code}/{user_code}', [CommentController::class, 'sendComment']);
// Route::get('/comments/{product_code}', [CommentController::class, 'getComments']); 

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    // Route::post('/logout', [AuthController::class, 'logout']);
    // Route::prefix('comments')->group(function () {
    //     Route::get('/my-comments', [CommentController::class, 'getMyComments']);
    //     Route::put('/{id}', [CommentController::class, 'updateComment']);
    //     Route::delete('/{id}', [CommentController::class, 'deleteComment']);
    // });
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/active', [CategoryController::class, 'getActiveCategories']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [CategoryController::class, 'toggleStatus']);
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/in-stock', [ProductController::class, 'getInStockProducts']);
    Route::get('/form-data', [ProductController::class, 'getFormData']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [ProductController::class, 'toggleStatus']);
    Route::get('products/active', [ProductController::class, 'getActiveProducts']);
    Route::get('products/inactive', [ProductController::class, 'getInactiveProducts']);
    Route::patch('products/bulk-toggle-active', [ProductController::class, 'bulkToggleActive']);
    Route::patch('/{id}/toggle-active', [ProductController::class, 'toggleActive']);
});


Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
    Route::post('/', [BrandController::class, 'store']);
    Route::get('/{id}', [BrandController::class, 'show']);
    Route::put('/{id}', [BrandController::class, 'update']);
    Route::delete('/{id}', [BrandController::class, 'destroy']);
});


Route::prefix('vouchers')->group(function () {
    Route::get('/', [VoucherController::class, 'index']);
    Route::get('/{id}', [VoucherController::class, 'show']);
    Route::post('/', [VoucherController::class, 'store']);
    Route::put('/{id}', [VoucherController::class, 'update']);
    Route::delete('/{id}', [VoucherController::class, 'destroy']);
});


Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::patch('/{id}/approve', [CommentController::class, 'approve']);
    Route::patch('/{id}/hide', [CommentController::class, 'hide']);
    Route::patch('/bulk/approve', [CommentController::class, 'bulkApprove']);
    Route::patch('/bulk/hide', [CommentController::class, 'bulkHide']);
    Route::delete('/{id}', [CommentController::class, 'destroy']);
    Route::post('/{id}/reply', [CommentController::class, 'reply']);
    Route::delete('/replies/{replyId}', [CommentController::class, 'deleteReply']);
    Route::get('/statistics/overview', [CommentController::class, 'statistics']);
    Route::post('/moderate/auto', [CommentController::class, 'autoModerate']);
});