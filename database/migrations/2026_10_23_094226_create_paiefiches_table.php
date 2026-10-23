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
        Schema::create('paiefiches', function (Blueprint $table) {
            $table->id();
            $table->foreignId("patient_id")->constrained();
            $table->double('tarifs')->default(20000);
            $table->integer('devise')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiefiches');
    }
};
