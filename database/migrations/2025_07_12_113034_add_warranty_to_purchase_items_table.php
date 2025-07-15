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
        Schema::table('purchase_items', function (Blueprint $table) {
            // The number of months the warranty is valid for.
            $table->integer('warranty_months')->unsigned()->nullable()->after('total_price'); // Or after another relevant column
            
            // The exact date the warranty expires.
            $table->date('warranty_expiry_date')->nullable()->after('warranty_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn(['warranty_months', 'warranty_expiry_date']);
        });
    }
};