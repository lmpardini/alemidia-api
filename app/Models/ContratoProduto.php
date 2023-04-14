<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contrato Produto Classs
 *
 * @property integer $id
 * @property integer $contrato_id
 * @property integer $produto_id
 * @property Produto $Produto
 * @property integer $quantidade
 * @property integer $quantidade_foto
 * @property string $impressao
 * @property integer $tempo_evento
 * @property boolean $bazuca
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */


class ContratoProduto extends Model
{
    use HasFactory;

    protected $table = "contrato_produtos";

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Contrato()
    {
        return $this->hasMany(Contrato::class, 'id', 'contrato_id');
    }
    public function Produto()
    {
        return $this->hasOne(Produto::class, 'id', 'produto_id');
    }
}
