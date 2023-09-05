<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ToolReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tool_id', 'return_status', 'comment', 'details'];

    public function tool()
    {
        return $this->belongsTo('App\Models\Tool', 'tool_id')->withTrashed();;
    }

    public function toolRequest()
    {
        return $this->belongsTo('App\Models\ToolRequest', 'tool_request_id')->withTrashed();;
    }

    public function site()
    {
        return $this->belongsTo('App\Models\Site', 'site_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y');
    }

    public function getReturnStatusAttribute($status)
    {
        switch ($status) {
            case 'good':
                return 'Good';
                break;
            case 'bad':
                return 'Bad';
                break;
        }
    }

    public function getDropTypeAttribute($status)
    {
        switch ($status) {
            case 'UPS':
                return 'UPS';
                break;
            case 'EPT':
                return 'EPT';
                break;
            case 'FSE':
                return 'FSE';
                break;
        }
    }
}
