<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Colaborador Class
 *
 * @property integer $id
 * @property string $nome
 * @property string $slug
 * @property string $mail
 * @property string $telefone
 * @property string $celular
 * @property string $celular2
 * @property string $cep
 * @property string $logradouro
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $cidade
 * @property string $estado
 * @property string $banco
 * @property string $agencia
 * @property string $cc
 * @property string $cpf_cnpj
 * @property string $chave_pix
 * @property string $comissao
 * @property boolean $ativo
 * @property Funcao $Funcao
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Colaborador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "colaborador";

    protected $hidden = [
        'laravel_through_key',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function Funcao(): HasManyThrough
    {
        return $this->hasManyThrough(
            Funcao::class,
            ColaboradorFuncao::class,
            'colaborador_id',
            'id',
            'id',
            'funcao_id'
        );
    }
}
