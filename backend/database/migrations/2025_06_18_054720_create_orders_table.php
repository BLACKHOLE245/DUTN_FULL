<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('stutus', ['Chờ xác nhận', 'Đã xác nhận đơn hàng', 'Đang giao hàng', 'Giao hàng thành công', 'Hủy đơn', 'Trả hàng']);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['Thanh toán khi nhận hàng', 'Chuyển khoản ngân hàng', 'Ví ZaloPay', 'VnPay'])->default('Thanh toán khi nhận hàng');
            $table->unsignedBigInteger('shipping_id');
            $table->foreign('shipping_id')->references('id')->on('shipping_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
