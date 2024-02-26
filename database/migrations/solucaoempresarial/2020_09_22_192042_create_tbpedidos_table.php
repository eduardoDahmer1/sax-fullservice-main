<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbpedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysqlIntegracao')->create('tbpedidos', function (Blueprint $table) {
            $table->increments('id');

            $table->string('numero_pedido', 255);
            $table->integer('cod_cliente');
            $table->string('nome_cliente', 300)->nullable();
            $table->timestamp('data_hora')->useCurrent();
            $table->integer('cod_vendedor');
            $table->string('nome_vendedor', 50)->nullable();

            $table->float('subtotal', 18, 5)->nullable();
            $table->float('desconto_porcentual', 18, 5)->default('0.0');
            $table->float('desconto_valor', 18, 5)->default('0.0');
            $table->float('total', 18, 5)->default('0.0');

            $table->string('nome_entrega', 100)->nullable();
            $table->string('cep_entrega', 10)->nullable();
            $table->string('endereco_entrega', 100)->nullable();
            $table->string('numero_entrega', 20)->nullable();
            $table->string('bairro_entrega', 100)->nullable();
            $table->string('cidade_entrega', 100)->nullable();
            $table->char('estado_entrega', 2)->nullable();
            $table->string('telefone_entrega', 20)->nullable();
            $table->string('celular_entrega', 20)->nullable();
            $table->string('obs_entrega', 350)->nullable();

            $table->string('cpf_cnpj', 25)->nullable();
            $table->string('rg_ie', 30)->nullable();

            $table->string('cep', 10)->nullable();
            $table->string('endereco', 200)->nullable();
            $table->string('numero', 15)->nullable();
            $table->string('cod_cidade', 15)->nullable();
            $table->string('nome_cidade', 80)->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('email', 200)->nullable();

            $table->char('tipo_contribuinte', 1)->default(3);
            $table->char('consumidor_final', 1)->default(5);
            $table->integer('indicador_presenca')->default(2);

            $table->text('observacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbpedidos');
    }
}
