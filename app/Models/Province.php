<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'country_id'];

    public function getCreatedAtAttribute($value)
    {
        return date('m/d/Y', \strtotime($value));
    }

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    public function sites()
    {
        return $this->hasMany('App\Models\Site');
    }
}
