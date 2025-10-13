<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ApplicantStatsWidget extends ChartWidget
{
    protected ?string $heading = 'Applicant Stats Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
