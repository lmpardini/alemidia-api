<?php

namespace App\Console\Commands;

use App\Imports\ClienteImport;
use App\Models\Cliente;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ImportClientesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:clientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command para importar base de dados para Banco';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Inicio do Command");

        try {

            $clientes = (new ClienteImport)->toArray('storage/clientes.xlsx');

            $cpfsGerados = [];

            DB::beginTransaction();

            foreach ($clientes as $cliente) {
                foreach ($cliente as $index => $cl) {

                    /**
                     * Verifica se já possui cadastro no banco
                     */

                    $cpf = $cl['cpf'];

                    $verificaCadastro = Cliente::where('cpf_cnpj', $cpf)->first();

                    if ($verificaCadastro) {
                        $cpf = self::cpfRandom(false);
                        $cpfsGerados[] = $cpf;
                    }

                    /**
                     * Determina se é CPF ou CNPJ para atribuir o campo tipo_cadastro
                     */

                    $tipoCadastro = null;

                    if (strlen($cl['cpf']) >11) {
                        $tipoCadastro = 'pj';
                    } else {
                        $tipoCadastro = 'pf';
                    }

                    /**
                     * Trata campo Telefone Fixo, Celular e Celular2
                     */

                    $foneFixo = null;
                    if ($cl['fon1']) {
                        $primeiroTratamentoFoneFixo = str_replace('(', '', $cl['fon1']);
                        $segundoTratamentoFoneFixo = str_replace(')', '', $primeiroTratamentoFoneFixo);
                        $foneFixo = str_replace('-', '', $segundoTratamentoFoneFixo);
                    }

                    $cel1 = null;
                    if ($cl['cel1']) {
                        $primeiroTratamentoCel1 = str_replace('(', '', $cl['cel1']);
                        $segundoTratamentoCel1 = str_replace(')', '', $primeiroTratamentoCel1);
                        $cel1 = str_replace('-', '', $segundoTratamentoCel1);
                    }

                    $cel2 = null;
                    if ($cl['cel1']) {
                        $primeiroTratamentoCel2 = str_replace('(', '', $cl['cel2']);
                        $segundoTratamentoCel2 = str_replace(')', '', $primeiroTratamentoCel2);
                        $cel2 = str_replace('-', '', $segundoTratamentoCel2);
                    }

                    $estado = match (trim($cl['en_uf'])) {
                        '1111', 'A', 'AA', 'AAAAAAAAA', 'CABREUVA', 'JUNDIAÍ', 'SÃO APULO', 'SAO PAULO',
                        'SAÕ PAULO', 'SÁO PAULO', 'SÃO PAULO', 'SÃOPAULO', 'SÕ PAULO', 'XXX'   => 'SP',
                        'PARANA' => 'PR',
                        default => $cl['en_uf']
                    };

                    $novoCadastroCliente = new Cliente();
                    $novoCadastroCliente->id = $cl['id'];
                    $novoCadastroCliente->tipo_cadastro = $tipoCadastro;
                    $novoCadastroCliente->nome_razao_social = ucwords(strtolower(trim($cl['no'])));
                    $novoCadastroCliente->mail = trim($cl['ml']);
                    $novoCadastroCliente->cpf_cnpj = $cpf;
                    $novoCadastroCliente->rg_ie = $cl['rg'];
                    $novoCadastroCliente->telefone = $foneFixo;
                    $novoCadastroCliente->celular = $cel1;
                    $novoCadastroCliente->celular2 = $cel2;
                    $novoCadastroCliente->cep = str_replace('-', '', $cl['en_cp']);
                    $novoCadastroCliente->logradouro = ucwords(strtolower(trim($cl['en'])));
                    $novoCadastroCliente->numero = trim($cl['en_nu']);
                    $novoCadastroCliente->complemento = trim($cl['en_cm']);
                    $novoCadastroCliente->bairro = ucwords(strtolower(trim($cl['en_br'])));
                    $novoCadastroCliente->cidade = ucwords(strtolower(trim($cl['en_ci'])));
                    $novoCadastroCliente->estado = $estado;
                    $novoCadastroCliente->ativo = $cl['at'];
                    $novoCadastroCliente->save();

                    $this->info('Importação cliente '. $index+1 .' concluida');

                }
            }

            DB::commit();

            $this->warn("CPF's gerados pelo sistema");
            dump($cpfsGerados);

            $this->info("Fim do Command");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }

    /**
     * Método para gerar CPF válido, com máscara ou não
     * @example cpfRandom(0)
     *          para retornar CPF sem máscar
     * @param int $mascara
     * @return string
     */
    public static function cpfRandom(bool $mascara) {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - (self::mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - (self::mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        $retorno = '';
        if ($mascara) {
            $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
        }
        return $retorno;
    }


    private static function mod($dividendo, $divisor) {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }
}
