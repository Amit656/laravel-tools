<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'province_id'];

    public static function getCityName($id)
    {
        return City::where('id', $id)
            ->first('name');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    public function sites()
    {
        return $this->hasMany('App\Models\Site');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('m/d/Y', \strtotime($value));
    }
}
