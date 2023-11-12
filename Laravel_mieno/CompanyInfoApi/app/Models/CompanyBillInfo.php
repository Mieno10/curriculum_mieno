<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBillInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'billing_place_name',
        'billing_place_kana_name',
        'billing_address',
        'billing_call_num',
        'billing_depart',
        'billing_to_name',
        'billing_to_kana_name'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
