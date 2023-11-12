<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyBillInfo>
 */
class CompanyBillInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1,
            'billing_place_name' => 'たまご城',
            'billing_place_kana_name' => 'たまごじょう',
            'billing_address' => '愛媛県今治市タオル町1-1-1',
            'billing_call_num' => 111111111,
            'billing_depart' => 'ゆで卵部',
            'billing_to_name' => '茹卵実',
            'billing_to_kana_name' => 'ゆでたまみ'
        ];
    }
}
