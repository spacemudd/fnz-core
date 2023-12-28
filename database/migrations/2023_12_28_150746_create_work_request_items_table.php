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
        Schema::create('work_request_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('work_request_id');
            $table->foreign('work_request_id')->references('id')->on('work_requests');
            $table->string('description');
            $table->string('part_number');
            $table->unsignedBigInteger('required_qty')->default(1);
            $table->unsignedBigInteger('available_qty')->nullable();
            $table->decimal('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_request_items');
    }
};
