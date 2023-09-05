<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateToolReturn extends FormRequest
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
            'comment' => 'required|array|min:1',
            'condition' => 'required|array|min:1',
            'returnTool' => 'required|array|min:1|exists:tool_requests,id',
            'pickup' => [
                'required',
                 Rule::in(['UPS', 'EPT', 'FSE']),
             ],
        ];
    }
}
