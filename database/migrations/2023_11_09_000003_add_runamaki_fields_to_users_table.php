<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->integer('puntos_runa')->default(100)->after('avatar');
            $table->decimal('reputacion', 3, 2)->default(5.00)->after('puntos_runa');
            $table->string('nivel')->default('Principiante')->after('reputacion');
            $table->enum('estado', ['activo', 'suspendido', 'inactivo'])->default('activo')->after('nivel');
            $table->enum('rol', ['usuario', 'admin'])->default('usuario')->after('estado');
            $table->string('ubicacion')->default('Cusco, Perú')->after('rol');
            $table->timestamp('ultima_conexion')->nullable()->after('ubicacion');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'puntos_runa',
                'reputacion',
                'nivel',
                'estado',
                'rol',
                'ubicacion',
                'ultima_conexion'
            ]);
        });
    }
};