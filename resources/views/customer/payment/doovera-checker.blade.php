<!DOCTYPE html>
<html>
<head>
    <title>Doovera Payment Status Checker</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .info { background: #cce7ff; border: 1px solid #99d6ff; color: #004085; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        input { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px; }
        .status-display { font-family: monospace; background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔍 Doovera Payment Status Checker</h1>
    
    <div style="margin: 20px 0;">
        <label>VA Number:</label><br>
        <input type="text" id="vaNumber" value="8800003398844716" placeholder="Enter VA Number">
        <button onclick="checkDooveraStatus()">Check Doovera Status</button>
        <button onclick="checkLocalStatus()">Check Local Status</button>
        <button onclick="syncPayment()">Sync Payment</button>
    </div>
    
    <div id="result"></div>
    
    <h3>Local Database Status:</h3>
    @php
        $user = auth()->user();
        $transaction = null;
        $membership = null;
        
        if ($user) {
            $transaction = \App\Models\Transaction::where('payment_gateway_id', '8800003398844716')->first();
            if ($transaction) {
                $membership = $transaction->membership;
            }
        }
    @endphp
    
    <div class="status-display">
        <strong>User:</strong> {{ $user->name ?? 'Not logged in' }}<br>
        <strong>Transaction Status:</strong> {{ $transaction->status ?? 'Not found' }}<br>
        <strong>Transaction Paid At:</strong> {{ $transaction->paid_at ?? 'Not paid' }}<br>
        @if($membership)
            <strong>Membership Status:</strong> {{ $membership->status }}<br>
            <strong>Payment Status:</strong> {{ $membership->payment_status }}<br>
            <strong>Valid Until:</strong> {{ $membership->end_time }}<br>
        @endif
    </div>

    <script>
        async function checkDooveraStatus() {
            const vaNumber = document.getElementById('vaNumber').value;
            showResult('Checking Doovera API...', 'info');
            
            try {
                const response = await fetch('/api/doovera/check-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ va_number: vaNumber })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showResult(`✅ Doovera Status: ${data.doovera_status}<br>Local Status: ${data.local_status}`, 'success');
                } else {
                    showResult(`❌ Error: ${data.error}`, 'error');
                }
            } catch (error) {
                showResult(`❌ Network Error: ${error.message}`, 'error');
            }
        }
        
        async function checkLocalStatus() {
            const vaNumber = document.getElementById('vaNumber').value;
            showResult('Checking local database...', 'info');
            
            try {
                const response = await fetch('{{ route("customer.payment.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ va_number: vaNumber })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showResult(`✅ Payment Success! Redirecting...`, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 2000);
                } else {
                    showResult(`⏳ Status: ${data.status}<br>Message: ${data.message}`, 'warning');
                }
            } catch (error) {
                showResult(`❌ Error: ${error.message}`, 'error');
            }
        }
        
        async function syncPayment() {
            const vaNumber = document.getElementById('vaNumber').value;
            showResult('Syncing payment with Doovera...', 'info');
            
            try {
                const response = await fetch('/api/payment/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ va_number: vaNumber })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showResult(`✅ Sync Success! ${data.message}`, 'success');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showResult(`❌ Sync Failed: ${data.error}`, 'error');
                }
            } catch (error) {
                showResult(`❌ Sync Error: ${error.message}`, 'error');
            }
        }
        
        function showResult(message, type) {
            document.getElementById('result').innerHTML = `<div class="${type}">${message}</div>`;
        }
        
        // Auto check on load
        window.onload = function() {
            checkLocalStatus();
        };
    </script>
</body>
</html>
