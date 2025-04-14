<?php

namespace App\Filament\Resources\LoansResource\Pages;

use Filament\Resources\Pages\Page;
use Filament\Actions;
use Filament\Facades\Filament;
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('go_to_list')
                ->label('Go to List ')
                ->url(fn () => route('filament.admin.resources.loans.list', ['tenant' => Filament::getTenant()]))
                ->visible(fn () => Filament::auth()->user()->hasRole('Super Admin')),
        ];
    }
}
