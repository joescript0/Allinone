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
        Schema::create('frais', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contentieur_id")->constrained();
            $table->foreignId("type_frai_id")->constrained();
            $table->double("montant");
            $table->text("devise")->nullable();
            $table->text("taux")->nullable();
            $table->text("libelle")->nullable();
            $table->integer("paye")->default(0);
            $table->timestamps();
            $table->text('recherche');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frais');
    }
};
