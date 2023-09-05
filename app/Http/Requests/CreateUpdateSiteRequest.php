<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateSiteRequest extends FormRequest
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
            'siteId' => 'nullable|integer|min:1|exists:sites,id',
            'siteName' => 'required|max:100',
            'siteAddress' => 'required',
            'province' => 'required',
            'city' => 'required',
            'desc' => 'nullable|max:100',
            'siteType' => [
                'required',
                 Rule::in(['hospital', 'hub']),
             ],
        ];
    }
}
