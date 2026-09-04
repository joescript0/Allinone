<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listesdesinvites', function (Blueprint $table) {
            // 1. Supprimer la clé étrangère
            $table->dropForeign(['table_id']);

            // 2. Supprimer la colonne
            $table->dropColumn('table_id');
        });

        // 3. Recréer la colonne comme un integer normal (sans contrainte)
        Schema::table('listesdesinvites', function (Blueprint $table) {
            $table->integer('table_id')->default(0);
        });
    }

    public function down(): void
    {
        // Pour revenir en arrière, on recrée la colonne en BIGINT avec clé étrangère
        Schema::table('listesdesinvites', function (Blueprint $table) {
            $table->dropColumn('table_id');
            $table->unsignedBigInteger('table_id');
            $table->foreign('table_id')->references('id')->on('tables');
        });
    }
};