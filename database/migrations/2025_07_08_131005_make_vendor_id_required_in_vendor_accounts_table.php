<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_accounts', function (Blueprint $table) {
            // This makes the 'vendor_id' column required.
            $table->unsignedBigInteger('vendor_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable()->change();
        });
    }
};