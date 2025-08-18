<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Payment Successful - MinglaGawa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN (if not already included globally) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Remix Icon CDN for icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <div class="flex justify-center mb-4">
                <div class="bg-green-100 rounded-full p-4">
                    <i class="ri-check-line text-4xl text-green-600"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-green-700 mb-2">Payment Successful!</h2>
            <p class="text-gray-700 mb-4">
                Your payment of ₱{{ number_format($appointment->total_amount, 2) }} for the completed service has been processed successfully.
            </p>
            <p class="text-gray-600 mb-6">
                The freelancer has been notified and will receive their payment.<br>
                Thank you for using <span class="font-semibold text-green-700">MinglaGawa</span>!
            </p>
            <div class="space-y-3">
                <a href="{{ route('customer.appointments.view') }}"
                   class="inline-block px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition w-full">
                    View My Appointments
                </a>
                <a href="{{ route('customer.dashboard') }}"
                   class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition w-full">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>