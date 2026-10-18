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
        Schema::create('listesexamensmedicauxes', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('popularite')->nullable();
            $table->string('type_methode')->nullable();
            $table->text('description')->nullable();
            $table->text('a_jeune')->nullable();
            $table->double('tarifs')->default(20000);
            $table->double('tarifss')->default(20000);
            $table->integer('devise')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listesexamensmedicauxes');
    }
};
