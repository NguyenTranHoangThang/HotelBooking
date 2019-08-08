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
    <h1>Chào&nbsp;{{$booking->contact_name}}</h1>
    <div>Yêu cầu đặt phòng của bạn đã được&nbsp;{{$booking->hotel_name}}&nbsp;chấp nhận.<br>
    Trvel thông báo đến cho bạn rằng: <span style="font-weight:bold">bạn cần thanh toán cho phía&nbsp;{{$booking->hotel_name}}&nbsp;trước khi đến nhận phòng.</span></div>
   @if($policy->cancelable >0)
    <div>
        Lưu ý,&nbsp;{{$booking->hotel_name}}&nbsp;cho phép người đặt được phép hủy phòng trước ngày check-in&nbsp;{{$policy->cancelable}}&nbsp;ngày.
        Bạn cần cân nhắc thanh toán đúng thời hạn để không bị hủy lượt đặt phòng.
    </div>
    @else
    <div>
        Bạn cần cân nhắc thanh toán đúng thời hạn (thông thường là trước ngày check-in một nửa khoảng thời gian kể từ lúc nhận được e-mail này. Nếu bạn nhận được 
        e-mail quá cận ngày check-in, bạn NÊN THANH TOÁN NGAY BÂY GIỜ) để không bị hủy lượt đặt phòng.
    </div>
   @endif
   <div>
       Bạn cần chuyển khoản vào tài khoản:&nbsp;<span style="font-size:20px;font-weight:bold">{{$credit_card}}</span>.<br>
   Ngân hàng&nbsp;<span style="font-size:20px;font-weight:bold">{{$bank}}</span>
   </div>
</body>
</html>