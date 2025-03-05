<?php

namespace App\Filament\Resources\LoansResource\Widgets;

use App\Models\Loan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Widgets\CalendarWidget;

class CalenderLoansWidget extends CalendarWidget
{
    protected static string $view = 'filament.resources.loans-resource.widgets.calender-loans-widget';
    protected string $calendarView = 'resourceTimeGridWeek';

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return Loan::all()->map(function ($loan) {
            return Event::make()
                ->title("Peminjaman oleh {$loan->user->name}")
                ->start(Carbon::parse($loan->loan_date))
                ->end(Carbon::parse($loan->loan_end_date))
                ->data([
                    'description' => $loan->description,
                    'status' => $loan->status,
                    'organization' => $loan->organizationLoan->name ?? 'Tidak Ada Organisasi',
                ]);
        })->toArray();
    }
}
