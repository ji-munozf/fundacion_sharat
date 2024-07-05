<?php

namespace App\Exports;

use App\Models\Postulation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostulationsByVacancyExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $postulations = Postulation::with('vacancy')
            ->whereHas('user', function ($query) {
                $query->role('postulante'); // Usando spatie/laravel-permission
            })
            ->get();

        $grouped = $postulations->groupBy('vacancy_id')->map(function ($items, $key) {
            $vacancy = $items->first()->vacancy;
            $userCount = $items->groupBy('user_id')->count();
            return [
                'Nombre de la vacante' => $vacancy->name,
                'Título del puesto' => $vacancy->job_title,
                'Cantidad de usuarios' => $userCount,
            ];
        });

        return $grouped->values();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nombre de la vacante',
            'Título del puesto',
            'Cantidad de usuarios',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos a la primera fila (encabezados)
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFE699'],
            ],
        ]);

        // Aplicar estilos generales a las celdas
        $sheet->getStyle('A:C')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Postulaciones según vacantes';
    }
}

