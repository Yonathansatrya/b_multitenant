<?php

namespace App\Filament\Resources\RoomLoansResource\Widgets;

use Carbon\Carbon;
use App\Models\RoomLoans;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Widgets\CalendarWidget;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class RoomWidget extends CalendarWidget
{
    public string $calendarView = 'dayGridMonth';

    protected $listeners = ['changeCalendarView' => 'updateCalendarView', 'refreshCalendar' => '$refresh'];

    public function mount()
    {
        $this->calendarView = session('calendarView', 'dayGridMonth');
    }

    #[On('changeCalendarView')]
    public function updateCalendarView($state)
    {
        $this->calendarView = $state;
        session(['calendarView' => $state]);
        session()->save();

        $this->refreshRecords();
    }

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        $startDate = Carbon::parse($fetchInfo['start'] ?? now()->startOfMonth());
        $endDate = Carbon::parse($fetchInfo['end'] ?? now()->endOfMonth());

        if ($this->calendarView === 'timeGridWeek') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($this->calendarView === 'timeGridDay') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        }

        return RoomLoans::query()
            ->where('loan_status', 'Approve')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->with('roomLoanDetails.room:id,room_name')
            ->select(['id', 'start_date', 'end_date'])
            ->get()
            ->flatMap(fn($loan) => $loan->roomLoanDetails->map(
                fn($detail) =>
                Event::make()
                    ->title($detail->room->room_name)
                    ->start(Carbon::parse($loan->start_date)->format('Y-m-d'))
                    ->end(Carbon::parse($loan->end_date)->format('Y-m-d'))
                    ->allDay(true)
            ))->toArray();
    }
}
