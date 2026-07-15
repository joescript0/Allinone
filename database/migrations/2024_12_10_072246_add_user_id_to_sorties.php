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
        Schema::table('sorties', function (Blueprint $table) {
            //
            $table->integer("facture_id")->nullable();
            $table->integer("type_frai_id")->nullable();
            $table->double('prix_unitaire')->nullable();
            $table->double('quantite')->nullable();
            $table->double('total')->nullable();
            $table->integer('devise')->nullable();
            $table->double('taux')->nullable();
            $table->text('libelle')->nullable();
            $table->text('preuve_de_sortie')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            //
        });
    }
};
