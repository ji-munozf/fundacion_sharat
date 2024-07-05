<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postulations', function (Blueprint $table) {
            $table->id();
            $table->string('names');
            $table->string('last_names');
            $table->string('email');
            $table->string('contact_number');
            $table->string('curriculum_vitae'); // Este campo puede almacenar el nombre del archivo subido
            $table->string('strengths');
            $table->text('reasons');
            $table->foreignId('vacancy_id')->constrained('vacancies'); // Llave foránea hacia la tabla "vacancies"
            $table->foreignId('user_id')->constrained('users'); // Llave foránea hacia la tabla "users"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postulations');
    }
}

