<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFilasTableAddSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('filas', 'settings')) {
            Schema::table('filas', function (Blueprint $table) {
                $table->json('settings')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('filas', 'settings')) {
            Schema::table('filas', function (Blueprint $table) {
                $table->dropColumn('settings');
            });
        }
    }
}
