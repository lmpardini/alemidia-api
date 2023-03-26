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
        Schema::create('pagamentos_formas_condicoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forma_pagamento_id');
            $table->foreign('forma_pagamento_id')->references('id')->on('pagamento_forma_pagamento');
            $table->unsignedBigInteger('condicao_pagamento_id');
            $table->foreign('condicao_pagamento_id')->references('id')->on('pagamento_condicao_pagamento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos_formas_condicoes');
    }
};
