<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
  use SoftDeletes;
  protected $table = 'customer';
  protected $primaryKey = 'user_id';

  protected $fillable = [
    'user_id',
    'coin',
    'address',
  ];

  public function ofUser()
  {
    return $this->belongsTo('App\Models\User','user_id','id');
  }
}
