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
        Schema::table('payments', function (Blueprint $table) {
            // Rename columns to match Payment model
            $table->renameColumn('reference_number', 'reference');
            $table->renameColumn('transaction_id', 'transactionid');
            $table->renameColumn('tracking_id', 'trackingid');
            $table->renameColumn('total_amount', 'amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert column names
            $table->renameColumn('reference', 'reference_number');
            $table->renameColumn('transactionid', 'transaction_id');
            $table->renameColumn('trackingid', 'tracking_id');
            $table->renameColumn('amount', 'total_amount');
        });
    }
};

