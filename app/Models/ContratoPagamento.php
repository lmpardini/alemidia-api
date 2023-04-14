<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contrato Pagamentos Class
 *
 * @property $id
 * @property $contrato_id
 * @property Contrato $Contrato
 * @property $data_pagamento
 * @property $valor
 * @property $forma_pagamento_id
 * @property $created_at
 * @property $updated_at

 */

class ContratoPagamento extends Model
{
    use HasFactory;

    protected $table = 'contrato_pagamentos';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function Contrato()
    {
        return $this->hasMany(Contrato::class, 'id', 'contrato_id');
    }

    public function FormaPagamento()
    {
        return $this->hasOne(PagamentoFormaPagamento::class, 'id', 'forma_pagamento_id');
    }
}
