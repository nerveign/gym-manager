<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Midtrans - Gym Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        {{-- Fixed Sidebar --}}
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ auth()->user()->name }}" />

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
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ auth()->user()->image_url ?? asset('images/default-user.png') }}" alt="{{ auth()->user()->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
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

        <!-- Main Content -->
        <div class="ml-64 flex-1 overflow-y-auto">
            <div class="p-8">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-center mb-6">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Membership</h1>
                            <p class="text-gray-600">Silakan lakukan pembayaran melalui Midtrans</p>
                        </div>
                        
                        <div class="text-center">
                            <button id="pay-button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg">
                                Bayar Sekarang
                            </button>
                            
                            <div class="mt-4">
                                <a href="{{ route('customer.payment.show') }}" class="text-gray-600 hover:text-gray-800">
                                    ← Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Midtrans -->
    <script>
        // Debug client key
        console.log('Midtrans Client Key loaded:', '{{ config("services.midtrans.client_key") }}');
        
        document.getElementById('pay-button').onclick = function(){
            console.log('Payment button clicked');
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    console.log('Payment success:', result);
                    
                    // Show loading state instead of alert
                    showLoadingState('Memverifikasi pembayaran...');
                    
                    // Verify payment status with server before redirect
                    verifyPaymentAndRedirect(result.order_id, '{{ $transaction->id }}');
                },
                onPending: function(result){
                    console.log('Payment pending:', result);
                    // Removed alert - will handle quietly
                    
                    // Redirect back to payment page for pending status
                    window.location.href = '{{ route("customer.payment.show") }}?status=pending&order_id=' + result.order_id;
                },
                onError: function(result){
                    console.log('Payment error:', result);
                    // Redirect to payment page with error status - no alert
                    window.location.href = '{{ route("customer.payment.show") }}?status=failed';
                },
                onClose: function(){
                    console.log('Payment popup closed');
                    // Stay on current page - no alert needed
                    // User deliberately closed the payment popup
                }
            });
        };
        
        // Function to show loading state
        function showLoadingState(message) {
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'payment-loading';
            loadingDiv.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; padding: 2rem; border-radius: 8px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                        <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                        <p style="margin: 0; color: #333; font-size: 16px;">${message}</p>
                    </div>
                </div>
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>
            `;
            document.body.appendChild(loadingDiv);
        }
        
        // Function to hide loading state
        function hideLoadingState() {
            const loadingDiv = document.getElementById('payment-loading');
            if (loadingDiv) {
                loadingDiv.remove();
            }
        }

        // Function to verify payment status with server
        function verifyPaymentAndRedirect(orderId, transactionId) {
            console.log('Verifying payment with server...', {
                orderId: orderId,
                transactionId: transactionId
            });
            
            // Make AJAX request to verify payment status
            fetch('{{ route("customer.payment.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    transaction_id: transactionId
                })
            })
            .then(response => {
                console.log('Verify response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Verify response data:', data);
                
                if (data.success && data.payment_verified) {
                    // Payment actually verified, redirect to success
                    console.log('Payment verified, redirecting to success page...');
                    window.location.href = '{{ route("customer.payment.success") }}?transaction_id=' + transactionId + '&order_id=' + orderId;
                } else {
                    // Payment not verified yet, redirect back quietly
                    console.log('Payment not verified yet:', data.message);
                    hideLoadingState();
                    window.location.href = '{{ route("customer.payment.show") }}?status=unconfirmed&order_id=' + orderId;
                }
            })
            .catch(error => {
                console.error('Verification error:', error);
                hideLoadingState();
                // Redirect to payment page with error status - no alert
                window.location.href = '{{ route("customer.payment.show") }}?status=error';
            });
        }
    </script>
</body>
</html>