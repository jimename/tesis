<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tornaguias', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->string('numero')->nullable();
            $table->string('yacimiento')->nullable();
            $table->string('tranca')->nullable();
            $table->string('cuadrilla')->nullable();
            $table->string('tipoMaterial')->nullable();
            $table->string('minerales')->nullable();
            $table->integer('peso')->nullable();
            $table->integer('sacos')->nullable();
            $table->unsignedBigInteger('transporte_id')->nullable();
            $table->foreign('transporte_id')->references('id')->on('transportes');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('contratista_id')->nullable();
            $table->foreign('contratista_id')->references('id')->on('contratistas');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->string('tipo')->nullable()->comment('Administrativo, Vigilancia');
            $table->string('observacionAdministrativo')->nullable();
            $table->string('estadoAdministrativo')->nullable();
            $table->string('observacionVigilancia')->nullable();
            $table->string('aprobado')->nullable()->comment('Aprobado por el administrador')->default('Pendiente');
            $table->string('recibido')->nullable()->default('Pendiente');
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
        Schema::dropIfExists('tornaguias');
    }
};
