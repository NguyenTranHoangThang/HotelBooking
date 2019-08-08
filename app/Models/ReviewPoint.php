<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewPoint extends Model
{
  protected $table = 'review_point';

  protected $fillable = [
    'point',
    'customer_id',
    'review_id',
  ];
}
