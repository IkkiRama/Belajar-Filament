<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            now()->startOfYear();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now()->endOfYear();

        $pemasukan = Transaction::incomes()
                    ->whereBetween('date_transaction', [$startDate, $endDate])
                    ->sum("amount");

        $pengeluaran = Transaction::expenses()
                    ->whereBetween('date_transaction', [$startDate, $endDate])
                    ->sum("amount");

        return [
            Stat::make('Total Pemasukan', "Rp " . number_format($pemasukan)),
            Stat::make('Total Pengeluaran', "Rp " . number_format($pengeluaran)),
            Stat::make('Selisih', "Rp ". number_format($pemasukan - $pengeluaran)),
        ];
    }
}
