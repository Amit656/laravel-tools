<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcceptRequestToolRequest extends FormRequest
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
            'requestToolId' => 'required|exists:tool_requests,id',
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
        ];
    }
}
