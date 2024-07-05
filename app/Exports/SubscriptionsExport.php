<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SubscriptionsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new SubscriptionsTableExport(),
            new SubscriptionsSummaryExport(),
        ];
    }
}
