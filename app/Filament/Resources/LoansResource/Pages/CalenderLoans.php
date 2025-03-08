<?php

namespace App\Filament\Resources\LoansResource\Pages;

use Filament\Resources\Pages\Page;
use Filament\Actions;
use App\Filament\Resources\LoansResource;
use App\Filament\Resources\LoansResource\Widgets\CalenderLoansWidget;

class CalenderLoans extends Page
{
    protected static string $resource = LoansResource::class;
    protected static string $view = 'filament.resources.loans-resource.pages.calender-loans';

    protected function getHeaderWidgets(): array
    {
        return [
            CalenderLoansWidget::class,
        ];
    }
}
