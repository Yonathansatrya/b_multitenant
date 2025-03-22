<?php

namespace App\Filament\Resources\RoomLoansResource\Widgets;

use Filament\Widgets\Widget;
use Livewire\Component;
class RoomFilter extends Widget
{
    protected static string $view = 'filament.resources.room-loans-resource.widgets.room-filter';

    public ?string $calendarView = 'dayGridMonth';

    public function applyFilter()
    {
        $this->dispatch('updatedCalendarView', $this->calendarView);
    }
}
