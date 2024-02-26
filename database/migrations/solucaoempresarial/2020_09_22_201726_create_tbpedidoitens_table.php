<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbpedidoitensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysqlIntegracao')->create('tbpedidoitens', function (Blueprint $table) {
            $table->increments('sequencia');

            $table->string('numero_pedido', 255);
            $table->integer('id_produto');

            $table->string('cod_produto', 50)->nullable();
            $table->string('descricao', 300)->nullable();
            $table->string('marca', 50)->nullable();
            $table->string('tipo', 20)->nullable();

            $table->float('qtd_pedida', 18, 5)->nullable();
            $table->float('preco_unitario', 18, 5)->nullable();
            $table->float('preco_total', 18, 5)->nullable();
            $table->string('obs_produto', 300)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbpedidoitens');
    }
}
