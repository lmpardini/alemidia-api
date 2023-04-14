<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportarBaseDadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:tudo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command para chamar todos os commands que fazem importação de dados';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
       $this->call('importar:assessoria');
       $this->call('importar:buffet');
       $this->call('importar:clientes');
       $this->call('importar:vendedor');
    }
}
