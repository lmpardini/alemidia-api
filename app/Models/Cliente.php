<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cliente
 * @property integer $id
 * @property string $tipo_cadastro
 * @property string $nome_razao_social
 * @property string $mail
 * @property string $cpf_cnpj
 * @property string $rg_ie
 * @property string $telefone
 * @property string $celular
 * @property string $celular2
 * @property string $logradouro
 * @property string $cep
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $cidade
 * @property string $estado
 * @property boolean $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cliente';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
