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
        Schema::create('article_propose', function (Blueprint $table) {
            $table->id();
            
            // Attribut is_accepte pour indiquer l'état
            $table->boolean('is_accepte')->default(false); // false par défaut pour non accepté
            
            // Clé étrangère vers la table articles
            $table->unsignedBigInteger('article_id');
            
            $table->timestamps();
    
            // Définir la clé étrangère
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_propose');
    }
};
