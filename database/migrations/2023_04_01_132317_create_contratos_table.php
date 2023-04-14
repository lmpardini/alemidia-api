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
        Schema::create('contrato', function (Blueprint $table) {
            $table->id();
            $table->date('data_evento');
            $table->string('noivo_debutante');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('cliente');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->integer('qtde_convidados');
            $table->unsignedBigInteger('buffet_id');
            $table->foreign('buffet_id')->references('id')->on('buffet');
            $table->unsignedBigInteger('assessoria_id');
            $table->foreign('assessoria_id')->references('id')->on('assessoria');
            $table->unsignedBigInteger('vendedor_id');
            $table->foreign('vendedor_id')->references('id')->on('colaborador');
            $table->unsignedBigInteger('condicao_pgto_id');
            $table->foreign('condicao_pgto_id')->references('id')->on('pagamento_condicao_pagamento');
            $table->float('valor_total')->nullable();
            $table->string('observacoes')->nullable();
            $table->string('forma_pgto_padrao')->nullable();
            $table->integer('parcelas')->nullable();
            $table->string('observacao_pagamento')->nullable();
            $table->string('hash_verificacao');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
