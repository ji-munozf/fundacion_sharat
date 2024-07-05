<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PostulationsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new PostulationsTableExport(),
            new PostulationsByVacancyExport(),
        ];
    }
}
