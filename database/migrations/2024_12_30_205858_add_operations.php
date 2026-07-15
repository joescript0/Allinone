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
        Schema::table('entres', function (Blueprint $table) {
            //
            $table->text('n_piece')->nullable();
            $table->double('entree')->nullable();
            $table->double('sortie')->nullable();
            $table->integer("type")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entres', function (Blueprint $table) {
            //
        });
    }
};
