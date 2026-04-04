<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('commercial_terrain_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('chauffeur_id')->constrained('users')->onDelete('cascade');

            $table->decimal('montant', 10, 2);
            $table->date('date_versement');
            $table->enum('mode_paiement', ['espece', 'mobile_money', 'carte']);
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('versements');
    }
};