<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
  use SoftDeletes;
  protected $table = 'reply';

  protected $fillable = [
    'content',
    'question_id',
  ];

  public function Question()
  {
    return $this->belongsTo('App\Models\Question', 'question_id', 'id');
  }
}
