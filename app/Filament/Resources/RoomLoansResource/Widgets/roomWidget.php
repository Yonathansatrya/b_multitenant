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

    protected $listeners = [
        'handleupdatedCalendarView' => 'handleUpdatedCalendarView',
        'refresh-room-widget' => 'refreshCalendar',
    ];

    public function mount()
    {
        $this->calendarView = session('calendarView', 'dayGridMonth');
        $this->getEvents();
        // $this->dispatch('$refresh');
        // dd($this->calendarView);
        $this->refreshRecords();
    }

    #[On('updatedCalendarView')]
    public function handleupdatedCalendarView($newView)
    {
        $this->calendarView = $newView;
        session(['calendarView' => $newView]);
        $this->mount();
        // dd($this->calendarView);
        // $this->dispatch('$refresh');
        // $this->refreshRecords();
        $this->refreshResources();
        $this->dispatch('refreshWidget');
    }

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        $startDate = Carbon::parse($fetchInfo['start'] ?? now()->startOfMonth());
        $endDate = Carbon::parse($fetchInfo['end'] ?? now()->endOfMonth());

        // \Log::info('calendar yang di terima di event adalah:', ['view' => $this->calendarView]);=
        // dd($this->calendarView);
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
                    ->allDay(true),
            ))->toArray();
    }
}
