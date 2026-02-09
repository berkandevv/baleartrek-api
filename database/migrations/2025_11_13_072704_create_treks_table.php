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
        Schema::create('treks', function (Blueprint $table) {
            $table->id();
            $table->string('regnumber')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('imageUrl')->nullable();
            $table->enum('status', ['y', 'n'])->default('n');
            $table->foreignId('municipality_id')->constrained()->onUpdate('restrict')->onDelete('restrict');
            $table->integer('totalScore')->default(0);
            $table->integer('countScore')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treks');
        Schema::table('treks', function (Blueprint $table) {
            $table->dropColumn(['description', 'imageUrl']);
        });
    }
};
