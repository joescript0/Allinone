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
        Schema::create('type_frais', function (Blueprint $table) {
            $table->id();
            $table->text("nom")->nullable();
            $table->text("code")->nullable();
            $table->text("description")->nullable();
            $table->timestamps();
            $table->text('recherche')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_frais');
    }
};
