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
        Schema::create('themes_users_', function (Blueprint $table) {
            $table->id(); // Ajoute une colonne 'id' auto-incrémentée pour identifier chaque enregistrement
            $table->unsignedBigInteger('theme_id'); // Clé étrangère vers la table themes
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table users
            $table->timestamps(); // Ajoute les colonnes 'created_at' et 'updated_at' pour suivre les dates de création et de mise à jour
        
            // Définir les clés étrangères
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
            // Optionnel : indexer les colonnes pour améliorer les performances des jointures
            $table->unique(['theme_id', 'user_id']); // Pour éviter les doublons
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes_users_');
    }
};
