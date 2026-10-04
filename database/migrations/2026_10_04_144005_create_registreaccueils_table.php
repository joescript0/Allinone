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
        Schema::create('registreaccueils', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->default(0);
            $table->integer("personne_id")->default(0);
            $table->integer("service_id")->default(0);
            $table->integer("motif_id")->default(0);
            $table->string("heure_entree")->nullable(0);
            $table->string("heure_sortie")->nullable(0);
            $table->string("note")->nullable(0);
            $table->string('signature')->nullable();
            $table->integer("etat")->default(1);
            $table->integer("supprimer")->default(0);
            $table->integer("action")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registreaccueils');
    }
};
