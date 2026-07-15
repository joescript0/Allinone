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
        Schema::create('numdeclarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contentieur_id")->constrained();
            $table->text("numero")->nullable();
            $table->timestamps();
            $table->text('recherche')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numdeclarations');
    }
};
