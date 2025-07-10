 {{-- Make sure Alpine.js is included in your main layout (layouts/app.blade.php) --}}
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

<header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center shadow-sm flex-shrink-0 z-10">
   <!-- #endregion -->

    <!-- This div pushes items to the left (in RTL) or right (in LTR) -->
    <div class="flex-grow"></div>

    <!-- Right side of header: Icons and User Menu -->
    <div class="flex items-center gap-x-4 sm:gap-x-6">

        <!-- Notifications Dropdown -->
        <div x-data="{ open: false }" class="relative">
     
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-700">
                <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('img/avatar2.png') }}" alt="User avatar">
                <span class="hidden sm:inline">{{ Auth::user()->name ?? 'محمد' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden sm:inline" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Panel -->
    
        </div>
    </div>
</header>