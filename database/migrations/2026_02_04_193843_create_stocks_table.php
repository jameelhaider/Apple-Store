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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('company_name');
            $table->string('model_name');
            $table->string('health')->nullable();
            $table->string('rom');
            $table->string('ram')->nullable();
            $table->string('imei1');
            $table->string('imei2')->nullable();
            $table->string('purchase');
            $table->string('sale');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
