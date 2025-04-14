<x-filament-widgets::widget>
    <x-filament::card>
        <form wire:submit.prevent="applyFilter">
            <div>
                <label for="calendarView" class="block text-sm font-medium text-gray-700">Tampilan Kalender</label>
                <select id="calendarView" wire:model="calendarView" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="dayGridMonth">Bulanan</option>
                    <option value="timeGridWeek">Mingguan</option>
                    <option value="timeGridDay">Harian</option>
                </select>
            </div>

            <x-filament::button type="submit" onclick="document.location.reload()" class="mt-2 w-full">
                Terapkan Filter
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament-widgets::widget>
