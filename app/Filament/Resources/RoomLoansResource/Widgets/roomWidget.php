<?php

namespace App\Filament\Resources\RoomLoansResource\Widgets;

use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;

class roomWidget extends CalendarWidget
{
    protected static string $view = 'filament.resources.room-loans-resource.widgets.room-widget';
    protected string $calendarView = 'dayGridMonth';

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return [
            //
        ];
    }
}
