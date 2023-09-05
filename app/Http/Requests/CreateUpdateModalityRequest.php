<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateModalityRequest extends FormRequest
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
            'modalityId' => 'nullable|integer|min:1|exists:modality,id',
            'modalityName' => [
                                'required',
                                'min:1',
                                'max:50',
                                Rule::unique('modality', 'name')->ignore($this->modalityId),
                            ],
        ];
    }
}
