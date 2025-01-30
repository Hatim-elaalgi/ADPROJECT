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
        Schema::create('themes', function (Blueprint $table) {
            $table->id(); // Ajoute une colonne 'id' auto-incrémentée pour identifier chaque enregistrement
            $table->string('title'); // Pour stocker le titre du thème
            $table->text('discription'); // Pour stocker la description du thème
            $table->boolean('is_accept')->default(false); // Pour indiquer si le thème est accepté ou non. Par défaut, il est marqué comme non accepté
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table users
            $table->timestamps(); // Ajoute les colonnes 'created_at' et 'updated_at' pour suivre les dates de création et de mise à jour

            // Définir la clé étrangère
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
