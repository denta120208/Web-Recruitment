<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return $this->exportToExcel();
                }),
                
            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    return $this->exportToPdf();
                }),
        ];
    }
    
    protected function exportToExcel()
    {
        return redirect()->route('reports.export.excel');
    }
    
    protected function exportToPdf()
    {
        return redirect()->route('reports.export.pdf');
    }
    
    public function getTitle(): string
    {
        return 'Recruitment Reports';
    }
}
