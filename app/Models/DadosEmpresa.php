<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DadosEmpresa
 *
 * @property integer $id
 * @property string $nome_fantasia
 * @property string $razao_social
 * @property string $cnpj
 * @property string $inscricao_estadual
 * @property string $tel_comercial
 * @property string $cel_comercial
 * @property string $cel_comercial2
 * @property string $site
 * @property string $representante_legal
 * @property string $cpf_representante
 * @property string $rg_representante
 * @property string $cep
 * @property string $logradouro
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $cidade
 * @property string $estado
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class DadosEmpresa extends Model
{
    use HasFactory;

    protected $table = 'dados_empresa';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
