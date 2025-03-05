<?php

namespace App\Filament\Widgets;

use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;

class CalenderLoan extends CalendarWidget
{
    // protected static string $view = 'filament.widgets.calender-loan';
    protected string $calendarView = 'dayGridMonth';

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return [
            // Chainable object-oriented variant
            Event::make()
                ->title('My first event')
                ->start(today())
                ->end(today()),

            // Array variant
            ['title' => 'My second event', 'start' => today()->addDays(3), 'end' => today()->addDays(3)],

            // Eloquent model implementing the `Eventable` interface
            // MyEvent::find(1),
        ];
    }
}
