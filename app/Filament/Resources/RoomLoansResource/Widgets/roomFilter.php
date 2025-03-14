<?php

namespace App\Filament\Resources\RoomLoansResource\Widgets;

use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class RoomFilter extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.resources.room-loans-resource.widgets.room-filter';

    public ?string $calendarView = null;

    public function mount(): void
    {
        $this->form->fill([
            'calendarView' => session('calendarView', 'dayGridMonth'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('calendarView')
                ->label('Tampilan Kalender')
                ->columnSpanFull()
                ->options([
                    'dayGridMonth' => 'Bulanan',
                    'timeGridWeek' => 'Mingguan',
                    'timeGridDay' => 'Harian',
                ])
                ->default(session('calendarView', 'dayGridMonth'))
                ->reactive()
                ->live()
                ->afterStateUpdated(fn($state) => $this->updateCalendarView($state)),
        ]);
    }

    public function updateCalendarView($state)
    {
        session(['calendarView' => $state]);
        session()->save();

        $this->dispatch('changeCalendarView', $state);
    }
}
