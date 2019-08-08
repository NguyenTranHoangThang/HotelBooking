<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiList extends Model
{
    protected $table = 'api_list';

    protected $fillable =[
      'hotel_id',
      'update_link',
      'get_all_room_link',
      'booking_link',
      'get_all_room_type_link',
    ];
}
