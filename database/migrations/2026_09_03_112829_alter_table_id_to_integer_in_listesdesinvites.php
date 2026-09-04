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
        Schema::table('listesdesinvites', function (Blueprint $table) {
            //
            $table->string("code_unique")->nullable();
            $table->string("dans_la_salle")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listesdesinvites', function (Blueprint $table) {
            //
        });
    }
};
