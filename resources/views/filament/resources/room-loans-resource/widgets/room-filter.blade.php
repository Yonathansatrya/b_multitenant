<x-filament-widgets::widget>
    <x-filament::section>
        <div>
            <form wire:submit.prevent="submit">
                {{ $this->form }}
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
