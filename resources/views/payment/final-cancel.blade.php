<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Payment Cancelled - MinglaGawa</title>
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
                <div class="bg-red-100 rounded-full p-4">
                    <i class="ri-close-line text-4xl text-red-600"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-red-700 mb-2">Payment Cancelled</h2>
            <p class="text-gray-700 mb-4">
                Your final payment for the completed service was not processed.
            </p>
            <p class="text-gray-600 mb-6">
                You can try again when you're ready or contact support if you need assistance.
            </p>
            <div class="space-y-3">
                <a href="{{ route('customer.appointments.view') }}"
                   class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition w-full">
                    Return to Appointments
                </a>
                <button onclick="window.history.back()" 
                   class="inline-block px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition w-full">
                    Go Back
                </button>
            </div>
        </div>
    </div>
</body>
</html>