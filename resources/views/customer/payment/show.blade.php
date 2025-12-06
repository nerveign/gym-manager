<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | FitAja</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                            <div class="payment-container">
                                {{-- Payment Button --}}
                                <div class="mb-6">
                                    <div class="text-center">
                                        <button type="button" 
                                                id="paymentBtn"
                                                onclick="payViaVirtualAccount()"
                                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-4 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <i class="fas fa-university mr-2"></i>
                                            Pay via Virtual Account
                                        </button>
                                        
                                        <div class="text-center mt-4">
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-shield-alt mr-1"></i>
                                                Your payment is secured with 256-bit SSL encryption
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-700">Processing payment...</p>
        </div>
    </div>

    <script>
        // Removed auto-check membership to prevent infinite refresh
        // Manual check only when user clicks payment or check status button
        
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function payViaVirtualAccount() {
            // Test connection first
            if (!navigator.onLine) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Internet Connection',
                    html: `
                        <div class="text-gray-700">
                            <i class="fas fa-wifi text-red-500 text-2xl mb-2"></i>
                            <p>Please check your network connection and try again.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-lg shadow-xl',
                        title: 'text-gray-800 font-semibold',
                        confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                    }
                });
                return;
            }
            
            // Disable button and show loading
            const btn = document.getElementById('paymentBtn');
            btn.disabled = true;
            btn.textContent = 'Processing...';
            
            showLoading();
            
            console.log('=== PAYMENT DEBUG START ===');
            console.log('Starting payment process...');
            console.log('URL:', '{{ route("customer.payment.create-order") }}');
            console.log('CSRF Token:', '{{ csrf_token() }}');
            console.log('Browser User Agent:', navigator.userAgent);
            console.log('Network Status:', navigator.onLine ? 'Online' : 'Offline');
            
            // Add error handling untuk network issues
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
            
            fetch('{{ route("customer.payment.create-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({}),
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                console.log('=== RESPONSE RECEIVED ===');
                console.log('Response status:', response.status);
                console.log('Response statusText:', response.statusText);
                console.log('Response URL:', response.url);
                
                // Check if response is actually JSON
                const contentType = response.headers.get('content-type');
                console.log('Content-Type:', contentType);
                
                if (!response.ok) {
                    // Get the error response body
                    return response.text().then(text => {
                        console.error('=== ERROR RESPONSE BODY ===');
                        console.error('Status:', response.status);
                        console.error('Status Text:', response.statusText);
                        console.error('Response Body:', text);
                        
                        hideLoading();
                        resetButton();
                        
                        // Show user-friendly error message
                        if (response.status === 419) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Session Expired',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-clock text-orange-500 text-2xl mb-2"></i>
                                        <p>Your session has expired. Please refresh the page and try again.</p>
                                    </div>
                                `,
                                confirmButtonText: 'Refresh Page',
                                confirmButtonColor: '#f59e0b',
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else if (response.status === 403) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Access Denied',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-ban text-red-500 text-2xl mb-2"></i>
                                        <p>Please make sure you are logged in as a customer.</p>
                                    </div>
                                `,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545',
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            });
                        } else if (response.status === 404) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Service Not Found',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-search text-red-500 text-2xl mb-2"></i>
                                        <p>Payment service not found. Please contact support.</p>
                                    </div>
                                `,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545',
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            });
                        } else if (response.status >= 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-server text-red-500 text-2xl mb-2"></i>
                                        <p>Please try again later or contact support.</p>
                                    </div>
                                `,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545',
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Payment Failed',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                                        <p><strong>${response.statusText}</strong></p>
                                        <p class="mt-2">Please try again or contact support.</p>
                                    </div>
                                `,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545',
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            });
                        }
                        
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    });
                }
                
                // Check if response is JSON
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Not JSON - maybe redirected to login or error page
                    return response.text().then(text => {
                        console.error('Expected JSON but got:', text);
                        hideLoading();
                        resetButton();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Unexpected Response',
                            html: `
                                <div class="text-gray-700">
                                    <i class="fas fa-question-circle text-orange-500 text-2xl mb-2"></i>
                                    <p>Unexpected response format. You may need to login again.</p>
                                </div>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#f59e0b',
                            background: '#fff',
                            customClass: {
                                popup: 'rounded-lg shadow-xl',
                                title: 'text-gray-800 font-semibold',
                                confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                            }
                        });
                        throw new Error('Invalid response format');
                    });
                }
            })
            .then(data => {
                console.log('=== SUCCESS RESPONSE ===');
                console.log('Create order response:', data);
                
                if (data.success) {
                    // Real payment mode - show payment instructions
                    hideLoading();
                    resetButton();
                    
                    const paymentUrl = `https://payment-dummy.doovera.com/pay/${data.va_number}`;
                    
                    // Show stylish popup instead of alert
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center pt-4">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-check text-3xl text-green-500"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Virtual Account Created!</h2>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 w-full">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-600">VA Number:</span>
                                        <span class="text-sm font-mono font-bold text-blue-600">${data.va_number}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-600">Amount:</span>
                                        <span class="text-sm font-bold text-gray-900">Rp ${data.amount.toLocaleString()}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 text-center mb-4">
                                    Complete your payment at: <br>
                                    <a href="${paymentUrl}" target="_blank" class="text-blue-500 font-medium hover:underline">${paymentUrl}</a>
                                </p>
                                <p class="text-xs text-gray-400 text-center">
                                    After payment, click "Check Payment Status" to verify
                                </p>
                            </div>
                        `,
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: 'Continue to Payment',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl p-0 w-[28rem]',
                            actions: 'flex justify-center w-full px-6 pb-6 mt-4',
                            confirmButton: 'w-full py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg text-sm transition shadow-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(paymentUrl, '_blank');
                        }
                    });
                    
                    // Show payment status check button
                    showPaymentStatusChecker(data.va_number, data.transaction_id);
                    
                } else {
                    resetButton();
                    hideLoading();
                    console.error('Payment creation failed:', data.error);
                    
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center pt-4">
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-times text-3xl text-red-500"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Payment Failed</h2>
                                <p class="text-sm text-gray-600 text-center mb-4">
                                    ${data.error || 'Unknown error occurred'}
                                </p>
                                <p class="text-xs text-gray-400 text-center">
                                    Please try again or contact support
                                </p>
                            </div>
                        `,
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: 'Try Again',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-2xl p-0 w-[24rem]',
                            actions: 'flex justify-center w-full px-6 pb-6 mt-4',
                            confirmButton: 'w-full py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg text-sm transition shadow-sm'
                        }
                    });
                }
            })
            .catch(error => {
                resetButton();
                hideLoading();
                console.error('=== PAYMENT ERROR ===');
                console.error('Error type:', error.constructor.name);
                console.error('Error message:', error.message);
                console.error('Full error:', error);
                
                // Better error message for users
                let errorMessage = '';
                let errorIcon = 'fas fa-exclamation-triangle';
                
                if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Connection error. Please check your internet connection and try again.';
                    errorIcon = 'fas fa-wifi';
                } else if (error.message.includes('500')) {
                    errorMessage = 'Server error. Please refresh the page and try again.';
                    errorIcon = 'fas fa-server';
                } else {
                    errorMessage = 'Payment failed: ' + error.message;
                    errorIcon = 'fas fa-times-circle';
                }
                
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center pt-4">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                <i class="${errorIcon} text-3xl text-red-500"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Connection Error</h2>
                            <p class="text-sm text-gray-600 text-center mb-4">
                                ${errorMessage}
                            </p>
                            <p class="text-xs text-gray-400 text-center">
                                If the problem persists, please contact support
                            </p>
                        </div>
                    `,
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'Try Again',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl p-0 w-[24rem]',
                        actions: 'flex justify-center w-full px-6 pb-6 mt-4',
                        confirmButton: 'w-full py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg text-sm transition shadow-sm'
                    }
                });
            });
        }

        function resetButton() {
            const btn = document.getElementById('paymentBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-university mr-2"></i>Pay via Virtual Account';
        }

        function verifyPaymentAndRedirect(vaNumber, transactionId) {
            console.log('=== VERIFICATION DEBUG START ===');
            console.log('Verifying payment for VA:', vaNumber);
            console.log('Transaction ID:', transactionId);
            console.log('Verify URL:', '{{ route("customer.payment.verify") }}');
            
            fetch('{{ route("customer.payment.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ va_number: vaNumber })
            })
            .then(response => {
                console.log('=== VERIFY RESPONSE RECEIVED ===');
                console.log('Verify response status:', response.status);
                console.log('Verify response statusText:', response.statusText);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('=== VERIFY ERROR RESPONSE ===');
                        console.error(text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                console.log('=== VERIFY RESPONSE SUCCESS ===');
                console.log('Verify response:', data);
                
                if (data.success && data.status === 'success') {
                    // Payment successful - redirect ke success page
                    console.log('Payment successful, redirecting to:', data.redirect_url);
                    
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center pt-4">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-crown text-3xl text-yellow-500"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Payment Successful!</h2>
                                <p class="text-sm text-gray-600 text-center mb-4">
                                    Your membership has been activated.<br>
                                    Redirecting to dashboard...
                                </p>
                                <div class="flex items-center text-green-600">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    <span class="text-sm font-medium">Redirecting...</span>
                                </div>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        customClass: {
                            popup: 'rounded-2xl p-0 w-[24rem]'
                        },
                        timer: 2000
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                    window.location.href = data.redirect_url;
                    
                } else if (data.status === 'pending') {
                    // Payment still pending - show user-friendly message
                    console.log('Payment still pending...');
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Payment Pending',
                        html: `
                            <div class="text-gray-700">
                                <i class="fas fa-hourglass-half text-blue-500 text-2xl mb-3"></i>
                                <p class="mb-3"><strong>Payment is still pending.</strong></p>
                                <p class="mb-2">Please complete payment at Doovera dashboard.</p>
                                <p>After payment, click "Check Payment Status" button again to verify.</p>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6',
                        background: '#fff',
                        customClass: {
                            popup: 'rounded-lg shadow-xl',
                            title: 'text-gray-800 font-semibold',
                            confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                        }
                    });
                    
                } else {
                    console.log('Payment status:', data.status);
                    
                    Swal.fire({
                        icon: 'question',
                        title: 'Payment Status',
                        html: `
                            <div class="text-gray-700">
                                <i class="fas fa-info-circle text-gray-500 text-2xl mb-3"></i>
                                <p class="mb-3"><strong>Payment verification result:</strong></p>
                                <p class="mb-3 text-lg font-medium">${data.status || 'Unknown status'}</p>
                                <p>You can try checking status again after completing payment at Doovera dashboard.</p>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#6b7280',
                        background: '#fff',
                        customClass: {
                            popup: 'rounded-lg shadow-xl',
                            title: 'text-gray-800 font-semibold',
                            confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                        }
                    });
                }
            })
            .catch(error => {
                hideLoading();
                resetButton();
                
                console.error('=== VERIFICATION ERROR ===');
                console.error('Error type:', error.constructor.name);
                console.error('Error message:', error.message);
                console.error('Full error:', error);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Verification Failed',
                    html: `
                        <div class="text-gray-700">
                            <i class="fas fa-times-circle text-red-500 text-2xl mb-3"></i>
                            <p class="mb-2"><strong>Payment verification failed:</strong></p>
                            <p class="mb-3 text-sm text-gray-600">${error.message}</p>
                            <p class="text-sm">Check console for details.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545',
                    background: '#fff',
                    customClass: {
                        popup: 'rounded-lg shadow-xl',
                        title: 'text-gray-800 font-semibold',
                        confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                    }
                });
            });
        }

        function showPaymentStatusChecker(vaNumber, transactionId) {
            // Hide original payment button
            const originalBtn = document.getElementById('paymentBtn');
            originalBtn.style.display = 'none';
            
            // Create status checker UI
            const statusDiv = document.createElement('div');
            statusDiv.className = 'mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg';
            statusDiv.innerHTML = `
                <h3 class="text-lg font-medium text-blue-800 mb-2">Payment Created Successfully</h3>
                <p class="text-sm text-blue-600 mb-3">VA Number: <strong>${vaNumber}</strong></p>
                <div class="bg-green-50 border border-green-200 rounded p-3 mb-4">
                    <p class="text-sm text-green-800 mb-2">✅ Virtual Account has been created successfully!</p>
                    <p class="text-xs text-green-600">Complete your payment at Doovera dashboard, then click "Check Payment Status" below.</p>
                </div>
                <button id="checkStatusBtn" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>Check Payment Status
                </button>
                
                @if(app()->environment(['local', 'development', 'testing']))
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-xs text-yellow-800 mb-2"><strong>Development Mode:</strong> Simulate payment for testing</p>
                    <button id="simulateBtn" class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors duration-200 text-sm">
                        <i class="fas fa-magic mr-2"></i>Simulate Payment Success (Testing Only)
                    </button>
                </div>
                @endif
            `;
            
            // Insert after payment button
            originalBtn.parentNode.insertBefore(statusDiv, originalBtn.nextSibling);
            
            // Add event listener to check status button
            document.getElementById('checkStatusBtn').addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking...';
                
                verifyPaymentAndRedirect(vaNumber, transactionId);
                
                // Reset button after 5 seconds if not redirected
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Check Payment Status';
                }, 5000);
            });
            
            // Add simulate payment button event listener (development only)
            @if(app()->environment(['local', 'development', 'testing']))
            const simulateBtn = document.getElementById('simulateBtn');
            if (simulateBtn) {
                simulateBtn.addEventListener('click', function() {
                    if (!confirm('Simulate payment success for testing?\n\nThis will mark the payment as completed and activate the membership.')) {
                        return;
                    }
                    
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Simulating...';
                    
                    fetch(`/customer/payment/simulate-success/${vaNumber}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Simulation Successful!',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-magic text-purple-500 text-2xl mb-3"></i>
                                        <p>Payment simulation successful!</p>
                                        <p>Redirecting to success page...</p>
                                    </div>
                                `,
                                confirmButtonText: 'Continue',
                                confirmButtonColor: '#10b981',
                                timer: 2000,
                                timerProgressBar: true,
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            }).then(() => {
                                window.location.href = data.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Simulation Failed',
                                html: `
                                    <div class="text-gray-700">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                                        <p>${data.error || 'Unknown error'}</p>
                                    </div>
                                `,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc3545',
                                background: '#fff',
                                customClass: {
                                    popup: 'rounded-lg shadow-xl',
                                    title: 'text-gray-800 font-semibold',
                                    confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Simulation Error',
                            html: `
                                <div class="text-gray-700">
                                    <i class="fas fa-bug text-red-500 text-2xl mb-2"></i>
                                    <p>${error.message}</p>
                                </div>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc3545',
                            background: '#fff',
                            customClass: {
                                popup: 'rounded-lg shadow-xl',
                                title: 'text-gray-800 font-semibold',
                                confirmButton: 'px-4 py-2 rounded-md text-white font-medium'
                            }
                        });
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-magic mr-2"></i>Simulate Payment Success (Testing Only)';
                    });
                });
            }
            @endif
            
            // No auto-refresh - user must click manually to check status
            console.log('Payment created. User can check status manually by clicking the button.');
        }
    </script>
</body>
</html>
