<?php

namespace App\Filament\Resources\RoomLoansResource\Widgets;

use Carbon\Carbon;
use App\Models\RoomLoans;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Widgets\CalendarWidget;
use Livewire\Attributes\On;

class RoomWidget extends CalendarWidget
{
    public string $calendarView;

    protected $listeners = [
        'RefreshCalendarView' => 'refreshCalendarView',
    ];

    public function mount()
    {
        $this->calendarView = session('calendarView', 'dayGridMonth');
    }

    #[On('ChangeCalendarView')]
    public function refreshCalendarView($calendarView)
    {
        session(['calendarView' => $calendarView]);
        $this->calendarView = $calendarView;
        $this->dispatch('$refresh');
    }

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        return RoomLoans::query()
            ->where('loan_status', 'Approve')
            ->with('roomLoanDetails.room:id,room_name')
            ->select(['id', 'start_date', 'end_date'])
            ->get()
            ->flatMap(fn($loan) => $loan->roomLoanDetails->map(
                fn($detail) =>
                Event::make()
                    ->title($detail->room->room_name)
                    ->start(Carbon::parse($loan->start_date)->format('Y-m-d'))
                    ->end(Carbon::parse($loan->end_date)->format('Y-m-d'))
                    ->allDay(true),
            ))->toArray();
    }
}
