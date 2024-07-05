<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class SubscriptionController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware

{
    public static function middleware(): array
    {
        return [
            (new Middleware(middleware: 'can:Visualizar suscripciones'))->only('index'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::with('user')->get();
        return view('portal.subscriptions.index', compact('subscriptions'));
    }

    public function exportToExcel()
    {
        $subscriptions = Subscription::with('user')->get();

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser("suscripciones_usuarios.xlsx");

        // Estilo para el título
        $titleStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(16)
            ->setFontColor('000000')
            ->setBackgroundColor('D3D3D3')
            ->build();

        // Estilo para la descripción
        $descriptionStyle = (new StyleBuilder())
            ->setFontItalic()
            ->setFontSize(12)
            ->setFontColor('000000')
            ->build();

        // Estilo para los encabezados
        $headerStyle = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor('FFFFFF')
            ->setBackgroundColor('4CAF50') // Verde
            ->setCellAlignment(\Box\Spout\Common\Entity\Style\CellAlignment::CENTER)
            ->setShouldWrapText(true)
            ->build();

        // Estilo para las filas de datos
        $dataRowStyle = (new StyleBuilder())
            ->setFontSize(10)
            ->setCellAlignment(\Box\Spout\Common\Entity\Style\CellAlignment::CENTER)
            ->setShouldWrapText(true)
            ->build();

        // Crear el título
        $titleRow = WriterEntityFactory::createRow([
            WriterEntityFactory::createCell('Suscripciones de los usuarios', $titleStyle),
        ]);

        // Crear la descripción
        $descriptionRow = WriterEntityFactory::createRow([
            WriterEntityFactory::createCell('A continuación se mostrará una tabla con el historial de suscripciones de los usuarios en el portal Sharat Recruitment.', $descriptionStyle),
        ]);

        // Crear los encabezados
        $headerRow = WriterEntityFactory::createRowFromArray([
            'Usuario', 'Email', 'Duración del plan', 'Precio', 'Fecha de inicio', 'Fecha de término', 'Estado',
        ], $headerStyle);

        // Agregar las filas al archivo
        $writer->addRow($titleRow);
        $writer->addRow($descriptionRow);
        $writer->addRow(WriterEntityFactory::createRow([])); // Fila en blanco
        $writer->addRow($headerRow);

        // Agregar las filas de datos
        foreach ($subscriptions as $subscription) {
            $status = now()->lt($subscription->end_date) ? 'Activo' : 'Expirado';
            $dataRow = WriterEntityFactory::createRowFromArray([
                $subscription->user->name,
                $subscription->user->email,
                $subscription->duration,
                number_format($subscription->price, 0, ',', '.'),
                Carbon::parse($subscription->start_date)->format('d-m-Y H:i:s'),
                Carbon::parse($subscription->end_date)->format('d-m-Y H:i:s'),
                $status,
            ], $dataRowStyle);
            $writer->addRow($dataRow);
        }

        $writer->close();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
