<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromCustomerLedgersTransactionId extends Migration
{
    public function up()
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->dropUnique('customer_ledgers_transaction_id_unique');
        });
    }

    public function down()
    {
        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->unique('transaction_id');
        });
    }
}