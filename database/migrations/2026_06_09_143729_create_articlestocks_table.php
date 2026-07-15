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
        Schema::create('articlestocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("article_id")->constrained();
            $table->integer("stock")->default(0);
            $table->double("prix_detail")->default(0);
            $table->double("prix_gros")->default(0);
            $table->integer("taille_lot")->default(0);
            $table->integer("taille_piece")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articlestocks');
    }
};
