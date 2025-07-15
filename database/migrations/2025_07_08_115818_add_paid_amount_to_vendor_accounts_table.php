<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_accounts', function (Blueprint $table) {
            // Add a column to track the amount paid. Default is 0.
            $table->decimal('paid_amount', 15, 2)->default(0)->after('amount');

            // Modify the status column to include the new 'partially_paid' option.
            // Update existing unpaid bills' status.
            $table->string('status')->default('unpaid')->change();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_accounts', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
            $table->string('status')->default('unpaid')->change(); // Revert changes if needed
        });
    }
};
