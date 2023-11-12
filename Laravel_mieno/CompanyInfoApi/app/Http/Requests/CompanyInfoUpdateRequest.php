<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyInfoUpdateRequest extends FormRequest
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
            'company_name' => ['max:255'],
            'company_kana_name' => ['max:255','regex:/^[ぁ-ん]+$/'],
            'company_address' => ['unique:company_infos', 'max:255'],
            'company_call_num' => ['unique:company_infos','max:255', 'regex:/^[0-9]+$/'],
            'represent_name' => ['max:255'],
            'represent_kana_name' => ['max:255', 'regex:/^[ぁ-ん]+$/']
        ];
    }
}
