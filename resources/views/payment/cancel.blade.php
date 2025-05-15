<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Cancelled - MinglaGawa</title>
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
            <p class="text-gray-700 mb-6">
                Your payment was not completed.<br>
                You can try booking again or contact support if you need help.
            </p>
            <a href="{{ isset($post) && $post->freelancer ? route('freelancer.profile', $post->freelancer->id) : route('homepage') }}"
            class="inline-block px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded transition">
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>