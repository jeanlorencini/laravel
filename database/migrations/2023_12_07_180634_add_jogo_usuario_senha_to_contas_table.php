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
        Schema::table('contas', function (Blueprint $table) {
            $table->string('jogo');
            $table->string('usuario');
            $table->string('senha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contas', function (Blueprint $table) {
            $table->dropColumn(['jogo', 'usuario', 'senha']);
        });
    }
};
