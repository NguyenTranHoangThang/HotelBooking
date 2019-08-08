<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trvel gửi đến cho bạn.</title>
    <style style="text/css">
    </style>
</head>
<body>
    <h1>Chào&nbsp;{{$user->name}}</h1>
    <div>Bạn đã đặt phòng trên Trvel.Từ bây giờ, yêu cầu đặt của bạn sẽ được phía nhà cung cấp dịch vụ xem xét.</div>
    <div>
        <table>
            <tr>
                <td>
                    <span>Địa điểm:</span>
                </td>
                <td>
                    <span>{{$place}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Ngày nhận phòng:</span>
                </td>
                <td>
                    <span>{{explode('-',$booking->check_in)[2].'-'.explode('-',$booking->check_in)[1].'-'.explode('-',$booking->check_in)[0]}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Ngày trả phòng (dự kiến):</span>
                </td>
                <td>
                    <span>{{explode('-',$booking->check_out)[2].'-'.explode('-',$booking->check_out)[1].'-'.explode('-',$booking->check_out)[0]}}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span>Chi tiết giá:</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Số lượng phòng:</span>
                </td>
                <td>
                    <span>{{$booking->room_amount}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Giá phòng (/đêm):</span>
                </td>
                <td>
                    <span>{{number_format($booking->room_price)}}&nbsp;đồng</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Số đêm ở:</span>
                </td>
                <td>
                    <span>{{$days}}&nbsp;ngày</span>
                </td>
            </tr>
            @if($booking->discount_value >0)
                <tr>
                    <td colspan="2">
                        <span>Bạn nhận được&nbsp;{{$booking->discount_value}}% giảm giá.</span>
                    </td>
                </tr>
            @endif
            <tr>
                <td>
                    <h2>Tổng cộng:</h2>
                </td>
                <td>
                    <h2 style="color:orange">{{number_format($booking->room_amount*$booking->room_price*$days*((100-$booking->discount_value)/100))}}&nbsp;đồng</h2>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>