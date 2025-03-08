<x-filament-panels::page>
    <x-filament::modal id="view-detail" class="p-6" width="3xl">
        <x-slot name="heading">
            <h2 class="text-2xl font-semibold">Deskripsi Peminjaman Barang</h2>
        </x-slot>

        <x-slot name="description">
            <div
                x-data="{ loan: {} }"
                x-init="window.addEventListener('open-modal', event => {
                    if (event.detail.id === 'view-detail') {
                        loan = event.detail.data;
                    }
                });"
                class="p-6 rounded-lg shadow-md"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Nama Peminjam:</strong> <span x-text="loan.user ?? 'Tidak ada data'"></span></p>
                    <p><strong>Dari Organisasi:</strong> <span x-text="loan.organization ?? 'Tidak ada organisasi'"></span></p>
                    <p><strong>Tanggal Pinjam:</strong> <span x-text="loan.loan_date ?? '-'"></span></p>
                    <p><strong>Tanggal Kembali:</strong> <span x-text="loan.loan_end_date ?? '-'"></span></p>
                    <p><strong>Status:</strong> <span x-text="loan.status ?? '-'"></span></p>
                    <p class="col-span-2"><strong>Deskripsi:</strong> <span x-text="loan.deskripsi ?? '-'"></span></p>
                </div>

                <h3 class="mt-6 text-lg font-semibold">Daftar Barang</h3>
                <div class="overflow-x-auto mt-2">
                    <table class="w-full border rounded-lg shadow-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="border p-3">Nama Barang</th>
                                <th class="border p-3">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in loan.items ?? []" :key="item.name">
                                <tr class="odd:bg-gray-100 even:bg-white dark:odd:bg-gray-700 dark:even:bg-gray-800">
                                    <td class="border p-3" x-text="item.name"></td>
                                    <td class="border p-3" x-text="item.quantity"></td>
                                </tr>
                            </template>
                            <template x-if="!loan.items || loan.items.length === 0">
                                <tr>
                                    <td colspan="2" class="border p-3 text-center">Tidak ada barang</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>
