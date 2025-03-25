<x-filament-panels::page>
    <x-filament::tabs>
        <div class="p-6 py-5 px-7 text-center">
            <h2 class="text-lg font-bold">Keluar dari Organisasi</h2>
            <p class="mt-2">Anda akan keluar dari organisasi ini.</p>
            <p class="text-red-500">Data akses ke organisasi ini akan dihapus.</p>

            <x-filament::modal>
                <x-slot name="trigger">
                    <x-filament::button class="mt-6 px-3 py-3">
                        Keluar Dari Organisasi
                    </x-filament::button>
                </x-slot>
                
                <x-slot name="title">
                    Konfirmasi Keluar
                </x-slot>

                <p class="text-gray-700">
                    Apakah Anda yakin ingin keluar dari organisasi ini? <br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <x-filament::button wire:click="logoutWorkSpace" color="danger" class="mt-6 px-4 py-2">
                    Keluar dari Organisasi
                </x-filament::button>
                {{-- <x-filament::button color="gray" >
                    Cancel
                </x-filament::button> --}}
            </x-filament::modal>
        </div>
    </x-filament::tabs>
</x-filament-panels::page>
