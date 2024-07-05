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
        Schema::create('postulation_user_data', function (Blueprint $table) {
            $table->id();
            $table->string('names');
            $table->string('last_names');
            $table->string('email');
            $table->string('contact_number');
            $table->string('curriculum_vitae'); // Este campo puede almacenar el nombre del archivo subido
            $table->string('strengths');
            $table->text('reasons');
            $table->foreignId('user_id')->constrained('users'); // Llave foránea hacia la tabla "users"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulation_user_data');
    }
};
