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
        Schema::table('vendor_ledgers', function (Blueprint $table) {
            // Add a text column for notes. It's nullable because not every
            // ledger entry (like a bill/debit) will have a payment note.
            // We place it after 'payment_type' for logical grouping.
            $table->text('notes')->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_ledgers', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};