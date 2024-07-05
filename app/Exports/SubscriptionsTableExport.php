<?php

namespace App\Exports;

use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubscriptionsTableExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $subscriptions = Subscription::with('user')->get();

        return $subscriptions->map(function ($subscription) {
            $status = now()->lt($subscription->end_date) ? 'Activo' : 'Expirado';
            return [
                $subscription->user->name,
                $subscription->user->email,
                $subscription->duration,
                $subscription->price,
                \Carbon\Carbon::parse($subscription->start_date)->format('d-m-Y H:i'),
                \Carbon\Carbon::parse($subscription->end_date)->format('d-m-Y H:i'),
                $status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Usuario',
            'Email',
            'Duración del plan',
            'Precio',
            'Fecha de inicio',
            'Fecha de término',
            'Estado',
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

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    public function title(): string
    {
        return 'Tabla de suscripciones';
    }
}
