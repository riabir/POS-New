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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('bill_no');
            $table->date('bill_date');
            $table->string('remarks');
            $table->decimal('sub_total', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->string('status')->default('Due');  // Added default here
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
