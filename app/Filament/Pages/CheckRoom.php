<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Components\Grid;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;

class CheckRoom extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Ketersediaan Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
    protected static ?string $title = 'Ketersediaan Ruangan';
    protected static ?string $slug = 'check-room-availability';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.check-room';

    public $startDate;
    public $endDate;

    public function checkAvailability()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);
    }

    protected function getTableQuery()
    {
        $user = auth()->user();
        $organizationId = $user->organizations()->first()->id ?? null;

        return Room::query()
            ->where('organization_id', $organizationId)
            ->select('rooms.*')
            ->selectRaw("
            CASE
                WHEN rooms.status = 'Inactive' THEN 'Tidak Tersedia'
                WHEN EXISTS (
                    SELECT 1 FROM room_loan_details rld
                    JOIN room_loans rl ON rld.room_loan_id = rl.id
                    WHERE rld.room_id = rooms.id
                    AND rl.loan_status = 'Approve'
                    AND (
                        (rl.start_date BETWEEN ? AND ?)
                        OR (rl.end_date BETWEEN ? AND ?)
                    )
                )
                THEN 'Tidak Tersedia'
                ELSE 'Tersedia'
            END AS status
        ", [$this->startDate, $this->endDate, $this->startDate, $this->endDate]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->label('No'),
            TextColumn::make('room_name')->label('Nama Ruangan'),
            TextColumn::make('status')
                ->label('Status Ruangan')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Tersedia' => 'success',
                    'Tidak Tersedia' => 'danger',
                }),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                DatePicker::make('startDate')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live(),
                DatePicker::make('endDate')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->live(),
            ]),
        ]);
    }

}
