<?php

namespace App\Filament\Resources\RoomLoansResource\Pages;

use App\Filament\Resources\RoomLoansResource;
use App\Filament\Resources\RoomLoansResource\Widgets\roomWidget;
use Filament\Resources\Pages\Page;

class CalendarRoomLoans extends Page
{
    protected static string $resource = RoomLoansResource::class;

    protected static string $view = 'filament.resources.room-loans-resource.pages.calendar-room-loans';


    protected function getHeaderWidgets(): array
    {
        return [
            roomWidget::class,
        ];
    }
}
