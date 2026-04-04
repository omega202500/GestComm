<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chargements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chauffeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->integer('quantite');
            $table->date('date_chargement');
            $table->enum('statut', ['charge', 'livre', 'retour'])->default('charge');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chargements');
    }
};
