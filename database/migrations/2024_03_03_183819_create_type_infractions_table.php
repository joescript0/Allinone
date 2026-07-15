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
        Schema::create('type_infractions', function (Blueprint $table) {
            $table->id();
            $table->text("code");
            $table->text("libelle")->nullable();
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
        Schema::dropIfExists('type_infractions');
    }
};
