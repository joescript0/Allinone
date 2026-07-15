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
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->default(0);
            $table->integer("type_depense_id")->default(0);
            $table->integer("devise")->default(0);
            $table->string("date_depense")->default("");
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
        Schema::dropIfExists('depenses');
    }
};
