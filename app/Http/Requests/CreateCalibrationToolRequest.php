<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCalibrationToolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tool_id' => 'required|integer|min:1|exists:tools,id',
            'report' => 'mimes:pdf|max:10240',
            'next_calibration_due_date' => 'nullable|after:yesterday',
            'tool_condition' => [
                'required',
                 Rule::in(['good', 'bad']),
             ],
        ];
    }
}
