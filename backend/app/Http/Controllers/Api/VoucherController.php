<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function show($id)
{
    $voucher = Voucher::findOrFail($id);
    return response()->json($voucher);
}

    public function index()
    {
        return response()->json(Voucher::orderBy('created_at', 'desc')->get());
    }

public function store(Request $request)
{
    $request->validate([
        'voucher_name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:vouchers,code',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'min_order_amount' => 'required|numeric|min:0',
        'max_discount_amount' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'status' => 'required|in:0,1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ]);

    $voucher = Voucher::create($request->all());

    return response()->json([
        'message' => 'Thêm voucher thành công',
        'voucher' => $voucher
    ]);
}

public function update(Request $request, $id)
{
    $voucher = Voucher::findOrFail($id);

    $request->validate([
        'voucher_name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'min_order_amount' => 'required|numeric|min:0',
        'max_discount_amount' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'status' => 'required|in:0,1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ]);

    $voucher->update($request->all());

    return response()->json([
        'message' => 'Cập nhật voucher thành công',
        'voucher' => $voucher
    ]);
}



    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json(['message' => 'Xóa voucher thành công']);
    }
}
