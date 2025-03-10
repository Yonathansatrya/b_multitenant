<?php

namespace App\Filament\Resources\LoansResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use App\Filament\Resources\LoansResource;
use Filament\Resources\Pages\ListRecords;

class ListLoans extends ListRecords
{
    protected static string $resource = LoansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('go_back_calender')
                // ->color('')
                ->label('Calendar')
                ->url(fn() => route('filament.admin.resources.loans.index', ['tenant' => Filament::getTenant()])),

        ];
    }
}
