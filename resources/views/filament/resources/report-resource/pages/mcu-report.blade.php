<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">MCU Report</h2>
            <p class="text-sm text-gray-600 mb-6">
                Daftar kandidat yang sudah masuk tahap MCU (Medical Check Up) untuk job vacancy ini. Klik "View MCU" untuk melihat hasil MCU.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
