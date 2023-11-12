<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyInfoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'max:255'],
            'company_kana_name' => ['required', 'max:255','regex:/^[ぁ-ん]+$/'],
            'company_address' => ['required', 'unique:company_infos', 'max:255'],
            'company_call_num' => ['required', 'unique:company_infos','max:255', 'regex:/^[0-9]+$/'],
            'represent_name' => ['required', 'max:255'],
            'represent_kana_name' => ['required', 'max:255', 'regex:/^[ぁ-ん]+$/']
        ];
    }
}
