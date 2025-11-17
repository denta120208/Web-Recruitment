<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">All Applicants Report</h2>
            <p class="text-sm text-gray-600 mb-4">
                Daftar semua kandidat yang melamar untuk job vacancy ini dengan status masing-masing. 
                Klik nama kandidat untuk melihat detail profil mereka.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
