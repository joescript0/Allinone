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
        Schema::table('Alertes', function (Blueprint $table) {
            //
            $table->dropColumn('user_id_desactiver');
            $table->dropColumn('etat');
            $table->integer("etat_1")->default(1);
            $table->string("user_id_desactiver_etat_1")->default(0);
            $table->string("date_desactiver_etat_1")->nullable();
            $table->string("date_transfert")->nullable();
            $table->string("description_descente")->nullable();
            $table->integer("etat_2")->default(0);
            $table->string("user_id_desactiver_etat_2")->default(0);
            $table->string("date_desactiver_etat_2")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Alertes', function (Blueprint $table) {
            //
        });
    }
};
