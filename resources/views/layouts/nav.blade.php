{{-- Make sure Alpine.js is included in your main layout (layouts/app.blade.php) --}}
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

<header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center shadow-sm flex-shrink-0 z-10">
   
    <!-- Hamburger Menu Button for mobile (You can add this if needed) -->
    <button id="menuBtn" class="lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
    </button>
   
    <!-- This div pushes items to the left (in RTL) or right (in LTR) -->
    <div class="flex-grow"></div>

    <!-- Right side of header: Icons and User Menu -->
    <div class="flex items-center gap-x-4 sm:gap-x-6">

        <!-- Notifications Dropdown (example) -->
        <div class="relative">
            <button class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </button>
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-sm font-medium text-gray-700">
                <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('img/avatar2.png') }}" alt="User avatar">
                <span class="hidden sm:inline">{{ Auth::user()->name ?? 'مستخدم' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden sm:inline transition-transform" :class="{ 'rotate-180': open }" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- ✅✅✅ Dropdown Panel (هذا هو الجزء المضاف) ✅✅✅ -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                 style="display: none;">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                    {{-- يمكنك إضافة رابط لملف المستخدم هنا في المستقبل --}}
                    {{-- 
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        ملف المستخدم
                    </a> 
                    --}}
                    
                    <!-- زر تسجيل الخروج -->
                    <form method="POST" action="{{ route('logout') }}" role="menuitem">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            تسجيل الخروج
                        </a>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</header>