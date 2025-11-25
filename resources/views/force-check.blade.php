<!DOCTYPE html>
<html>
<head>
    <title>Force Membership Check</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .status { padding: 15px; border-radius: 5px; margin: 10px 0; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .info { background: #cce7ff; border: 1px solid #99d6ff; color: #004085; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>🔄 Force Membership Check</h1>
    
    @php
        $user = auth()->user();
        $membership = $user ? $user->membership()->first() : null;
    @endphp
    
    <div class="info">
        <h3>Current Status:</h3>
        <p><strong>User:</strong> {{ $user->name ?? 'Not logged in' }}</p>
        <p><strong>User ID:</strong> {{ $user->id ?? 'N/A' }}</p>
        
        @if($membership)
            <p><strong>Membership Status:</strong> {{ $membership->status }}</p>
            <p><strong>Payment Status:</strong> {{ $membership->payment_status }}</p>
            <p><strong>Start Time:</strong> {{ $membership->start_time }}</p>
            <p><strong>End Time:</strong> {{ $membership->end_time }}</p>
        @else
            <p><strong>Membership:</strong> Not found</p>
        @endif
    </div>
    
    @if($membership && $membership->status === 'active')
        <div class="success">
            ✅ <strong>Membership is ACTIVE!</strong><br>
            You should be automatically redirected to dashboard.
            <br><br>
            <a href="{{ route('customer.dashboard') }}">
                <button>Go to Dashboard</button>
            </a>
        </div>
        
        <script>
            // Auto redirect after 3 seconds
            setTimeout(() => {
                window.location.href = '{{ route("customer.dashboard") }}';
            }, 3000);
        </script>
        
    @else
        <div class="info">
            ⏳ Membership is not active yet. 
            <br><br>
            <a href="{{ route('customer.payment.show') }}">
                <button>Back to Payment</button>
            </a>
        </div>
    @endif
    
    <hr style="margin: 30px 0;">
    
    <h3>Manual Actions:</h3>
    <button onclick="window.location.reload()">Refresh Page</button>
    <button onclick="checkPaymentStatus()">Check Payment Status</button>
    
    <script>
        async function checkPaymentStatus() {
            try {
                const response = await fetch('{{ route("customer.payment.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        va_number: '8800003398844716' // VA number yang kita tau
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Payment Success! Redirecting to success page...');
                    window.location.href = data.redirect_url;
                } else {
                    alert('Status: ' + data.status + '\nMessage: ' + (data.message || 'No message'));
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    </script>
</body>
</html>
