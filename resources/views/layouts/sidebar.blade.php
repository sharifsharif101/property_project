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
            <i class="bi bi-pie-chart-fill text-lg w-6 text-center"></i>
            <span>لوحة التحكم</span>
        </a>

        <!-- Properties Section -->
        <div x-data="{ open: {{ request()->routeIs('properties.*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <i class="bi bi-building text-lg w-6 text-center"></i>
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
                    <i class="bi bi-grid-3x3-gap-fill text-lg w-6 text-center"></i>
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
                    <i class="bi bi-people-fill text-lg w-6 text-center"></i>
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
                    <i class="bi bi-file-earmark-text-fill text-lg w-6 text-center"></i>
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

        <!-- Payments Section (Updated) -->
        <div x-data="{ open: {{ request()->routeIs(['installments.*', 'payments.*']) ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full flex items-center justify-between p-2 rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">
                <span class="flex items-center gap-3">
                    <i class="bi bi-cash-stack text-lg w-6 text-center"></i>
                    <span>المدفوعات والأقساط</span>
                </span>
                <svg :class="{ 'rotate-90': open }" class="w-5 h-5 transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition class="pr-6 mt-2 space-y-1">
                <a href="{{ route('installments.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('installments.index') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    سجل الأقساط
                </a>
                
                <a href="{{ route('payments.index') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('payments.index') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    سجل المدفوعات
                </a>

                <a href="{{ route('payments.create') }}"
                    class="block p-2 text-sm rounded-md transition-colors {{ request()->routeIs('payments.create') ? 'text-white bg-gray-700' : 'text-gray-400 hover:text-white' }}">
                    إضافة دفعة جديدة
                </a>
            </div>
        </div>
    </nav>
</div>