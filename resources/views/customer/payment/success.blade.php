<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | FitAja</title>
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
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 mr-1">
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 ml-64">
            <main class="pt-6 pb-8 px-6 h-screen overflow-y-auto">
                <div class="max-w-2xl mx-auto">
                    {{-- Success Message --}}
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <i class="fas fa-check text-green-600 text-2xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
                        <p class="text-gray-600">Your membership has been activated successfully</p>
                    </div>

                    {{-- Membership Details Card --}}
                    @if($membership)
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-id-card text-green-600 mr-2"></i>
                            Membership Details
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500">Status</span>
                                    <p class="font-semibold text-green-600">{{ ucfirst($membership->status) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Start Date</span>
                                    <p class="font-medium">{{ $membership->start_time ? $membership->start_time->format('d M Y, H:i') : 'Today' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">End Date</span>
                                    <p class="font-medium">{{ $membership->end_time ? $membership->end_time->format('d M Y, H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500">Duration</span>
                                    <p class="font-medium">30 Days</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Amount Paid</span>
                                    <p class="font-medium">Rp {{ number_format($membership->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Payment Status</span>
                                    <p class="font-semibold text-blue-600">{{ ucfirst($membership->payment_status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Transaction Details Card --}}
                    @if($transaction)
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-receipt text-blue-600 mr-2"></i>
                            Transaction Details
                        </h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-mono text-sm">{{ $transaction->payment_gateway_id ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium">{{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date</span>
                                <span class="font-medium">{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, H:i') : $transaction->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Next Steps --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">
                            <i class="fas fa-lightbulb mr-2"></i>
                            What's Next?
                        </h3>
                        <ul class="space-y-2 text-blue-800">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                Your membership is now active and ready to use
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-calendar-plus text-blue-600 mr-2"></i>
                                Book your training sessions with our professional trainers
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-dumbbell text-blue-600 mr-2"></i>
                                Track your fitness progress and achieve your goals
                            </li>
                        </ul>
                    </div>

                    {{-- Manual Activation Button (if status still pending) --}}
                    @if($transaction && $transaction->status === 'pending')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Membership Not Yet Active
                        </h3>
                        <p class="text-yellow-800 mb-4">
                            Your payment was successful, but your membership is still pending activation. Click the button below to activate it now.
                        </p>
                        <form method="POST" action="{{ route('customer.payment.activate') }}">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                            <button type="submit" class="bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-play mr-2"></i>
                                Activate Membership Now
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('customer.dashboard', ['from_success' => 1]) }}" 
                           class="flex-1 bg-blue-600 text-white text-center font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Go to Dashboard
                        </a>
                        <a href="{{ route('customer.bookings.create') }}" 
                           class="flex-1 bg-green-600 text-white text-center font-semibold py-3 px-6 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Book First Session
                        </a>
                    </div>

                    {{-- Support Note --}}
                    <div class="text-center mt-8 text-gray-500">
                        <p class="text-sm">
                            Need help? Contact our support team at 
                            <a href="mailto:support@fitaja.com" class="text-blue-600 hover:text-blue-800">support@fitaja.com</a>
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Debug: Log page access
        console.log('Success page loaded');
        console.log('URL:', window.location.href);
        console.log('URL Search Params:', window.location.search);
        
        // Check session storage for payment data
        var storedTransactionId = sessionStorage.getItem('payment_transaction_id');
        var storedResult = sessionStorage.getItem('payment_result');
        
        if (storedTransactionId) {
            console.log('Stored Transaction ID:', storedTransactionId);
        }
        
        if (storedResult) {
            console.log('Stored Payment Result:', JSON.parse(storedResult));
        }
        
        // Get URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        var transactionId = urlParams.get('transaction_id');
        var orderId = urlParams.get('order_id');
        
        console.log('URL Transaction ID:', transactionId);
        console.log('URL Order ID:', orderId);
    </script>
</body>
</html>
