<?php

namespace App\Exports;

use App\Models\Vacancy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VacanciesExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison, WithStyles, WithColumnFormatting, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Vacancy::with('user', 'institution')->withCount('applications')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nombre',
            'Título del puesto',
            'Descripción',
            'Gerente de contrataciones',
            'Número de vacantes',
            'Sueldo bruto',
            'Activo',
            '¿Está eliminado?',
            'Creador',
            'Institución',
            'Total de postulaciones',
        ];
    }

    /**
     * @param $vacancy
     * @return array
     */
    public function map($vacancy): array
    {
        return [
            $vacancy->name,
            $vacancy->job_title,
            $vacancy->description,
            $vacancy->contracting_manager,
            $vacancy->number_of_vacancies,
            $vacancy->gross_salary,
            $vacancy->active ? 'Sí' : 'No',
            $vacancy->is_eliminated ? 'Sí' : 'No',
            $vacancy->user->name,
            $vacancy->institution->name,
            $vacancy->applications_count,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos a la primera fila (encabezados)
        $sheet->getStyle('A1:K1')->applyFromArray([
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
        $sheet->getStyle('A:K')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return [];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE, // Formato de moneda para la columna del sueldo bruto
        ];
    }

    public function title(): string
    {
        return 'Tabla de vacantes';
    }
}
