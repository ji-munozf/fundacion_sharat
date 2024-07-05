<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostulationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postulation_status', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->nullable();
            $table->text('reasons')->nullable();
            $table->foreignId('postulation_id')->constrained('postulations')->onDelete('cascade'); // Llave foránea hacia la tabla "postulations"
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
        Schema::dropIfExists('postulation_status');
    }
}
