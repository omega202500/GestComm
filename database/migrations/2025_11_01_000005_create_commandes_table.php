<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('commercial_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            //  $table->foreignId('client_nom')->constrained()->onDelete('cascade');
            $table->foreignId('id')->constrained('clients')->onDelete('cascade');
            $table->string('client_tel');
            $table->enum('statut', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->date('date_livraison');
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commandes');
    }
};
