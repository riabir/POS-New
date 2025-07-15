<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->string('transaction_id')->unique()->after('id')->nullable();
            $table->string('transaction_type')->after('transaction_id')->default('legacy_entry'); // 'invoice', 'new_payment', 'advance_payment', etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
        $table->dropColumn(['transaction_id', 'transaction_type']);

        });
    }
};
