<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  {{-- The title will be provided by the child page, with a default value --}}
  <title>@yield('title', 'لوحة التحكم')</title>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Cairo', sans-serif; }
    .submenu { max-height: 0; transition: max-height 0.3s ease-out; }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex min-h-screen">

  <!-- Overlay for mobile menu -->
  <div id="overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

  {{-- Including the sidebar partial --}}
  @include('layoutss.partials.sidebar')

  <!-- Main Content Wrapper -->
  <div class="flex-grow flex flex-col">

    {{-- Including the header partial --}}
    @include('layoutss.partials.header')

    <!-- Page Content -->
    <main class="p-8">
      {{-- This is where the content of each page will be injected --}}
      @yield('contents')
    </main>
    
  </div>

  {{-- Scripts for layout interactivity --}}
  <script>
    const sidebar = document.getElementById("sidebar");
    const menuBtn = document.getElementById("menuBtn");
    const overlay = document.getElementById("overlay");

    function toggleMenu() {
      sidebar.classList.toggle("translate-x-full");
      overlay.classList.toggle("hidden");
    }

    menuBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleMenu();
    });

    overlay.addEventListener("click", () => {
      toggleMenu();
    });

    const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener("click", () => {
        const submenu = toggle.nextElementSibling;
        const chevron = toggle.querySelector(".chevron");
        if (submenu.style.maxHeight && submenu.style.maxHeight !== '0px') {
          submenu.style.maxHeight = '0px';
          chevron.classList.remove("rotate-180");
        } else {
          submenu.style.maxHeight = submenu.scrollHeight + "px";
          chevron.classList.add("rotate-180");
        }
      });
    });
  </script>
  
  {{-- You can stack page-specific scripts here if needed --}}
  @stack('scriptss')

</body>
</html>