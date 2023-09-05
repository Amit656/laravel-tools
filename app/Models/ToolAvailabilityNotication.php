<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolAvailabilityNotication extends Model
{
    use HasFactory;

    protected $fillable = ['tool_id', 'user_id', 'notified'];

    public function tool()
    {
        return $this->belongsTo('App\Models\Tool', 'tool_id')->withTrashed();;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }
}
