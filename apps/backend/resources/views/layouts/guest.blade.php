<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Speisekarte') – MenuSnap</title>
    <link rel="icon" type="image/png" href="/images/logoNoName.png">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen">
    @yield('content')

    {{-- Footer --}}
    <footer class="py-6 text-center">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs text-gray-400 hover:text-gray-500 transition-colors">
            <img src="/images/logoNoName.png" alt="MenuSnap" class="h-3.5 w-auto opacity-40">
            Powered by MenuSnap
        </a>
    </footer>
</body>
</html>
