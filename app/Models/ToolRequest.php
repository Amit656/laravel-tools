<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ToolRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tool_id', 'user_id', 'delivery_date', 'expected_return_date', 'site_id', 'pickup_type', 'details', 'status'];

    public function tool()
    {
        return $this->belongsTo('App\Models\Tool')->withTrashed();
    }

    public function site()
    {
        return $this->belongsTo('App\Models\Site')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y');
    }

    public function getExpectedReturnDateAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y');
    }

    public function getStatusAttribute($status)
    {
        switch ($status) {
            case 'pending':
                return 'Pending';
                break;
            case 'approved':
                return 'Approved';
                break;
            case 'rejected':
                return 'Rejected';
                break;
            case 'returned':
                return 'Returned';
                break;
        }
    }

    public function getPickupTypeAttribute($status)
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

    public function getDeliveryDateAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y');
    }
}
