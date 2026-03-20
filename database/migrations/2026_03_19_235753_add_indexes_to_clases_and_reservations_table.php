<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToClasesAndReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $hasIndex = fn($table, $name) => DB::selectOne(
            "SELECT COUNT(*) as cnt FROM information_schema.statistics
             WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?",
            [$table, $name]
        )->cnt > 0;

        if (!$hasIndex('clases', 'clases_clase_type_id_index')) {
            Schema::table('clases', function (Blueprint $table) {
                $table->index('clase_type_id');
            });
        }

        if (!$hasIndex('reservations', 'reservations_clase_id_index')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->index('clase_id');
            });
        }

        if (!$hasIndex('reservations', 'reservations_clase_id_user_id_index')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->index(['clase_id', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::table('clases', function (Blueprint $table) {
            $table->dropIndex(['clase_type_id']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['clase_id', 'user_id']);
            $table->dropIndex(['clase_id']);
        });
    }
}
