<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_bill_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('company_infos');
            $table->text('billing_place_name');
            $table->text('billing_place_kana_name');
            $table->text('billing_address');
            $table->text('billing_call_num');
            $table->text('billing_depart');
            $table->text('billing_to_name');
            $table->text('billing_to_kana_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_bill_infos');
    }
};
