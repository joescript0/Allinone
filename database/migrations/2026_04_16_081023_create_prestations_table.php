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
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("poste_id")->constrained();
            $table->foreignId("moi_id")->constrained();
            $table->integer('type_prestation')->default(0);
            $table->integer('type_de_rotation')->default(0);
            $table->integer('nombre_de_jour')->default(0);
            $table->string('date_debut')->default("");
            $table->json('details');
            $table->integer('etat')->default(1);
            $table->integer('sipprimer')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestations');
    }
};
