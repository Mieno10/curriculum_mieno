<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyInfo>
 */
class CompanyInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 1,
            'company_name' => 'おにぎり株式会社',
            'company_kana_name' => 'おにぎりかぶしきがいしゃ',
            'company_address' => '東京都新宿区3丁目1-1-1',
            'company_call_num' => 999999999,
            'represent_name' => 'おにぎり太郎',
            'represent_kana_name' => 'おにぎりたろう'
        ];
    }
}
