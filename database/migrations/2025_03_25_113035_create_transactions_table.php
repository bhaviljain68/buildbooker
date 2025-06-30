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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('unit_id');
            $table->date('receipt_date'); // Receipt Generation Date
            $table->date('payment_date'); // Payment Date
            $table->string('unit_no'); // Apartment or Shop No.
            $table->string('bank_name'); // Bank Name
            $table->string('bank_branch'); // Bank Branch
            $table->enum('payment_type', ['bank_draft', 'cheque', 'rtgs', 'neft', 'imps', 'cash']); // Payment Type
            $table->string('payment_reference'); // Bank Draft No., Cheque No., RTGS No., NEFT No., or Cash reference
            $table->decimal('transaction_amount', 15); // Amount in Rs.
            $table->boolean('gst')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
