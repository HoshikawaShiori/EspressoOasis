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
       Schema::create('coffees', function (Blueprint $table) {
        $table->id();
        $table->timestamps();
        $table->string('imagePath');
        $table->string('title');
        $table->json('sizes')->nullable(); // Store sizes and their prices as JSON data
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffees');
    }
};
