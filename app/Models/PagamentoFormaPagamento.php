<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Forma Pagamento Class
 * @property integer $id
 * @property string $nome
 * @property string $slug
 * @property string $descricao
 * @property boolean $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 */

class PagamentoFormaPagamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "pagamento_forma_pagamento";

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'laravel_through_key'
    ];
}
