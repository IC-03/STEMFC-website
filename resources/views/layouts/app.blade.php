<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'My App' }}</title>

    {{-- Example: if you’re using Tailwind or Bootstrap, link it here --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Allow individual pages to push extra <style> or <link> tags --}}
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">

    {{-- Your visible header (navbar, branding, etc.) --}}
    @include('layouts.header')

    {{-- Main content injected by child views --}}
    <main class="min-h-screen">
        @yield('main-container')
    </main>

    {{-- Your visible footer --}}
    @include('layouts.footer')

    {{-- Allow child views or components to push <script> blocks --}}
    @stack('scripts')

    {{-- Alpine.js: --}}
    <script src="https://unpkg.com/alpinejs" defer></script>

    {{-- SweetAlert2: --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

</body>
</html>
