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
        Schema::create('writes', function (Blueprint $table) {
            $table->id();
            $table->integer('groupe_id')->default(0);
            $table->integer('ressource_id')->default(0);
            $table->boolean("display");
            $table->boolean("add");
            $table->boolean("edit");
            $table->boolean("delete");
            $table->timestamps();
            $table->text('recherche')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writes');
    }
};
