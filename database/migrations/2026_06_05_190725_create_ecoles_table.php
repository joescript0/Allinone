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
        Schema::create('ecoles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("district_id")->constrained();
            $table->string("nom")->nullable();
            $table->string("adresse")->nullable();
            $table->string("nom_directeur")->nullable();
            $table->integer("nombre_eleve")->default(0);
            $table->integer("nombre_enseignant")->default(0);
            $table->integer("nombre_classe")->default(0);
            $table->string("telephone")->nullable();
            $table->foreignId("annee_id")->constrained();
            $table->foreignId("moi_id")->constrained();
            $table->string("annee_creation")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecoles');
    }
};
