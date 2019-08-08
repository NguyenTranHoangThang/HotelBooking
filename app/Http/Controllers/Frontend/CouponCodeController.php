<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CouponCode;
use App\Models\Room;

class CouponCodeController extends Controller
{
    public function checkCouponCode(Request $req)
    {
        $value = 0;
        $mess = '';
        $status = false;
        $code = CouponCode::find($req->code_id);
        $room = Room::find($req->room_id);
        $couponCodes = $room->couponCode();
        if ($code == null || $code->hotel_id !=$room->hotel_id) {
            $mess = "Mã ".$code->code." không chính xác hoặc không tồn tại! Thử lại?!";
        } else {
            if (sizeOf($couponCodes) == 0) {
                $mess = "Dường như mã ".$code->code." ĐÃ HẾT hoặc KHÔNG ĐƯỢC ÁP DỤNG cho loại phòng này.";
            } else {
                foreach ($couponCodes as $couponCode) {
                    if ($couponCode->id == $code->id) {
                        if (!Carbon::now()->lessThan(Carbon::parse($couponCode->start_at))) {
                            if ($couponCode->end_at == null || Carbon::now()->lessThan(Carbon::parse($couponCode->end_at))) {
                                if (($couponCode->apply_amount - $couponCode->applied_amount) > 0) {
                                    $status = true;
                                    $value =  $couponCode->discount_value;
                                    $mess = "Bạn nhận được " . $couponCode->discount_value . "% giảm giá.";
                                } else {
                                    $mess = "Rất tiếc, mã giảm giá đã hết.";
                                }
                            } else {
                                $mess = "Mã hết hạn.";
                            }
                        } else {
                            $mess = "Kiểm tra ngày áp dụng cho mã.";
                        }
                        return response()->json([
                            'status' => $status,
                            'value' => $value,
                            'mess' => $mess,
                        ]);
                    }
                }
                $mess = "Dường như mã ".$code->code." ĐÃ HẾT hoặc KHÔNG ĐƯỢC ÁP DỤNG cho loại phòng này.";
            }
        }
        return response()->json([
            'status' => $status,
            'value' => $value,
            'mess' => $mess,
        ]);
    }
}
