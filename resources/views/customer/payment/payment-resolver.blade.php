<!DOCTYPE html>
<html>
<head>
    <title>Payment Status Resolver</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; text-align: center; }
        .status { padding: 20px; border-radius: 10px; margin: 20px 0; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .info { background: #cce7ff; border: 1px solid #99d6ff; color: #004085; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        button { padding: 15px 25px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 10px; font-size: 16px; }
        button:hover { background: #0056b3; }
        .redirect-btn { background: #28a745; }
        .redirect-btn:hover { background: #1e7e34; }
    </style>
</head>
<body>
    <h1>🚀 Payment Status Resolver</h1>
    
    @php
        $user = auth()->user();
        $membership = null;
        $transaction = null;
        
        if ($user) {
            $membership = \App\Models\Membership::where('user_id', $user->id)->orderBy('updated_at', 'desc')->first();
            if ($membership) {
                $transaction = \App\Models\Transaction::where('membership_id', $membership->id)->orderBy('updated_at', 'desc')->first();
            }
        }
    @endphp
    
    @if(!$user)
        <div class="error">
            ❌ User not logged in
        </div>
        <button onclick="window.location.href='{{ route('login') }}'">Login</button>
        
    @elseif(!$membership)
        <div class="info">
            ℹ️ No membership found for user: {{ $user->name }}
        </div>
        <button onclick="window.location.href='{{ route('customer.payment.show') }}'">Go to Payment</button>
        
    @elseif($membership->status === 'active' && $membership->payment_status === 'paid')
        <div class="success">
            ✅ <strong>PAYMENT SUCCESS!</strong><br><br>
            <strong>User:</strong> {{ $user->name }}<br>
            <strong>Membership:</strong> ACTIVE<br>
            <strong>Payment:</strong> PAID<br>
            <strong>Valid Until:</strong> {{ $membership->end_time }}<br><br>
            Redirecting to success page...
        </div>
        
        @if($transaction && $transaction->status === 'completed')
            <button class="redirect-btn" onclick="goToSuccess()">
                🎉 Go to Success Page
            </button>
            <script>
                function goToSuccess() {
                    window.location.href = '{{ route("customer.payment.success", ["transaction_id" => $transaction->id ?? ""]) }}';
                }
                
                // Auto redirect after 3 seconds
                setTimeout(goToSuccess, 3000);
            </script>
        @else
            <button class="redirect-btn" onclick="window.location.href='{{ route('customer.dashboard') }}'">
                🏠 Go to Dashboard
            </button>
            <script>
                setTimeout(() => {
                    window.location.href = '{{ route("customer.dashboard") }}';
                }, 3000);
            </script>
        @endif
        
    @else
        <div class="info">
            ⏳ <strong>Status Check:</strong><br><br>
            <strong>User:</strong> {{ $user->name }}<br>
            <strong>Membership Status:</strong> {{ $membership->status }}<br>
            <strong>Payment Status:</strong> {{ $membership->payment_status }}<br>
            @if($transaction)
                <strong>Transaction Status:</strong> {{ $transaction->status }}<br>
                <strong>VA Number:</strong> {{ $transaction->payment_gateway_id }}<br>
            @endif
        </div>
        
        <button onclick="forceActivate()">🔧 Force Activate</button>
        <button onclick="window.location.href='{{ route('customer.payment.show') }}'">💳 Go to Payment</button>
        
        <script>
            function forceActivate() {
                if (confirm('Force activate membership for {{ $user->name }}?')) {
                    fetch('{{ route("membership.force-check") }}', {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            alert('Force activation completed. Refreshing page...');
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error.message);
                    });
                }
            }
        </script>
    @endif
    
    <hr style="margin: 40px 0;">
    
    <h3>Quick Actions</h3>
    <button onclick="window.location.reload()">🔄 Refresh</button>
    <button onclick="window.location.href='{{ route('customer.dashboard') }}'">🏠 Dashboard</button>
    
    <p style="margin-top: 30px; font-size: 14px; color: #666;">
        If payment was completed in Doovera but still showing as pending,<br>
        use the "Force Activate" button above.
    </p>
</body>
</html>
