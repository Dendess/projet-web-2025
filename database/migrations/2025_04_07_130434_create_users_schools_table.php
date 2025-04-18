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
        // Modifier la table 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('average')->nullable(); // ou ->default(0)
            $table->unsignedBigInteger('cohort_id')->nullable(); // ou ->default(0)
        });

        // Créer la table 'users_schools'
        Schema::create('users_schools', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('cohort_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');

            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les colonnes de la table 'users'
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['average', 'cohort_id']);
        });

        // Supprimer la table 'users_schools'
        Schema::dropIfExists('users_schools');
    }
};
