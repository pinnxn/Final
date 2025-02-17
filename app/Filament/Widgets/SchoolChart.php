<?php

namespace App\Filament\Widgets;

use App\Models\Register;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SchoolChart extends ChartWidget
{
    protected static ?string $heading = 'จำนวนผู้สมัครตามสถานศึกษา';

    public ?string $filter = 'year';

    protected function getFilters(): ?array
    {
        $years = Register::query()
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->mapWithKeys(function ($year) {
                return [$year => "ปี $year"];
            })
            ->toArray();

        // Add "All Years" option at the beginning
        return ['all' => 'ทุกปี'] + $years;
    }
    protected function getData(): array
    {
        $query = Register::query()
            ->select('name_education', DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as total'))
            ->groupBy('name_education', 'year')
            ->orderBy('name_education')
            ->orderBy('year');

        // Apply year filter if selected and not 'all'
        if ($this->filter && $this->filter !== 'all') {
            $query->having('year', '=', $this->filter);
        }
        $data = $query->get();

        // Group data by year
        $years = $data->pluck('year')->unique()->values();
        $schools = $data->pluck('name_education')->unique()->values();

        $datasets = [];
        $colors = [
            '#66c2a5',  // Soft Teal
            '#fc8d62',  // Soft Orange
            '#8da0cb',  // Soft Blue
            '#e78ac3',  // Soft Magenta
            '#a6d854',  // Soft Green
            '#ffd92f',  // Soft Yellow
            '#e5c494',  // Tan
            '#b3b3b3',  // Gray
            '#8dd3c7',  // Light Teal
            '#bebada'   // Lavender
            ];

        foreach ($years as $index => $year) {
            $yearData = $data->where('year', $year);
            $datasets[] = [
                'label' => "ปี $year",
                'data' => $schools->map(function ($school) use ($yearData) {
                    return $yearData->where('name_education', $school)->first()->total ?? 0;
                })->toArray(),
                'backgroundColor' => $colors[$index % count($colors)],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $schools->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'stacked' => false,
                ],
                'y' => [
                    'stacked' => false,
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'responsive' => true,
            'interaction' => [
                'intersect' => false,
            ],
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SchoolChart::class,
        ];
    }
}
