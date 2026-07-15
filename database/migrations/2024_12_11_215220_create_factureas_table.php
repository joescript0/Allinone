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
        Schema::create('factureas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained();
            $table->text('numero')->nullable();
            $table->text('date_creation')->nullable();
            $table->integer("etat")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factureas');
    }
};
