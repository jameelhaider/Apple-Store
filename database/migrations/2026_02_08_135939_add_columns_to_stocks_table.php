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
        Schema::table('stocks', function (Blueprint $table) {
            $table->string('pushasing_from_name')->nullable();
            $table->string('pushasing_from_phone')->nullable();
            $table->string('pushasing_from_cnic')->nullable();
            $table->text('pushasing_from_address')->nullable();
            $table->string('dealer_id')->nullable();
            $table->string('purchasing_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            //
        });
    }
};
