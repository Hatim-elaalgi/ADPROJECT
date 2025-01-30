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
        Schema::create('numeros_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('numero_id'); // Clé étrangère vers la table numeros
            $table->unsignedBigInteger('article_id'); // Clé étrangère vers la table articles
            $table->timestamps();

            // Définir les clés étrangères
            $table->foreign('numero_id')->references('id')->on('numeros')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');

            // Optionnel : indexer les colonnes pour améliorer les performances des jointures
            $table->unique(['numero_id', 'article_id']); // Pour éviter les doublons
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numeros_articles');
    }
};
