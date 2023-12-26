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
        Schema::create('work_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref');
            $table->string('vin');
            $table->string('item_description');
            $table->string('item_part_number');
            $table->unsignedBigInteger('qty')->default(1);
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('is_available_qty')->nullable();
            $table->decimal('fnz_price')->nullable();
            $table->timestamp('fnz_priced_at')->nullable();
            $table->timestamp('approval_ref')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('webhook_url_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_requests');
    }
};
