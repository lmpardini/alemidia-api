<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BuffetImport implements  ToCollection, WithHeadingRow
{
    use Importable;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {

    }
}
