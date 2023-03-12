<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Função
 * @property integer $id
 * @property string $nome_funcao
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Funcao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "funcao";

    protected $hidden = [
      'laravel_through_key',
      'created_at',
      'deleted_at',
      'updated_at'
    ];
}
