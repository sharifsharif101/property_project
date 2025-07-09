<!-- Header -->
<header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center shadow-sm flex-shrink-0">
  <div id="menuBtn" class="lg:hidden text-2xl cursor-pointer">☰</div>
  <div class="flex-grow"></div>
  <div class="flex items-center gap-6">
    <div class="relative cursor-pointer text-xl">
      <span>🔔</span>
      <span class="absolute top-0 -left-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
    </div>
    {{-- Assuming user is authenticated --}}
    @auth
      <div class="font-bold">مرحباً، {{ Auth::user()->name }}</div>
    @else
      <div class="font-bold">مرحباً، زائر</div>
    @endauth
  </div>
</header>