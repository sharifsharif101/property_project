 <aside class="border-4 border-red main-sidebar" dir="ltr">
    <script src="//unpkg.com/alpinejs" defer></script>
    <section class="sidebar">
        <div class="user-panel flex items-center px-4 py-3 space-x-3 rtl:space-x-reverse">
            <div class="order-2">
                <img src="{{ asset('img/house.png') }}" class="rounded-full" alt="House Image"
                    style="width: 35px; height: 35px; object-fit: cover;">
            </div>
            <div class="order-1 flex-1">
                <p class="text-base font-medium" style="font-size: 19px; color: wheat; direction: rtl;">إدارة العقارات
                </p>
            </div>
        </div>

        <ul class="sidebar-menu">

            {{-- ▼▼▼ هذا هو الرابط الجديد للوحة التحكم ▼▼▼ --}}
            <li class="border rounded-md mb-2">
                <a href="{{ route('dashboard') }}"
                    class="w-full flex items-center px-4 py-2 bg-gray-200 hover:bg-blue-600 hover:text-white rounded-md focus:outline-none">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-tachometer-alt"></i>
                        <span class="font-medium">لوحة التحكم</span>
                    </div>
                </a>
            </li>
            {{-- ▲▲▲ نهاية الرابط الجديد ▲▲▲ --}}

            <div class="mt-7"></div>
            
            {{-- قسم العقارات --}}
            <li x-data="{ open: false }" class="border rounded-md mb-2">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-edit"></i>
                        <span class="font-medium">العقارات</span>
                    </div>
                    <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-transition class="pl-8 mt-2 space-y-1 text-gray-700">
                    <li>
                        <a href="{{ route('properties.index') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-200">
                            جميع العقارات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('properties.create') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-500 hover:text-white transition duration-200">
                            إضافة عقار جديد
                        </a>
                    </li>
                </ul>
            </li>
            
            {{-- قسم الشقق | الوحدات --}}
            <li x-data="{ open: false }" class="border rounded-md mt-5">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-building"></i> {{-- أيقونة معدلة --}}
                        <span class="font-medium"> الشقق | الوحدات </span>
                    </div>
                    <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-transition class="pl-8 mt-2 space-y-2 text-gray-700">
                    <li>
                        <a href="{{ route('units.index') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-[#C78A3B] hover:text-white transition duration-200">
                            جميع الوحدات
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('units.create') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-[#C78A3B] hover:text-white transition duration-200">
                            إضافة وحدة جديدة
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم المستأجرين --}}
            <li x-data="{ open: false }" class="border rounded-md mt-5">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-users"></i>
                        <span class="font-medium">المستأجرين</span>
                    </div>
                    <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-transition class="pl-8 mt-2 space-y-2 text-gray-700">
                    <li>
                        <a href="{{ route('tenants.index') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-green-500 hover:text-white transition duration-200">
                            جميع المستأجرين
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tenants.create') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-green-500 hover:text-white transition duration-200">
                            إضافة مستأجر جديد
                        </a>
                    </li>
                </ul>
            </li>

            <div class="mt-7"></div>

            {{-- قسم العقود --}}
            <li x-data="{ open: false }" class="border rounded-md mt-5">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-file-contract"></i>
                        <span class="font-medium">العقود</span>
                    </div>
                    <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-transition class="pl-8 mt-2 space-y-2 text-gray-700">
                    <li>
                        <a href="{{ route('contracts.index') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-purple-500 hover:text-white transition duration-200">
                            جميع العقود
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contracts.create') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-purple-500 hover:text-white transition duration-200">
                            إضافة عقد جديد
                        </a>
                    </li>
                </ul>
            </li>
            
            {{-- قسم المدفوعات والأقساط --}}
            <div class="mt-7"></div>
            <li x-data="{ open: false }" class="border rounded-md mt-5">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-money-bill-wave"></i>
                        <span class="font-medium">المدفوعات والأقساط</span>
                    </div>
                    <svg :class="{ 'transform rotate-90': open }" class="w-4 h-4 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-transition class="pl-8 mt-2 space-y-2 text-gray-700">
                    <li>
                        <a href="{{ route('installments.index') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-teal-500 hover:text-white transition duration-200">
                            سجل الأقساط
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payments.create') }}"
                            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-teal-500 hover:text-white transition duration-200">
                            إضافة دفعة جديدة
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </section>
</aside>