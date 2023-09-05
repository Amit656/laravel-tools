<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateCityRequest extends FormRequest
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
            'cityId' => 'nullable|integer|min:1|exists:cities,id',
            'province' => 'required',
            'cityName' => [
                                'required',
                                'min:1',
                                'max:50',
                                Rule::unique('cities', 'name')->ignore($this->cityId),
                            ],
        ];
    }
}
