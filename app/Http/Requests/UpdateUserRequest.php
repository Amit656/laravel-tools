<?php

namespace App\Http\Requests;

use App\Rules\UserUpdateRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'userId' => [
                'required',
                'integer',
                'min:1',
                'exists:users,id',
                new UserUpdateRule,
            ],
            'userName' => 'required|max:100',
            'userMobile' => 'max:15',
            'userPassword' => 'nullable|string|min:6|max:30',
            'userEmail' => [
                                'required',
                                'min:6',
                                'max:30',
                                Rule::unique('users', 'email')->ignore($this->userId),
                            ],
        ];
    }

    public function messages()
    {
        return [
            'userEmail.required' => __('admin.users.fields.email_required'),        
            'userEmail.min' => __('admin.users.fields.email_min'),        
            'userEmail.max' => __('admin.users.fields.email_max'),        
            'userEmail.unique' => __('admin.users.fields.email_unique')      
      ];
    }
}
