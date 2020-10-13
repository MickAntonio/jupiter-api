<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoleiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boleias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('solicitante_id');
            $table->unsignedInteger('motorista_id')->nullable();
            $table->text('motivo')->nullable();
            $table->enum('tipo', ['BOLEIA','AGENDAMENTO']);
            $table->dateTime('horario')->nullable();
            $table->enum('estado', ['SOLICITADA', 'AGENDADA', 'INICIADA', 'FINALIZADA', 'CANCELADA']);
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('funcionarios')->onDelete('cascade');
            $table->foreign('motorista_id')->references('id')->on('funcionarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boleias');
    }
}
