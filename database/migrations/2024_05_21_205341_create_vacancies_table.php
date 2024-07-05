<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('job_title');
            $table->text('description');
            $table->string('contracting_manager');
            $table->integer('number_of_vacancies');
            $table->integer('gross_salary');
            $table->boolean('active')->default(true);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('institution_id')->constrained('institutions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
