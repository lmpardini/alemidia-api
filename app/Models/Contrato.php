<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Contrato Class
 *
 * @property integer $id
 * @property Carbon $data_evento
 * @property string $noivo_debutante
 * @property integer $cliente_id
 * @property Cliente $Cliente
 * @property integer $buffet_id
 * @property Buffet $Buffet
 * @property integer $assessoria_id
 * @property Assessoria $Assessoria
 * @property integer $vendedor_id
 * @property Colaborador $Vendedor
 * @property integer $condicao_pgto_id
 * @property PagamentoCondicaoPagamento $CondicaoPagamento
 * @property Carbon $hora_inicio
 * @property Carbon $hora_fim
 * @property integer $qtde_convidados
 * @property integer $valor_total
 * @property string $observacoes
 * @property string $observacao_pagamento
 * @property string $forma_pgto_padrao
 * @property integer $parcelas
 * @property string $hash_verificacao
 * @property ContratoProduto $ContratoProduto
 * @property ContratoPagamento $ContratoPagamento
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Contrato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contrato';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function Cliente()
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
    }

    public function Buffet()
    {
        return $this->hasOne(Buffet::class, 'id', 'buffet_id');
    }

    public function Assessoria()
    {
        return $this->hasOne(Assessoria::class, 'id', 'assessoria_id');
    }

    public function Vendedor()
    {
        return $this->hasOne(Colaborador::class, 'id', 'vendedor_id');
    }

    public function CondicaoPagamento()
    {
        return $this->hasOne(PagamentoCondicaoPagamento::class, 'id', 'condicao_pgto_id');
    }

    public function ContratoPagamento()
    {
        return $this->hasMany(ContratoPagamento::class, 'contrato_id', 'id');
    }

    public function ContratoProduto()
    {
        return $this->hasMany(ContratoProduto::class, 'contrato_id', 'id');
    }
}
