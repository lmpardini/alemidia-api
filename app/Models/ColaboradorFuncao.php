<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ColaboradorFuncao
 *
 * @property integer $id
 * @property integer colaborador_id
 * @property integer funcao_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class ColaboradorFuncao extends Model
{
    use HasFactory;

    protected $table = 'colaborador_funcao';

    protected $hidden = [
        'laravel_through_key'
    ];
}
