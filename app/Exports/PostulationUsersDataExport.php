<?php

namespace App\Exports;

use App\Models\PostulationUserData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostulationUsersDataExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function collection()
    {
        return PostulationUserData::select('names', 'last_names', 'email', 'contact_number', 'curriculum_vitae', 'strengths', 'reasons')
            ->get()
            ->map(function ($item) {
                // Formatear el número de contacto
                $item->contact_number = substr($item->contact_number, 0, 4) . ' ' . substr($item->contact_number, 4);
                // Obtener solo el nombre base del archivo
                $item->curriculum_vitae = basename($item->curriculum_vitae);
                return $item;
            });
    }

    public function headings(): array
    {
        return [
            'Nombres',
            'Apellidos',
            'Email',
            'Número de contacto',
            'Curriculum vitae',
            'Fortalezas',
            'Razones',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos a la primera fila (encabezados)
        $sheet->getStyle('A1:G1')->applyFromArray([
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
        $sheet->getStyle('A:G')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Tabla de suscripciones';
    }

    public function map($row): array
    {
        return [
            $row->names,
            $row->last_names,
            $row->email,
            '+569 ' . substr($row->contact_number, 3), // Formato +569 seguido de espacio y los 8 dígitos
            basename($row->curriculum_vitae), // Obtener solo el nombre del archivo del currículum
            $row->strengths,
            $row->reasons,
        ];
    }
}
