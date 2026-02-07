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
        Schema::create('cash_receiveds', function (Blueprint $table) {
            $table->id();
            $table->string('account_id');
            $table->string('customer_name');
            $table->string('ammount');
            $table->string('narration');
            $table->string('initial_rem');
            $table->string('final_rem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_receiveds');
    }
};
