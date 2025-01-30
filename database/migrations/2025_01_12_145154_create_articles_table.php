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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Pour le titre
            $table->text('content'); // Pour le contenu
            $table->string('image_url')->nullable(); // Pour l'URL de l'image (nullable si non obligatoire)
            
            // Attribut status avec 4 états
            $table->enum('status', ['Refus', 'En cours', 'Retenu', 'Publié'])->default('En cours');
            
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table users
            $table->unsignedBigInteger('theme_id'); // Clé étrangère vers la table themes
            $table->timestamps();
        
            // Définir les clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
