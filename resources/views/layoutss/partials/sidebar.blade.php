<!-- Sidebar -->
<div id="sidebar" class="w-64 bg-gray-800 text-white p-5 flex-shrink-0 transition-transform duration-300 ease-in-out
                        fixed lg:relative top-0 right-0 h-screen lg:h-auto z-50
                        translate-x-full lg:translate-x-0">
  <h2 class="text-xl font-bold mb-8">لوحة التحكم</h2>
  
  <nav class="flex flex-col gap-2">
    <a href="{{-- route('dashboard') --}}" class="flex items-center gap-3 p-2 rounded-md transition-colors hover:bg-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
      <span>الرئيسية</span>
    </a>

    <a href="#" class="flex items-center gap-3 p-2 rounded-md transition-colors hover:bg-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
      <span>العقود</span>
    </a>
    
    <a href="#" class="flex items-center gap-3 p-2 rounded-md transition-colors hover:bg-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-3-1.95m-3 1.95a9 9 0 00-3-1.95m0 0A2.25 2.25 0 015.25 15.75v-1.5a2.25 2.25 0 012.25-2.25" /></svg>
      <span>المستأجرين</span>
    </a>

    <!-- Multi-level Menu Item -->
    <div>
      <button class="dropdown-toggle w-full flex items-center justify-between gap-3 p-2 rounded-md transition-colors hover:bg-gray-700">
        <div class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
          <span>التقارير</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 chevron transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
      </button>
      <div class="submenu overflow-hidden pr-8 mt-2 space-y-2">
        <a href="#" class="block p-2 text-sm rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">تقارير مالية</a>
        <a href="#" class="block p-2 text-sm rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">تقارير الإشغال</a>
        <a href="#" class="block p-2 text-sm rounded-md transition-colors text-gray-300 hover:bg-gray-700 hover:text-white">تقارير الصيانة</a>
      </div>
    </div>

    <!-- Divider -->
    <hr class="my-4 border-gray-700">
    
    <a href="#" class="flex items-center gap-3 p-2 rounded-md transition-colors hover:bg-gray-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
      <span>الإعدادات</span>
    </a>
  </nav>
</div>