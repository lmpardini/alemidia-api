<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PagamentosFormaCondições
 * @property integer $id
 * @property integer $forma_pagamento_id
 * @property integer $condicao_pagamento_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class PagamentosFormasCondicoes extends Model
{
    use HasFactory;

    protected $table = "pagamentos_formas_condicoes";

    protected $hidden = [
        'created_at',
        'updated_at',
        'laravel_through_key'
    ];
}
