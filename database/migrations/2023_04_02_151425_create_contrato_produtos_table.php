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
        Schema::create('contrato_produtos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrato_id');
            $table->foreign('contrato_id')->references('id')->on('contrato');
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produto');
            $table->integer('quantidade');
            $table->integer('quantidade_foto')->nullable();
            $table->string('impressao')->nullable();
            $table->integer('tempo_evento');
            $table->boolean('bazuca')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_produtos');
    }
};
