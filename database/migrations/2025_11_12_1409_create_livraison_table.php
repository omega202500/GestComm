<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terrain_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('chauffeur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->date('date_livraison')->nullable();
            $table->enum('status', ['en_cours','finalise','confirme','retour_effectue'])->default('en_cours');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('livraisons');
    }
};

