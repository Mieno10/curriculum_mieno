<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyBillInfoUpdateRequest extends FormRequest
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
            'billing_place_name' => [ 'max:255'],
            'billing_place_kana_name' => [ 'max:255', 'regex:/^[ぁ-ん]+$/' ],
            'billing_address' => [ 'max:255'],
            'billing_call_num' => [ 'max:255', 'regex:/^[0-9]+$/'],
            'billing_depart' => ['max:255'],
            'billing_to_name' => [ 'max:255'],
            'billing_to_kana_name' => [ 'max:255', 'regex:/^[ぁ-ん]+$/']
        ];
    }
}
