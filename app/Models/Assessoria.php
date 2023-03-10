<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Assessoria Class
 *
 * @property integer $id
 * @property string $tipo_cadastro
 * @property string $nome_razao_social
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at *
 */

class Assessoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "assessoria";

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
