<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\RoomLoansResource\Widgets\RoomFilter;
use App\Filament\Resources\RoomLoansResource\Widgets\RoomWidget;
use Livewire\Attributes\On;

class RoomSchedule extends Page
{
    protected static ?string $navigationLabel = 'Jadwal Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
    protected static ?string $slug = 'room-schedule';
    protected static ?string $title = 'Jadwal Ruangan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.room-schedule';

    protected $listeners = [
        'ChangeCalendarView' => 'updateCalendarView',
    ];

    #[On('ChangeCalendarView')]
    public function updateCalendarView($calendarView)
    {
        session(['calendarView' => $calendarView]);
        $this->dispatch('RefreshCalendarView');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoomFilter::class,
            RoomWidget::class,
        ];
    }
}
