<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbpedidoitens extends Model
{
    protected $fillable = ['numero_pedido', 'id_produto', 'cod_produto', 'descricao',
    'marca', 'tipo', 'qtd_pedida', 'preco_unitario', 'preco_total', 'obs_produto'];
}
