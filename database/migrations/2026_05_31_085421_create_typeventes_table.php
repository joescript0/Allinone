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
        Schema::create('typeventes', function (Blueprint $table) {
            $table->id();
            $table->string("nom")->default("");
            $table->string("description")->nullable();
            $table->integer("etat")->default(1);
            $table->integer("supprimer")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typeventes');
    }
};
