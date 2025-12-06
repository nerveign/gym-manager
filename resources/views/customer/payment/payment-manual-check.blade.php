<!DOCTYPE html>
<html>
<head>
    <title>Payment Manual Check - Simple</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body style="padding: 20px; font-family: Arial, sans-serif;">
    <h1>Payment Status Check (No Auto-Refresh)</h1>
    
    <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <h3>Manual Payment Status Check</h3>
        <p>Enter your VA Number to check payment status manually:</p>
        
        <input type="text" id="vaNumber" placeholder="Enter VA Number (e.g., 8800000000010236)" style="width: 350px; padding: 10px; margin: 10px 0;">
        <br>
        
        <button onclick="checkPaymentStatus()" style="background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px;">
            Check Payment Status
        </button>
        
        <button onclick="simulatePayment()" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px;">
            Simulate Payment Success
        </button>
    </div>
    
    <div id="result" style="margin-top: 20px;"></div>
    
    <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <h4>How to use:</h4>
        <ol>
            <li><strong>Create payment first</strong> from regular payment page</li>
            <li><strong>Copy VA Number</strong> from the alert message</li>
            <li><strong>Paste here</strong> and click "Check Payment Status"</li>
            <li><strong>Or simulate</strong> payment success for testing</li>
        </ol>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        function checkPaymentStatus() {
            var vaNumber = $('#vaNumber').val().trim();
            if (!vaNumber) {
                alert('Please enter VA Number');
                return;
            }
            
            $('#result').html('<p style="color: #007cba;">Checking payment status for VA: <strong>' + vaNumber + '</strong>...</p>');
            
            $.post('/customer/payment/verify', { va_number: vaNumber })
            .done(function(data) {
                if (data.success && data.status === 'success') {
                    $('#result').html('<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px;"><h4>✅ Payment Successful!</h4><p>Redirecting to success page...</p></div>');
                    setTimeout(function() {
                        window.location.href = data.redirect_url;
                    }, 2000);
                } else {
                    $('#result').html('<div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px;"><h4>⏳ Payment Still Pending</h4><p>Status: ' + data.status + '</p><p>Please complete payment in Doovera dashboard and check again.</p></div>');
                }
            })
            .fail(function(xhr) {
                $('#result').html('<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;"><h4>❌ Check Failed</h4><pre>' + xhr.responseText + '</pre></div>');
            });
        }
        
        function simulatePayment() {
            var vaNumber = $('#vaNumber').val().trim();
            if (!vaNumber) {
                alert('Please enter VA Number');
                return;
            }
            
            $('#result').html('<p style="color: #28a745;">Simulating payment success for VA: <strong>' + vaNumber + '</strong>...</p>');
            
            $.post('/simulate-payment', { va_number: vaNumber })
            .done(function(data) {
                if (data.success) {
                    $('#result').html('<div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px;"><h4>✅ Payment Simulated Successfully!</h4><p>Redirecting to success page...</p></div>');
                    setTimeout(function() {
                        window.location.href = data.redirect_url;
                    }, 2000);
                } else {
                    $('#result').html('<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;"><h4>❌ Simulation Failed</h4><p>' + data.message + '</p></div>');
                }
            })
            .fail(function(xhr) {
                $('#result').html('<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;"><h4>❌ Simulation Failed</h4><pre>' + xhr.responseText + '</pre></div>');
            });
        }
    </script>
</body>
</html>
