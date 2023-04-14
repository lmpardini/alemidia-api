<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Produto
 * @property integer $id
 * @property string $nome
 * @property string $slug
 * @property string $descricao
 * @property integer $quantidade_dia
 * @property boolean $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "produto";

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
