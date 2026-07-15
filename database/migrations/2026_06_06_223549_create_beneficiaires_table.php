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
        Schema::create('beneficiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ecole_id")->constrained();
            $table->string("nom_eleve")->nullable();
            $table->integer("genre")->default(0);
            $table->foreignId("classe_id")->constrained();
            $table->string("nom_parent")->nullable();
            $table->string("telephone")->nullable();
            $table->integer("etat")->default(1);
            $table->integer("supprimer")->default(0);
            $table->foreignId("user_id")->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaires');
    }
};
