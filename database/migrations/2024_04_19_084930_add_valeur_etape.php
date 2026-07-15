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
        Schema::table('contentieurs', function (Blueprint $table) {
            $table->text('num_projet')->nullable();
            $table->text('nom_projet')->nullable();
            $table->text('date_creation')->nullable();
            $table->double('budget')->nullable();
            $table->integer('nombre_personne')->nullable();
            $table->text('debut')->nullable();
            $table->text('fin')->nullable();
            $table->text('description')->nullable();
            $table->text('date_cloture')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contentieurs', function (Blueprint $table) {
            //
        });
    }
};
