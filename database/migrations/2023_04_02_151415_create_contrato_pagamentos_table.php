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
        Schema::create('contrato_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contrato');
            $table->date('data_pagamento');
            $table->double('valor')->nullable();
            $table->unsignedBigInteger('forma_pagamento_id');
            $table->foreign('forma_pagamento_id')->references('id')->on('pagamento_forma_pagamento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_pagamentos');
    }
};
