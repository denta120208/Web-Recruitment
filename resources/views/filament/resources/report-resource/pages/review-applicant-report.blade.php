<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Applicant Report</h2>
            <p class="text-sm text-gray-600 mb-4">
                Daftar kandidat yang sudah masuk tahap Review Applicant untuk job vacancy ini. 
                Klik nama kandidat untuk melihat detail profil mereka.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
