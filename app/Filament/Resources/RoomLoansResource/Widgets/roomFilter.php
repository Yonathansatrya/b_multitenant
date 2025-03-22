<?php

namespace App\Filament\Resources\RoomLoansResource\Widgets;

use Filament\Widgets\Widget;

class RoomFilter extends Widget
{
    protected static string $view = 'filament.resources.room-loans-resource.widgets.room-filter';
    public string $calendarView;

    public function mount()
    {
        $this->calendarView = session('calendarView', 'dayGridMonth');
    }

    public function applyFilter()
    {
        session(['calendarView' => $this->calendarView]);
        $this->dispatch('ChangeCalendarView', $this->calendarView);
    }
}
