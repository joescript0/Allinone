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
        Schema::create('dsignesvitauxpatients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("signesvitauxpatient_id")->constrained();
            $table->foreignId("signesvitaux_id")->constrained();
            $table->text('valeur_signesvitaux')->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsignesvitauxpatients');
    }
};
