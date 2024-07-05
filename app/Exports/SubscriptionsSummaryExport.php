<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubscriptionsSummaryExport implements FromCollection, WithStyles, WithTitle
{
    public function collection()
    {
        // Obtener la cantidad de suscripciones, cantidad de usuarios suscritos y el total del precio de las suscripciones
        $summary = DB::table('subscriptions')
            ->selectRaw('COUNT(*) as total_subscriptions')
            ->selectRaw('COUNT(DISTINCT user_id) as total_users')
            ->selectRaw('SUM(price) as total_price')
            ->first();

        // Convertir el resultado en una colección de Laravel para exportar
        return collect([
            ['Total de Suscripciones', $summary->total_subscriptions],
            ['Total de Usuarios Suscritos', $summary->total_users],
            ['Total del Precio de Suscripciones', $summary->total_price],
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos a la primera fila (encabezados)
        $sheet->getStyle('A1:A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFE699'],
            ],
        ]);

        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFE699'],
            ],
        ]);

        // Aplicar estilos generales a las celdas
        $sheet->getStyle('A:B')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'B') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Aplicar formato de número a la celda que contiene el precio total
        $sheet->getStyle('B3')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        return [];
    }

    public function title(): string
    {
        return 'Total de suscripciones';
    }
}
