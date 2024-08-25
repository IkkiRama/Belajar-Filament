<?php

namespace App\Filament\Widgets;



use Flowframe\Trend\Trend;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class WidgetIncomeChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Pemasukan';
    protected static string $color = 'success';

    protected function getData(): array
    {
        $startDate = (!is_null($this->filters['startDate'] ?? null ?? $this->filters['startDate']) &&
                    $this->filters['startDate']="") ?
            Carbon::parse($this->filters['startDate']) :
            now()->startOfYear();

        $endDate = (!is_null($this->filters['endDate'] ?? null ?? $this->filters['startDate']) &&
                    $this->filters['endDate']="") ?
            Carbon::parse($this->filters['endDate']) :
            now()->endOfYear();

        $cek = $this->filters['startDate']!="";
            // dd($cek);
            $data = (!is_null($this->filters['endDate'] ?? null) && $cek
                )  ?

                Trend::query(Transaction::incomes())
                ->between(
                    start: $startDate,
                    end: $endDate,
                )
                ->perDay()
                ->sum("amount"):
                Trend::query(Transaction::incomes())
                ->between(
                    start: $startDate,
                    end: $endDate,
                )
                ->perMonth()
                ->sum("amount");
        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
