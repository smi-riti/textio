<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coming Soon - Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS CDN (if not using Laravel Mix/Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .fade-in {
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-white flex items-center justify-center min-h-screen px-6">

    <div class="text-center fade-in">
        <h1 class="text-5xl lg:text-6xl font-semibold mb-4">🚀 Coming Soon</h1>
        <p class="text-lg lg:text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-xl mx-auto">
            We’re working hard to bring something amazing to life. Stay tuned!
        </p>
        <a href="#" class="inline-block px-6 py-3 bg-black text-white rounded-full font-medium hover:bg-gray-800 transition duration-300">
            Notify Me
        </a>
    </div>

</body>
</html>
