<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateToolRequest extends FormRequest
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
            'modality' => 'required|exists:modality,id',
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'site' => 'required|exists:sites,id',
            'deliveryDate' => 'required|date|after:yesterday',
            'expectedReturnDate' => 'required|date|after:deliveryDate',
            'requestedTool' => 'array|exists:tools,id',
            'pickup' => [
                'required',
                 Rule::in(['UPS', 'EPT', 'FSE']),
             ],
        ];
    }
}
