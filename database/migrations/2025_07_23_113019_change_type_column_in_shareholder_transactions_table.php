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
        Schema::table('shareholder_transactions', function (Blueprint $table) {
            // Change 'type' to a string with a 25-char limit. This is safe and flexible.
            $table->string('type', 25)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shareholder_transactions', function (Blueprint $table) {
            // Revert back if needed. Assuming it might have been smaller.
            // This step is just for good practice.
            $table->string('type', 15)->change();
        });
    }
};