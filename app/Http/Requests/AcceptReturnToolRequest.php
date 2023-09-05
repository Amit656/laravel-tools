<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcceptReturnToolRequest extends FormRequest
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
            'returnToolId' => 'required|exists:tool_returns,id',
            'site' => 'required|exists:sites,id',
            'toolStatus' => 'required|exists:sites,id',
            'DHLDetials' => [
                                'nullable',
                                'min:1',
                                'max:50',
                            ],
            'UPSDetails' => [
                                'nullable',
                                'min:1',
                                'max:50',
                            ],
            'pickup' => [
                'required',
                Rule::in(['UPS', 'EPT', 'FSE']),
            ],
            'toolStatus' => [
                'required',
                Rule::in(['available', 'busy', 'calibration']),
            ],
        ];
    }
}
