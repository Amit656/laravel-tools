<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'userId' => 'required|integer|min:1|exists:users,id',
            'userName' => 'required|max:100',
            'mobile' => [
                                'required',
                                'max:15',
                                Rule::unique('users', 'phone')->ignore($this->userId),
                            ],
            'userEmail' => [
                                'nullable',
                                'min:6',
                                'max:30',
                                Rule::unique('users', 'email')->ignore($this->userId),
                            ],
        ];
    }
}
