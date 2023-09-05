<?php

namespace App\Http\Requests;

use App\Rules\ToolDeleteRule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ToolDeleteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'toolId' => [
                'required',
                'integer',
                'exists:tools,id',
                new ToolDeleteRule,
            ]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['toolId' => $this->route('toolId')]);
    }
}
