<?php

namespace Database\Seeders;

use App\Models\DadosEmpresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DadosEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dadosEmpresa = new DadosEmpresa();
        $dadosEmpresa->nome_fantasia = "Alemidia";
        $dadosEmpresa->razao_social = "Luciana Franzini de Oliveira Ribeiro ME";
        $dadosEmpresa->cnpj = "28039737000137";
        $dadosEmpresa->inscricao_estadual = "Alemidia";
        $dadosEmpresa->tel_comercial = "1133958775";
        $dadosEmpresa->cel_comercial = "11963461665";
        $dadosEmpresa->cel_comercial2 = "";
        $dadosEmpresa->site = "www.alemidia.com.br";
        $dadosEmpresa->representante_legal = "Luciana Franzini de Oliveira Ribeiro";
        $dadosEmpresa->cpf_representante = "29620645820";
        $dadosEmpresa->rg_representante = "423778419";
        $dadosEmpresa->cep = "13209-260";
        $dadosEmpresa->logradouro = "Rua 18 de Junho";
        $dadosEmpresa->numero = "60";
        $dadosEmpresa->complemento = "";
        $dadosEmpresa->bairro = "Jardim Morumbi";
        $dadosEmpresa->cidade = "Jundiaí";
        $dadosEmpresa->estado = "SP";
        $dadosEmpresa->save();
    }
}
