<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CheckBannedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            if ($request->user()->isBanned()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị cấm. Vui lòng liên hệ với admin để được hỗ trợ.',
                    'is_banned' => true,
                    'ban_message' => 'Bạn đã bị cấm, vui lòng liên hệ cho admin'
                ], 403);
            }
        }

        return $next($request);
    }
}

