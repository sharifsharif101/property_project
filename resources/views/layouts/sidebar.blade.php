 {{-- Make sure Alpine.js is included in your main layout (layouts/app.blade.php) --}}
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

<!-- Sidebar -->
<div id="sidebar"
    class="w-64 bg-gray-800 text-white p-4 flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out fixed lg:relative top-0 right-0 h-screen lg:h-auto z-50 translate-x-full lg:translate-x-0">

    <!-- Sidebar Header -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-2 mb-6">
        <img src="{{ asset('img/house.png') }}" class="h-10 w-10 rounded-full object-cover" alt="Logo">
        <h2 class="text-lg font-bold text-gray-200">إدارة العقارات</h2>
    </a>

    <!-- Navigation Links -->
    <nav class="flex-grow space-y-2">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 p-2 rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <span>لوحة التحكم</span>
        </a>

        <!-- Properties Section -->
        <div x-data="{ open: {{ request()->routeIs('properties.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>العقارات</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-5 h-5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="pr-6 mt-2 space-y-1">
                <a href="{{ route('properties.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('properties.index') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    جميع العقارات
                </a>
                <a href="{{ route('properties.create') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('properties.create') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    إضافة عقار جديد
                </a>
            </div>
        </div>

        <!-- Units Section -->
        <div x-data="{ open: {{ request()->routeIs('units.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>الشقق | الوحدات</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-5 h-5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="pr-6 mt-2 space-y-1">
                <a href="{{ route('units.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('units.index') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    جميع الوحدات
                </a>
                <a href="{{ route('units.create') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('units.create') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    إضافة وحدة جديدة
                </a>
            </div>
        </div>

        <!-- Tenants Section -->
        <div x-data="{ open: {{ request()->routeIs('tenants.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>المستأجرين</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-5 h-5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="pr-6 mt-2 space-y-1">
                <a href="{{ route('tenants.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('tenants.index') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    جميع المستأجرين
                </a>
                <a href="{{ route('tenants.create') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('tenants.create') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    إضافة مستأجر جديد
                </a>
            </div>
        </div>

        <hr class="my-4 border-gray-700">

        <!-- Contracts Section -->
        <div x-data="{ open: {{ request()->routeIs('contracts.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>العقود</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-5 h-5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="pr-6 mt-2 space-y-1">
                <a href="{{ route('contracts.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('contracts.index') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    جميع العقود
                </a>
                <a href="{{ route('contracts.create') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('contracts.create') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    إضافة عقد جديد
                </a>
            </div>
        </div>

        <!-- Payments Section -->
        <div x-data="{ open: {{ request()->routeIs(['installments.*', 'payments.*']) ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>المدفوعات والأقساط</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-5 h-5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="pr-6 mt-2 space-y-1">
                <a href="{{ route('installments.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('installments.*') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    سجل الأقساط
                </a>
                <a href="{{ route('payments.create') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('payments.create') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    إضافة دفعة جديدة
                </a>
            </div>
        </div>
    </nav>
</div>