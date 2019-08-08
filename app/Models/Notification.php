<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  // protected $table = 'notification';

  // protected $fillable = [
  //   'customer_id',
  //   'review_id',
  //   'status',
  // ];

  // public function Customer()
  // {
  //   return $this->belongsTo('App\Models\Customer', 'customer_id', 'user_id');
  // }

  // public function Review()
  // {
  //   return $this->belongsTo('App\Models\Review', 'review_id', 'id');
  // }

  protected $table = 'notifications';

  protected $fillable = [
    'type',
    'notifiable_type',
    'notifiable_id',
    'data',
    'read_at',
    'created_at'
  ];
}
