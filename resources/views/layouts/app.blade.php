<!DOCTYPE html>
<html dir="rtl" lang="ar" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables with Tailwind CSS Theme (CSS) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.tailwindcss.css">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
            /* إجبار الصفحة على الظهور دائمًا في الوضع الفاتح، بغض النظر عن إعدادات النظام */
    html {
        color-scheme: light !important;
    }

    /* أو بشكل أكثر تحكمًا: تأكد أن جميع العناصر تستخدم ألوان الوضع الفاتح */
    html, body, .bg-white, .bg-gray-50, .text-gray-800, .text-gray-600, .text-gray-500, .text-gray-900,
    .bg-green-100, .bg-red-100, .bg-yellow-100, .bg-blue-100, .bg-gray-200,
    .border-gray-200, .border-red-400, .border-yellow-400, .border-blue-400 {
        --tw-bg-opacity: 1 !important;
        --tw-text-opacity: 1 !important;
        --tw-border-opacity: 1 !important;
    }
        body { font-family: 'Cairo', sans-serif; }
        .submenu { max-height: 0; transition: max-height 0.3s ease-out; }

        /* Custom styles to improve DataTables + Tailwind look */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.5rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0 0.25rem !important;
            padding: 0.5rem 0.75rem !important;
        }
    </style>

    
</head>

<body class="bg-gray-100 flex min-h-screen">
    <div id="overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>
    
    @include('layouts.sidebar')
   
    <div class="flex-grow flex flex-col">
        @include('layouts.nav')
        
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-6 sm:px-8">
                    @yield('header')
                </div>
            </header>
        @endif
 
        <main class="p-6 md:p-8">
            @yield('content')
        </main>
    </div>

    {{-- ====================================================== --}}
    {{--                JAVASCRIPTS SECTION                     --}}
    {{-- ====================================================== --}}

    <!-- jQuery (required by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables with Tailwind CSS Theme (JS) -->
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.tailwindcss.js"></script>

    <!-- Layout-specific scripts (Sidebar logic) -->
    <script>
        // Your existing sidebar toggle scripts...
        const sidebar = document.getElementById("sidebar");
        const menuBtn = document.getElementById("menuBtn");
        const overlay = document.getElementById("overlay");
        if (menuBtn && sidebar && overlay) {
            menuBtn.addEventListener("click", () => {
                sidebar.classList.toggle("translate-x-full");
                overlay.classList.toggle("hidden");
            });
            overlay.addEventListener("click", () => {
                sidebar.classList.add("translate-x-full");
                overlay.classList.add("hidden");
            });
        }
    </script>
 
    <!-- This is where scripts from child pages will be injected -->
    @stack('scripts')
</body>
</html>