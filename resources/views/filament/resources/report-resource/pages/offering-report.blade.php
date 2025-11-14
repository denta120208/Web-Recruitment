<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Offering Letter Report</h2>
            <p class="text-sm text-gray-600 mb-6">
                Daftar kandidat yang sudah masuk tahap Offering Letter untuk job vacancy ini. Klik "View PDF" untuk melihat offering letter.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
