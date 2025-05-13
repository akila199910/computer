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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('job_no');
            $table->integer('customer_id');
            $table->integer('technician_id')->nullable();
            $table->integer('created_by');
            $table->integer('approved_by')->nullable();
            $table->date('order_date');
            $table->string('description');
            $table->decimal('total_cost')->nullable();
            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
