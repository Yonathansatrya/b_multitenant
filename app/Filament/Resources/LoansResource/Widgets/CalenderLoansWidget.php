<?php

namespace App\Filament\Resources\LoansResource\Widgets;

use App\Models\Loan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;
use Filament\Notifications\Notification;
use Guava\Calendar\Actions\CreateAction;
use App\Filament\Resources\LoansResource;
use Guava\Calendar\Widgets\CalendarWidget;

class CalenderLoansWidget extends CalendarWidget
{
    protected static string $resource = LoansResource::class;
    protected string $calendarView = 'dayGridMonth';
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled = true;

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        return Loan::all()->map(function ($loan) {

            $totalitem = $loan->loanItems->sum('quantity');

            $color = match ($loan->status) {
                'returned' => 'green',
                'loans' => 'red',
                default => 'blue',
            };

            return Event::make()
                ->title("Peminjaman oleh {$loan->user->name} ({$totalitem} barang)")
                ->start(Carbon::parse($loan->loan_date))
                ->end(Carbon::parse($loan->loan_end_date))
                ->backgroundColor($color);
        })->toArray();
    }

    public function onEventClick(array $info = [], ?string $action = null): void
    {
        $eventId = $info['event']['extendedProps']['id'] ?? null;

        $loan = Loan::find($eventId);
        $this->dispatch('open-modal', id: 'edit-user');


        // $this->dispatch('open-modal', [
        //     'title' => "Detail Peminjaman",
        //     'content' =>
        //         "<strong>Peminjam:</strong> " . $loan->user->name . "<br>" .
        //         "<strong>Organisasi:</strong> " . ($loan->organizationLoan->name ?? 'Tidak Ada Organisasi') . "<br>" .
        //         "<strong>Deskripsi:</strong> " . $loan->description . "<br>" .
        //         "<strong>Status:</strong> " . $loan->status . "<br>" ,
        // ]);
    }

    // public function getDateClickContextMenuActions(): array
    // {
    //     return [
    //         CreateAction::make('foo')
    //             ->model(Loan::class)
    //             ->mountUsing(fn ($arguments, $form) => $form->fill([
    //                 'starts_at' => data_get($arguments, 'dateStr'),
    //                 'ends_at' => data_get($arguments, 'dateStr'),
    //             ])),
    //     ];
    // }
}
