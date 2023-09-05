<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\UserDeleteRule;

class MemberDeleteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                new UserDeleteRule,
            ]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['user_id' => $this->route('user_id')]);
    }
}
