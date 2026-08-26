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
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->text('image')->nullable();
            $table->integer('etat')->default(1);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->text('mdp')->nullable();
            $table->text('recherche')->nullable();
            $table->integer('activite_id')->nullable();
            $table->text('adresse');
            $table->integer('type');
            $table->integer("devise")->nullable();
            $table->double("paiement")->nullable();
            $table->integer("periode")->nullable();
            $table->integer("quantite")->default(1);
            $table->integer("factures")->default(1);
            $table->integer("latitude")->default(1);
            $table->integer("longitude")->default(1);
            $table->text('description')->nullable();
            $table->foreignId("user_id")->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
