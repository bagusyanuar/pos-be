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
        Schema::create('material_receipt_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('material_receipt_id')->nullable();
            $table->uuid('material_id')->nullable();
            $table->decimal('qty', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
            $table->foreign('material_receipt_id')
                ->references('id')
                ->on('material_receipts')
                ->onDelete('set null');
            $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_receipt_items');
    }
};
