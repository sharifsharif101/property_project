<aside class=" border-4 border-red main-sidebar"  dir="ltr">
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
    <div class="user-panel flex items-center px-4 py-3 space-x-3 rtl:space-x-reverse">
  <!-- الصورة على اليمين -->
  <div class="order-2">
    <img 
      src="{{ asset('img/house.png') }}" 
      class="rounded-full" 
      alt="House Image" 
      style="width: 35px; height: 35px; object-fit: cover;"
    >
  </div>

  <!-- النص على اليسار -->
  <div class="order-1 flex-1">
<p class="text-base font-medium" style="
    font-size: 19px;
    color: wheat;
    direction: rtl;
">إدارة العقارات</p>  </div>
</div>


        <!-- /.search form -->

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li x-data="{ open: false }" class="border rounded-md mb-2">
                <button 
                    @click="open = !open" 
                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none"
                >
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fa fa-edit"></i>
                        <span class="font-medium">العقارات</span>
                    </div>
                    <svg 
                        :class="{'transform rotate-90': open}" 
                        class="w-4 h-4 transition-transform duration-300" 
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <ul 
                    x-show="open" 
                    x-transition 
                    class="pl-8 mt-2 space-y-1 text-gray-700"
                >
                    <li>
                        <a 
                            href="{{ route('properties.create') }}" 
                            class="block px-2 py-1 rounded hover:bg-blue-500 hover:text-white"
                        >
                            إضافة عقار جديد
                        </a>
                    </li>
                </ul>

              
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
