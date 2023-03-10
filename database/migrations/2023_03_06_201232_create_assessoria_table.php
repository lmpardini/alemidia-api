<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessoria', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_cadastro');
            $table->string('nome_razao_social')->unique();
            $table->string('slug')->unique();
            $table->string('mail');
            $table->string('telefone')->nullable();
            $table->string('celular');
            $table->string('celular2')->nullable();
            $table->string('cep');
            $table->string('logradouro');
            $table->string('numero');
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('cc')->nullable();
            $table->string('cpf_cnpj')->nullable()->unique();
            $table->string('chave_pix')->nullable();
            $table->string('comissao')->nullable();
            $table->boolean('ativo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessoria');
    }
};
