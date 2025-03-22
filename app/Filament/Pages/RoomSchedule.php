<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\RoomLoansResource\Widgets\RoomFilter;
use App\Filament\Resources\RoomLoansResource\Widgets\RoomWidget;
use Livewire\Livewire;

use Livewire\Attributes\On;

class RoomSchedule extends Page
{
    protected static ?string $navigationLabel = 'Jadwal Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
    protected static ?string $slug = 'room-schedule';
    protected static ?string $title = 'Jadwal Ruangan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.room-schedule';
    public $record;

    protected $listeners = [
        'updatedCalendarView' => 'updatedCalendarView',
        'refresh-room-widget' => 'refreshCalendar',
    ];

    #[On('updatedCalendarView')]
    public function updatedCalendarView($newView)
    {
        $this->record = $newView;
        $this->dispatch('handleupdatedCalendarView', $this->record);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoomFilter::class,
            RoomWidget::class,
        ];
    }
}
