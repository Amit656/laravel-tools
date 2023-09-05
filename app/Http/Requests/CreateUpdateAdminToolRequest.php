<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateAdminToolRequest extends FormRequest
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
            'id' => 'nullable|integer|min:1|exists:tools,id',
            'toolNo' => 'required|max:50',
            'modality' => 'required',
            'site' => 'required',
            'serialNo' => 'required|max:50',
            'productNo' => 'required|max:50',
            'typeOfUse' => 'required|max:50',
            'sortField' => 'nullable|max:50',
            'calibrationDate' => 'nullable|after:yesterday',
            'desc' => 'nullable|max:100',
            'toolImage' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'qr_code' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'toolStatus' => [
                'required',
                 Rule::in(['available', 'busy', 'calibration']),
             ],
        ];
    }
}
