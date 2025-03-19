<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\RoomLoansResource\Widgets\RoomFilter;
use App\Filament\Resources\RoomLoansResource\Widgets\RoomWidget;

class RoomSchedule extends Page
{
    protected static ?string $navigationLabel = 'Jadwal Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
    protected static ?string $slug = 'room-schedule';
    protected static ?string $title = 'Jadwal Ruangan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.room-schedule';

    protected function getHeaderWidgets(): array
    {
        return [
            RoomFilter::class,
            RoomWidget::class,
        ];
    }
}
