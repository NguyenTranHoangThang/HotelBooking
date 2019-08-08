<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Feature;
use App\Http\Resources\FeatureResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomFeature extends Model
{
  protected $table = 'room_feature';

  protected $fillable = [
    'id',
    'feature_id',
    'room_id',
  ];

  public function Feature()
  {
    return $this->belongsTo('App\Models\Feature', 'feature_id', 'id');
  }
  public function FeatureResource(){
		return new FeatureResource(Feature::find($this->feature_id));
  }
  public function Room()
  {
    return $this->belongsTo('App\Models\Room', 'room_id', 'id');
  }
}
