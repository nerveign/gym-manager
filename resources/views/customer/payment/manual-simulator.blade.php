<!DOCTYPE html>
<html>
<head>
    <title>Manual Payment Simulator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8 px-4 max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">🔧 Manual Payment Simulator</h2>
            <p class="text-sm text-gray-600 mb-4">Use this to simulate successful payment from Doovera</p>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">VA Number:</label>
                <input type="text" id="vaNumber" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                       placeholder="Enter VA number (e.g., 8800000461089862)">
            </div>
            
            <button onclick="simulatePayment()" id="simulateBtn" 
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-play mr-2"></i>Simulate Payment Success
            </button>
            
            <div id="result" class="mt-4"></div>
        </div>
    </div>

    <script>
        function simulatePayment() {
            const vaNumber = document.getElementById('vaNumber').value.trim();
            const resultDiv = document.getElementById('result');
            const btn = document.getElementById('simulateBtn');
            
            if (!vaNumber) {
                resultDiv.innerHTML = '<p class="text-red-600">Please enter VA number</p>';
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            // Simulate webhook call
            fetch('/webhook/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Signature': 'manual_test' // Special bypass signature
                },
                body: JSON.stringify({
                    external_id: 'GYM-000010-' + Date.now(),
                    va_number: vaNumber,
                    status: 'paid',
                    amount: 200000
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Webhook response:', data);
                
                if (data.status === 'success') {
                    resultDiv.innerHTML = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">✅ Payment simulated successfully!</div>';
                    
                    // Auto redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/customer/dashboard';
                    }, 2000);
                } else {
                    resultDiv.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">❌ Simulation failed: ' + (data.error || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">❌ Error: ' + error.message + '</div>';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-play mr-2"></i>Simulate Payment Success';
            });
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
