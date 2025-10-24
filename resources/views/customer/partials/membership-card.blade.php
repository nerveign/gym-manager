@php
    use Carbon\Carbon;

    $user = auth()->user();

    // Jika bukan customer, jangan tampilkan apa-apa
    if ($user->role !== 'customer') {
        return;
    }
@endphp

{{-- Kartu Membership Status --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        @if(isset($activeMembership) && $activeMembership)
            @php
                // Parsing tanggal menggunakan Carbon
                $startTime = Carbon::parse($activeMembership->start_time);
                $endTime = Carbon::parse($activeMembership->end_time);
                
                // Menghitung sisa hari dan membulatkannya ke atas (ceil) agar menjadi bilangan bulat
                $remainingDays = ceil(now()->diffInDays($endTime, false)); 
                
                // Mengatur teks dan kelas CSS berdasarkan sisa hari (Sekarang menggunakan Bahasa Inggris)
                $daysText = '';
                $colorClass = 'text-green-600 bg-green-100';
                $iconClass = 'fas fa-id-card text-green-600';

                if ($remainingDays > 1) {
                    $daysText = $remainingDays . ' Days Remaining'; // Jamak: 30 Days Remaining
                } elseif ($remainingDays == 1) {
                    $daysText = '1 Day Remaining'; // Tunggal
                } elseif ($remainingDays == 0) {
                    $daysText = 'Expires Today'; // Hari ini
                } else {
                     // Jika sudah kedaluwarsa (misalnya -1 hari)
                    $daysText = 'Recently Expired';
                    $colorClass = 'text-red-600 bg-red-100';
                    $iconClass = 'fas fa-exclamation-triangle text-red-600';
                }
                
                if ($remainingDays <= 7 && $remainingDays > 0) {
                    $colorClass = 'text-yellow-600 bg-yellow-100';
                } elseif ($remainingDays <= 0) {
                    $colorClass = 'text-red-600 bg-red-100';
                }
            @endphp

            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Membership Status</p>
                    <p class="text-3xl font-bold text-green-600">
                        Active
                    </p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $colorClass }}">
                        <i class="fas fa-calendar-alt mr-1"></i> {{ $daysText }}
                    </span>
                </div>
                <div class="size-12 flex items-center justify-center bg-green-50 rounded-lg">
                    <i class="{{ $iconClass }} text-2xl"></i>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-100 text-sm space-y-1">
                <div class="flex justify-between">
                    <span class="text-gray-500">Starts:</span>
                    <span class="font-medium text-gray-800">{{ $startTime->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Ends:</span>
                    <span class="font-medium text-gray-800">{{ $endTime->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Status:</span>
                    <span class="font-semibold text-blue-600">{{ ucfirst($activeMembership->payment_status) }}</span>
                </div>
            </div>

        @else
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Membership Status</p>
                    <p class="text-3xl font-bold text-red-600">Inactive</p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Please renew your membership now.
                    </p>
                </div>
                <div class="size-12 flex items-center justify-center bg-red-50 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
            </div>
        @endif
    </div>
</div>