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
        Schema::table('denuncias', function (Blueprint $table) {
            if (!Schema::hasColumn('denuncias', 'comentario_admin')) {
                $table->text('comentario_admin')->nullable()->after('admin_comentario');
            }
            if (!Schema::hasColumn('denuncias', 'procesada_por')) {
                $table->foreignId('procesada_por')->nullable()->constrained('users')->nullOnDelete()->after('comentario_admin');
            }
            if (!Schema::hasColumn('denuncias', 'procesada_at')) {
                $table->timestamp('procesada_at')->nullable()->after('procesada_por');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denuncias', function (Blueprint $table) {
            if (Schema::hasColumn('denuncias', 'procesada_por')) {
                $table->dropForeign(['procesada_por']);
            }
            $table->dropColumn(['comentario_admin', 'procesada_por', 'procesada_at']);
        });
    }
};
