<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalibrationReport extends Model
{
    use HasFactory;

    protected $fillable = ['tool_id', 'report'];

    public function tool()
    {
        return $this->belongsTo('App\Models\Tool')->withTrashed();
    }

    public function getCalibratedOnAttribute($value)
    {
        return date('m/d/Y', \strtotime($value));
    }
}
