<?php

namespace App\Console\Commands;

use App\Imports\AssessoriaImport;
use App\Imports\BuffetImport;
use App\Models\Assessoria;
use App\Models\Buffet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportarAssessoriaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:assessoria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command para importar Buffet da base de dados para Banco';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Inicio do Command");

        try {

            $assessorias = (new AssessoriaImport())->toArray('storage/assessoria.xlsx');

            $cpfsGerados = [];

            DB::beginTransaction();

            foreach ($assessorias as $assessoria) {
                foreach ($assessoria as $index => $cl) {

                    /**
                     * Verifica se já possui cadastro no banco
                     */

                    $cpf = $cl['cpf'];

                    $verificaCadastro = Assessoria::where('cpf_cnpj', $cpf)->first();

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
                    if ($cl['fon']) {
                        $primeiroTratamentoFoneFixo = str_replace('(', '', $cl['fon']);
                        $segundoTratamentoFoneFixo = str_replace(')', '', $primeiroTratamentoFoneFixo);
                        $foneFixo = str_replace('-', '', $segundoTratamentoFoneFixo);
                    }

                    $cel1 = null;
                    if ($cl['cel']) {
                        $primeiroTratamentoCel1 = str_replace('(', '', $cl['cel']);
                        $segundoTratamentoCel1 = str_replace(')', '', $primeiroTratamentoCel1);
                        $cel1 = str_replace('-', '', $segundoTratamentoCel1);
                    }

                    $estado = match (trim($cl['en_uf'])) {
                        '1111', 'A', 'AA', 'AAAAAAAAA', 'CABREUVA', 'JUNDIAÍ', 'SÃO APULO', 'SAO PAULO',
                        'SAÕ PAULO', 'SÁO PAULO', 'SÃO PAULO', 'SÃOPAULO', 'SÕ PAULO', 'XXX'   => 'SP',
                        'PARANA' => 'PR',
                        default => $cl['en_uf']
                    };

                    $novoCadastroAssessoria = new Assessoria();
                    $novoCadastroAssessoria->id = $cl['id'];
                    $novoCadastroAssessoria->tipo_cadastro = $tipoCadastro;
                    $novoCadastroAssessoria->nome_razao_social = ucwords(strtolower(trim($cl['no'])));
                    $novoCadastroAssessoria->slug = Str::slug(ucwords(strtolower(trim($cl['no']))), '_');
                    $novoCadastroAssessoria->mail = trim($cl['ml']);
                    $novoCadastroAssessoria->cpf_cnpj = $cpf;
                    $novoCadastroAssessoria->telefone = $foneFixo;
                    $novoCadastroAssessoria->celular = $cel1;                   2;
                    $novoCadastroAssessoria->cep = str_replace('-', '', $cl['en_cp']);
                    $novoCadastroAssessoria->logradouro = ucwords(strtolower(trim($cl['en'])));
                    $novoCadastroAssessoria->numero = trim($cl['en_nu']);
                    $novoCadastroAssessoria->complemento = trim($cl['en_cm']);
                    $novoCadastroAssessoria->bairro = ucwords(strtolower(trim($cl['en_br'])));
                    $novoCadastroAssessoria->cidade = ucwords(strtolower(trim($cl['en_ci'])));
                    $novoCadastroAssessoria->estado = $estado;
                    $novoCadastroAssessoria->ativo = $cl['at'];
                    $novoCadastroAssessoria->save();

                    $this->info('Importação Assessoria '. $index+1 .' concluida');

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
