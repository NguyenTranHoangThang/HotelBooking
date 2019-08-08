<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
  protected $table = 'province';

  protected $fillable = [
    'id',
    'name',
    'code',
  ];

  public function District()
  {
    return $this->hasMany("App\Models\District", "province_id", "id");
  }
}
