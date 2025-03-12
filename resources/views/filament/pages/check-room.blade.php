<x-filament-panels::page>
    <div class="p-4 space-y-6">
        <form wire:submit.prevent="checkAvailability" class="space-y-4 bg-white shadow-sm p-6 rounded-lg">
            {{ $this->form }}

            <div class="flex justify-end drop-shadow-xl ">
                <x-filament::button type="submit">
                    Cek Ruangan
                </x-filament::button>
            </div>
        </form>

        @if ($this->startDate && $this->endDate)
            <div class="bg-white shadow-sm p-6 rounded-lg">
                {{ $this->table }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
