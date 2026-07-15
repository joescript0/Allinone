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
        Schema::table('Factureasses', function (Blueprint $table) {
            //
            $table->double("montant_recu")->default(0);
            $table->integer("devise_recu")->default(0);
            $table->double("reste")->default(0);
            $table->double("taux")->default(0);
            $table->double("tva")->default(0);
            $table->integer("mode_de_paiment")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Factureasses', function (Blueprint $table) {
            //
        });
    }
};
