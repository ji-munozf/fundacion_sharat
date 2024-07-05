<?php

namespace App\Exports;

use App\Models\Postulation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostulationsTableExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $postulations = Postulation::with(['vacancy', 'user'])->get();

        return $postulations->map(function ($postulation) {
            return [
                $postulation->names,
                $postulation->last_names,
                $postulation->email,
                substr($postulation->contact_number, 0, 4) . ' ' . substr($postulation->contact_number, 4),
                basename($postulation->curriculum_vitae),
                $postulation->strengths,
                $postulation->reasons,
                $postulation->is_eliminated ? 'Sí' : 'No',
                $postulation->vacancy->name ?? 'N/A',
                $postulation->vacancy->job_title ?? 'N/A',
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nombres',
            'Apellidos',
            'Email',
            'Teléfono de contacto',
            'Curriculum Vitae',
            'Fortalezas',
            'Razones',
            '¿Está eliminado?',
            'Nombre de la vacante',
            'Título del puesto',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos a la primera fila (encabezados)
        $sheet->getStyle('A1:J1')->applyFromArray([
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
        $sheet->getStyle('A:J')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Tabla de postulaciones';
    }
}
