<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Pagamento Condicao de Pagamento Class
 * @property integer $id
 * @property string $nome
 * @property string $slug
 * @property string $descricao
 * @property boolean $aceita_parcelamento
 * @property boolean $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 */

class PagamentoCondicaoPagamento extends Model
{
    use HasFactory;

    protected $table = "pagamento_condicao_pagamento";

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function PagamentoFormaPagamento(): HasManyThrough{
        return $this->hasManyThrough(
            PagamentoFormaPagamento::class,
             PagamentosFormasCondicoes::class,
            'condicao_pagamento_id',
            'id',
            'id',
            'forma_pagamento_id',
        );

    }
}
