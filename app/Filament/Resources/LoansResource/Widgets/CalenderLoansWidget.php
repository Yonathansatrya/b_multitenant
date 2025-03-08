<?php

namespace App\Filament\Resources\LoansResource\Widgets;

use App\Models\Loan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;
use Guava\Calendar\Actions\CreateAction;
use App\Filament\Resources\LoansResource;
use Guava\Calendar\Widgets\CalendarWidget;

class CalenderLoansWidget extends CalendarWidget
{
    protected static string $resource = LoansResource::class;
    protected string $calendarView = 'dayGridMonth';
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled = true;
    public ?array $selectedLoan = null;

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        return Loan::with('organization.organization_loan.organization')->get()->map(function ($loan) {

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
                ->backgroundColor($color)
                ->extendedProps([
                    "user" => $loan->user->name,
                    "organization" => optional($loan->organization?->organization_loan?->organization)->name ?? 'Tidak ada organisasi',
                    "loan_date" => $loan->loan_date,
                    "loan_end_date" => $loan->loan_end_date,
                    "status" => $loan->status,
                    "deskripsi" => $loan->description,
                    "items" => $loan->loanItems->map(fn($li) => [
                        "name" => $li->item->name,
                        "quantity" => $li->quantity,
                    ])->toArray()
                ]);
        })->toArray();
    }

    public function onEventClick(array $info = [], ?string $action = null): void
    {
        if (!isset($info['event'])) {
            return;
        }
        
        $this->dispatch('open-modal', id: 'view-detail', data: $info['event']['extendedProps']);
    }

    // public function onEventClick(array $info = []): void
    // {
    //     do something on click
    //     $info contains the event data:
    //     $info['event'];
    //     $info['view'] - the view object

    //     $this->dispatch('open-modal', id: 'edit-user');

    // \Log::info('Dispatch open-modal dengan data:', ['data' => $info['event']['extendedProps'] ?? []]);
    // $this->dispatch('open-modal', [
    //     'id' => 'view-detail',
    //     'data' => $info['event']['extendedProps'],
    // ]);
    // }


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
