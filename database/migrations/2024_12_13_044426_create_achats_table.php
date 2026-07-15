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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("article_id")->constrained();
            $table->integer("facture_id")->nullable();
            $table->double('prix_unitaire')->nullable();
            $table->double('quantite')->nullable();
            $table->double('total')->nullable();
            $table->integer('devise')->nullable();
            $table->double('taux')->nullable();
            $table->text('libelle')->nullable();
            $table->text('preuve_de_sortie')->nullable();
            $table->integer("etat")->default(0);
            $table->integer("type")->default(0);
            $table->text('date_creation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
