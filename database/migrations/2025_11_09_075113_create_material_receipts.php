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
        Schema::create('material_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('receipt_number')->nullable();
            $table->uuid('supplier_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_receipts');
    }
};
