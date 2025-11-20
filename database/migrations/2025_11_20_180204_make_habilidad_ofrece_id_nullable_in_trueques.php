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
        Schema::table('trueques', function (Blueprint $table) {
            $table->unsignedBigInteger('habilidad_ofrece_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trueques', function (Blueprint $table) {
            // No podemos deshacer el cambio nullable fácilmente
        });
    }
};
