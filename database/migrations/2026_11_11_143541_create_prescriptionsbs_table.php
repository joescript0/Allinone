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
        Schema::create('prescriptionsbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("signesvitauxpatient_id")->constrained();
            $table->foreignId("user_id")->constrained();
            $table->foreignId("article_id")->constrained();
            $table->text('mode_emploi')->nullable();
            $table->integer('etat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptionsbs');
    }
};
