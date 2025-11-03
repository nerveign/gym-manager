<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />

            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                <x-nav-item text="Home" color="text-gray-600" src="home.svg" location="customer.dashboard" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Aktivitas Saya</div>
                <x-nav-item text="Progress Tracking" color="text-gray-600" src="barbell.svg" location="customer.progress.index" />
                <x-nav-item text="My Bookings" color="text-gray-600" src="calendar.svg" location="customer.bookings.index" />

                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Info Gym</div>
                <x-nav-item text="Trainer List" color="text-gray-600" src="user.svg" location="customer.trainers.index" />
                <x-nav-item text="Equipment List" color="text-gray-600" src="equipment.svg" location="customer.equipments.index" />
            </nav>

            {{-- User Profile Section --}}
            <div class="absolute bottom-0 w-64 p-4 flex justify-between bg-white border-t">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $user->image_url ?? asset('images/default-user.png') }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Customer</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 text-gray-500">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 ml-64">
            <main class="pt-6 pb-8 px-6 h-screen overflow-y-auto flex flex-col justify-center">
                {{-- Header --}}
                <div class="mb-6 text-center">
                    <div class="mb-4">
                        <h2 class="text-2xl font-semibold text-gray-900">Aktivasi Membership</h2>
                    </div>
                    <p class="text-gray-600">Complete your payment to activate your gym membership</p>
                </div>

                {{-- Payment Content --}}
                <div class="max-w-4xl mx-auto w-full">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                        {{-- Payment Details --}}
                        <div class="bg-white rounded-lg shadow-sm border p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Membership Details</h3>
                            
                            <div class="space-y-4 flex-grow">
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="text-gray-600">Package</span>
                                    <span class="font-medium">Basic Membership</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="text-gray-600">Duration</span>
                                    <span class="font-medium">{{ $duration }} Days</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="text-gray-600">Access</span>
                                    <span class="font-medium">Full Gym Access</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-blue-50 px-4 rounded-lg mt-4">
                                    <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-yellow-600 mt-1 mr-2"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium">Important:</p>
                                        <p>Your membership will be activated immediately after successful payment and will be valid for 30 days from the payment date.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Form --}}
                        <div class="bg-white rounded-lg shadow-sm border p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                            
                            {{-- Status Notifications --}}
                            @if(request('status') === 'pending')
                                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                                        <span class="text-yellow-800">Pembayaran sedang diproses. Silakan tunggu konfirmasi dari bank.</span>
                                    </div>
                                </div>
                            @elseif(request('status') === 'failed')
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                        <span class="text-red-800">Pembayaran gagal. Silakan coba lagi dengan metode pembayaran yang berbeda.</span>
                                    </div>
                                </div>
                            @elseif(request('status') === 'unconfirmed')
                                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                        <span class="text-blue-800">Pembayaran belum dapat dikonfirmasi. Sistem akan memverifikasi pembayaran Anda secara otomatis.</span>
                                    </div>
                                </div>
                            @elseif(request('status') === 'error')
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                        <span class="text-red-800">Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                        <span class="text-red-800">{{ session('error') }}</span>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('customer.payment.process') }}" method="POST" id="paymentForm" class="flex flex-col h-full">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $amount }}">
                                
                                {{-- Payment Method Selection --}}
                                <div class="mb-6 flex-grow">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Method</label>
                                    <div class="space-y-3">
                                        {{-- Midtrans Payment --}}
                                        <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" name="payment_method" value="midtrans" class="mr-3" required>
                                            <div class="flex items-center">
                                                <i class="fas fa-credit-card text-green-600 text-xl mr-3"></i>
                                                <div>
                                                    <div class="font-medium">Midtrans Payment Gateway</div>
                                                    <div class="text-sm text-gray-500">Credit Card, Bank Transfer, E-Wallet, QRIS</div>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        {{-- Manual Payment (Testing) --}}
                                        <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" name="payment_method" value="manual" class="mr-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-cog text-yellow-600 text-xl mr-3"></i>
                                                <div>
                                                    <div class="font-medium">Manual Payment (Testing)</div>
                                                    <div class="text-sm text-gray-500">For testing - instant activation</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Submit Button - Always at bottom --}}
                                <div class="mt-auto">
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-lock mr-2"></i>
                                        Proceed to Payment
                                    </button>
                                    
                                    <div class="text-center mt-4">
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-shield-alt mr-1"></i>
                                            Your payment is secured with 256-bit SSL encryption
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
                                    <i class="fas fa-info-circle text-yellow-600 mt-1 mr-2"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium">Important:</p>
                                        <p>Your membership will be activated immediately after successful payment and will be valid for 30 days from the payment date.</p>
                                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                e.preventDefault();
                alert('Please select a payment method');
                return false;
            }
        });
    </script>
</body>
</html>
