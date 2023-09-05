<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'userName' => 'required|min:3|max:30',
            'userEmail' => 'required|email|min:6|max:30|unique:users,email',
            'userEmpNo' => 'required|min:1|max:15|unique:users,employee_id',
            'userPassword' => 'required|string|min:6|max:15',
            'userMobile' => 'nullable|max:15|unique:users,phone'
        ];
    }

    public function messages()
    {
        return [
            'userEmpNo.required' => __('admin.users.fields.emp_no_required'),        
            'userEmpNo.min' => __('admin.users.fields.emp_no_min'),        
            'userEmpNo.max' => __('admin.users.fields.emp_no_max'),        
            'userEmpNo.unique' => __('admin.users.fields.emp_no_unique')      
      ];
    }
}
