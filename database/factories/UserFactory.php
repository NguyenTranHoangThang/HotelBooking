<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
//Dữ liệu

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $number = 1;
    return [
        'name' => $faker->name,
        'username' => 'user' . $number++,
        'email' => $faker->unique()->safeEmail,
        'phone_number' => $faker->tollFreePhoneNumber,
        'email_verified_at' => now(),
        'password' => '$2y$12$82PIBhK8MovKXkLUNkyb7Omctq6jR3F3ROkjtb0JcArXpllvX2bXO', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Models\Admin::class, function (Faker $faker) {
    static $n = 1;
    return [
        'name' => 'Admin' . $n,
        'username' => 'admin' . $n,
        'email' => 'admin' . $n++ . '@gmail.com',
        'password' => '$2y$12$82PIBhK8MovKXkLUNkyb7Omctq6jR3F3ROkjtb0JcArXpllvX2bXO', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Models\Hotel::class, function (Faker $faker) {
    static $no = 1;
    static $hotelDes = [
        'Nội dung mô tả một nhà cung cấp chỗ ở thứ nhất. Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing. Số lượng chữ trên thực tế có thể lớn hơn. Nhà cung cấp không có thật, các số liệu khác cũng được tạo ngẫu nhiên, không có thật. Mọi sự trùng hợp là không lường trước.',
        'Nội dung mô tả một nhà cung cấp chỗ ở thứ hai. Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing. Số lượng chữ trên thực tế có thể lớn hơn. Nhà cung cấp không có thật, các số liệu khác cũng được tạo ngẫu nhiên, không có thật. Mọi sự trùng hợp là không lường trước.',
        'Nội dung mô tả một nhà cung cấp chỗ ở thứ ba. Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing. Số lượng chữ trên thực tế có thể lớn hơn. Nhà cung cấp không có thật, các số liệu khác cũng được tạo ngẫu nhiên, không có thật. Mọi sự trùng hợp là không lường trước.',
        'Nội dung mô tả một nhà cung cấp chỗ ở thứ tư. Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing. Số lượng chữ trên thực tế có thể lớn hơn. Nhà cung cấp không có thật, các số liệu khác cũng được tạo ngẫu nhiên, không có thật. Mọi sự trùng hợp là không lường trước.',
    ];
    return [
        'verified' => rand(0, 1),
        'credit_card' => $faker->creditCardNumber,
        'rank_point' => $no,
        'name' => $faker->company,
        'description' => $hotelDes[rand(0,3)],
        'stars_num' => rand(0, 5),
        'meta_name' => 'hotel',
        'email' => $faker->email,
        'child_age' => '13',
        'tax_code' => 'IT98746784967' . $no++,
        'review_point' => 0,
        'bank' => 'vietcombank',
        'phone_number' => $faker->phoneNumber,
        'coin' => 1000000,
        'hotel_type_id' => rand(1, 7),
        'hotel_manager_id' => 1,
        'ward_id' => 1,
        'address' => $faker->address,
    ];
});
$factory->define(App\Models\Room::class, function (Faker $faker) {
    static $no = 1, $n = 1;
    if ($no > 4) $no = 1;
    if ($n > 6) $n = 1;
    static $roomDes = [
        'Nội dung mô tả một phòng thứ nhất. Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ hai.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ ba.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ tư.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ năm.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ sáu.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ bảy.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ tám.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
        'Nội dung mô tả một phòng thứ chín.  Nội dung là ngẫu nhiên, tạo ra nhằm mục đích testing.',
    ];
    return [
        'room_name' => $faker->lastName,
        'description' => $roomDes[rand(0,8)],
        // 'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'price' => rand(50000, 50000000),
        'max_adult_amount' => rand(2, 5),
        'max_child_amount' => $faker->randomDigit,
        'free_child_amount' => $faker->randomDigit,
        'room_size' => rand(50, 200),
        'amount' => rand(1, 10),
        'room_mode_id' => $no++,
        'room_type_id' => $n++,
        'hotel_id' => rand(1, 5),
    ];
});
$factory->define(App\Models\RoomBedType::class, function (Faker $faker) {
    static $no = 1, $noB = 1;
    if ($noB > 5) $noB = 1;
    return [
        'bed_type_id' => $noB++,
        'room_id' => $no++,
        'amount' => rand(1, 2)
    ];
});
$factory->define(App\Models\HotelImage::class, function (Faker $faker) {
    static $no = 1;
    return [
        'image_link' => $no . '.png',
        'hotel_id' => $no++,
        'is_primary' => 1
    ];
});
$factory->define(App\Models\RoomImage::class, function (Faker $faker) {
    static $no = 1;
    return [
        'image_link' => $no . '.png',
        'room_id' => $no++,
        'is_primary' => 1
    ];
});
$factory->define(App\Models\RoomFeature::class, function (Faker $faker) {
    static $no = 1, $fN = 1;
    if ($fN > 8) $fN = 1;
    return [
        'feature_id' => $fN++,
        'room_id' => $no++
    ];
});
$factory->define(App\Models\ServiceRoomType::class, function (Faker $faker) {
    static $no = 1;
    if ($no > 5) $no = 1;
    return [
        'service_id' => $no++,
        'room_type_id' => 1,
        'hotel_id' => 1
    ];
});
$factory->define(App\Models\BookingStatus::class, function (Faker $faker) {
    return [
        'name' => 'Chờ...'
    ];
});
$factory->define(App\Models\Policy::class, function (Faker $faker) {
    static $num = 1;
    return [
        'hotel_id' => $num,
        'content' => $faker->sentence($nbWords = 6, $variableNbWords = true),
    ];
});
$factory->define(App\Models\Booking::class, function (Faker $faker) {
    static $no = 1, $c = 6;
    static $userAddress = [
        'Long An',
        'Hà Nội',
        'Hồ Chí Minh',
        'Quãng Ngải',
        'Đà Nẵng',
        'Cà Mau',
        'Hải Phòng'
    ];
    return [
        'hotel_name' => 'Hotel',
        'room_price' => rand(50000, 1000000),
        'room_amount' => 1,
        'contact_name' => $faker->name,
        'contact_email' => $faker->unique()->safeEmail,
        'contact_phone' => $faker->phoneNumber,
        'contact_address' => $userAddress[rand(0,6)],
        'special_request' => "None",
        'bank' => "vietcombank" ,
        'check_in' => "2020-03-21",
        'check_out' => "2020-05-21",
        'room_id' => $no++,
        'customer_id' => $c++,
        'status_id' => rand(1, 6),
        'payment_method_id' => rand(1, 2),
    ];
});
$factory->define(App\Models\PaymentMethod::class, function (Faker $faker) {
    return [
        'name' => 'Thanh Toán Trực Tiếp'
    ];
});
$factory->define(App\Models\HotelFollowing::class, function (Faker $faker) {
    static $n = 1;
    return [
        'customer_id' => $n,
        'hotel_id' => $n++,
    ];
});
$factory->define(App\Models\Question::class, function (Faker $faker) {
    $question = rand(0, 5);
    static $titleArr = [
        'Tiêu đề câu hỏi thứ nhất?',
        'Tiêu đề câu hỏi thứ hai?',
        'Tiêu đề câu hỏi thứ ba?',
        'Tiêu đề câu hỏi thứ tư?',
        'Tiêu đề câu hỏi thứ năm?',
        'Tiêu đề câu hỏi thứ sáu?',
    ];
    static $contentArr = [
        'Nội dung câu hỏi thứ nhất dành cho nhà cung cấp dịch vụ',
        'Nội dung câu hỏi thứ hai dành cho nhà cung cấp dịch vụ',
        'Nội dung câu hỏi thứ ba dành cho nhà cung cấp dịch vụ',
        'Nội dung câu hỏi thứ tư dành cho nhà cung cấp dịch vụ',
        'Nội dung câu hỏi thứ năm dành cho nhà cung cấp dịch vụ',
        'Nội dung câu hỏi thứ sáu dành cho nhà cung cấp dịch vụ',
    ];
    return [
        'customer_id' => rand(6, 25),
        'hotel_id' => rand(1, 15),
        'title' => $titleArr[$question],
        'content' => $contentArr[$question],
        // 'title' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        // 'content' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'created_at' => '2019-07-04',
    ];
});
$factory->define(App\Models\CustomerFollowing::class, function (Faker $faker) {
    static $n = 6;
    return [
        'follower_id' => $n,
        'followed_id' => $n++,
    ];
});
$factory->define(App\Models\CouponCode::class, function (Faker $faker) {
    static $n = 1;
    $m = rand(5, 9);
    $d = rand(10, 20);
    $coupon = rand(0, 3);
    static $titleArr = [
        'Tiêu đề khuyến mãi thứ nhất?',
        'Tiêu đề khuyến mãi thứ hai?',
        'Tiêu đề khuyến mãi thứ ba?',
        'Tiêu đề khuyến mãi thứ tư?',
    ];
    static $contentArr = [
        'Nội dung đợt khuyến mãi thứ nhất của nhà cung cấp chỗ ở?',
        'Nội dung đợt khuyến mãi thứ hai của nhà cung cấp chỗ ở?',
        'Nội dung đợt khuyến mãi thứ ba  của nhà cung cấp chỗ ở?',
        'Nội dung đợt khuyến mãi thứ tư của nhà cung cấp chỗ ở?',
    ];
    return [
        'code' => 'ABCD' . $n,
        'discount_value' => rand(5, 45),
        'hotel_id' => $n++,
        'title' => $titleArr[$coupon],
        'content' => $contentArr[$coupon],
        // 'title' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        // 'content' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'start_at' => '2019-0' . $m . '-' . $d,
        'end_at' => '2019-' . ($m + 3) . '-' . ($d + 10),
        'apply_amount' => rand(50, 140)
    ];
});
$factory->define(App\Models\ApplyCouponCodeHotel::class, function (Faker $faker) {
    static $n = 1;
    return [
        'coupon_code_id' => $n,
        'hotel_id' => $n++,
    ];
});
$factory->define(App\Models\ApplyCouponCodeRoomType::class, function (Faker $faker) {
    static $n = 1;
    return [
        'coupon_code_id' => $n,
        'room_type_id' => $n++,
    ];
});
