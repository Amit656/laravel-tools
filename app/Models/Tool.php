<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tool extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['description', 'serial_no', 'product_no', 'type_of_use', 'modality_id', 'calibration_date', 'sort_field', 'site_id', 'status', 'image', 'tool_condition', 'qr_code'];

    protected $appends = [
        'image_url', 'QR_code_url', 'tool_condition_status',
    ];

    public function modality()
    {
        return $this->belongsTo('App\Models\Modality')->withTrashed();
    }

    public function site()
    {
        return $this->belongsTo('App\Models\Site')->withTrashed();
    }

    public function requestTools()
    {
        return $this->hasOne('App\Models\ToolRequest', 'tool_id')->withTrashed();
    }

    public function getCalibrationDateAttribute($value)
    {
        if($value){
            return date('m/d/Y', \strtotime($value));
        }
    }

    public function getImageUrlAttribute($value)
    {  
        if(!empty($this->image)){
            return asset('storage/' . $this->image) ;     
        }else{
            return null;
        }
    }

    public function getQRCodeUrlAttribute($value)
    {  
        if(!empty($this->qr_code)){
            return asset('storage/' . $this->qr_code) ;     
        }else{
            return null;
        }
    }

    public function getToolConditionStatusAttribute($value)
    {  
        $currentDate = Carbon::createFromFormat('m/d/Y', Carbon::now()->format('m/d/Y'));
        $nextMonthDate = Carbon::createFromFormat('m/d/Y', Carbon::now()->addMonths(1)->format('m/d/Y'));
        $calibrationDate = Carbon::createFromFormat('m/d/Y', Carbon::parse($this->calibration_date)->format('m/d/Y'));
        if($this->tool_condition == 'good'){
            if($this->calibration_date){
                if($calibrationDate->lte($currentDate)){
                    return "red";
                }else if($calibrationDate->gt($nextMonthDate)){
                    return "green";
                }else{
                    return "yellow";
                }
            }else{
                return "green";
            }
        }else{
            return "red";
        }
    }

    public function calibrationReport()
    {
        return $this->hasOne('App\Models\CalibrationReport', 'tool_id')->latest();
    }

    public function lastRequestTools()
    {
        return $this->hasOne('App\Models\ToolRequest', 'tool_id')->withTrashed()->latest();
    }

    public function getDescriptionAttribute($value)
    {  
        if($value){
            return $value;
        }
        return 'N/A';
    }

    public function getStatusAttribute($status)
    {
        switch ($status) {
            case 'available':
                return 'Available';
                break;
            case 'busy':
                return 'Under Investigation';
                break;
            case 'calibration':
                return 'Calibration';
                break;
        }
    }

}
