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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->text('image')->nullable();
            $table->integer('role')->nullable();
            $table->integer('etat')->default(1);
            $table->integer('groupe_id')->default(0);
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->text('mdp')->nullable();
            $table->text('recherche')->nullable();
            $table->integer('activite_id')->nullable();
            $table->text('adresse');
            $table->integer('type'); // entreprise ou privé 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
