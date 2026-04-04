<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('type'); 
            // vente | commande | versement | retour | rapport

            $table->string('titre')->nullable();
            $table->text('description')->nullable();

            $table->enum('status', ['nouveau', 'valide', 'refuse'])
                ->default('nouveau');

            $table->json('payload')->nullable(); // données envoyées (quantités, montants…)

            $table->timestamps();
        });
    }
}