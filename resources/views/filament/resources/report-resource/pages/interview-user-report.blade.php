<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Interview User Report</h2>
            <p class="text-sm text-gray-600 mb-6">
                Daftar kandidat yang sudah masuk tahap Interview User untuk job vacancy ini.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
