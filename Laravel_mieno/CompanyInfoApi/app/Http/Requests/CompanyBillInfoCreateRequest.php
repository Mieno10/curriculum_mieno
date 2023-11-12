<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyBillInfoCreateRequest extends FormRequest
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
            'company_id' => ['required', 'integer', 'unique:company_bill_infos', 'exists:company_infos,id'],
            'billing_place_name' => ['required', 'max:255'],
            'billing_place_kana_name' => ['required', 'max:255', 'regex:/^[ぁ-ん]+$/' ],
            'billing_address' => ['required', 'max:255'],
            'billing_call_num' => ['required', 'max:255', 'regex:/^[0-9]+$/'],
            'billing_depart' => ['required', 'max:255'],
            'billing_to_name' => ['required', 'max:255'],
            'billing_to_kana_name' => ['required', 'max:255', 'regex:/^[ぁ-ん]+$/']
        ];
    }
}
