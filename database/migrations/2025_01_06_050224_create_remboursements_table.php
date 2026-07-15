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
        Schema::create('remboursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId("credit_id")->constrained();
            $table->text('date_r')->nullable();
            $table->text('nom_r')->nullable();
            $table->text('libelle')->nullable();
            $table->double('entree')->nullable();
            $table->integer('taux_r')->nullable();
            $table->integer('devise_r')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remboursements');
    }
};
