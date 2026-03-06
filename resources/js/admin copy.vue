<template>

  <div>
    <loadingbar />
    <div class="app-wrapper"><!-- Sidebar -->
      <aside id="sidebar" class="sidebar">
        <div class="p-6 border-b border-white border-opacity-20">
          <h1 id="platform-name" class="text-2xl font-bold text-white">LearnHub</h1>
          <p class="text-white text-opacity-80 text-sm mt-1">Platform Pembelajaran</p>
        </div>
        <nav class="p-4"><!-- Dashboard Menu -->
          <div class="menu-item active rounded-lg mb-2"><router-link to="/"
              class="flex items-center px-4 py-3 text-white">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg><span class="font-medium">Dashboard</span> </router-link>
          </div><!-- My Courses Menu with Submenu -->


          <div class="menu-item rounded-lg mb-2"><button onclick="toggleSubmenu('resources')"
              class="w-full flex items-center justify-between px-4 py-3 text-white">
              <div class="flex items-center">
                <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg><span class="font-medium">Resources</span>
              </div>
              <svg id="chevron-resources" class="w-6 h-4 chevron" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div id="submenu-resources" class="submenu">
              <router-link to="/resources/peraturan"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Peraturan </router-link>
              <router-link to="/resources/videos"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Videos </router-link>
            </div>
          </div>

          <div class="menu-item rounded-lg mb-2"><button onclick="toggleSubmenu('course')"
              class="w-full flex items-center justify-between px-4 py-3 text-white">
              <div class="flex items-center">
                <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg><span class="font-medium">Ujian dan Quiz</span>
              </div>
              <svg id="chevron-course" class="w-6 h-4 chevron" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div id="submenu-course" class="submenu">
              <router-link to="/course/kategori"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Kategori </router-link>
              <router-link to="/course/modul-ujian"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                Modul </router-link>
              <router-link to="/course/soal" class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                Soal </router-link>
              <router-link to="/course/event" class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                Event </router-link>
            </div>
          </div>
          <div class="menu-item rounded-lg mb-2"><button onclick="toggleSubmenu('settings')"
              class="w-full flex items-center justify-between px-4 py-3 text-white">
              <div class="flex items-center">
                <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg><span class="font-medium">Settings</span>
              </div>
              <svg id="chevron-settings" class="w-6 h-4 chevron" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div id="submenu-settings" class="submenu">
              <router-link to="/setting/users"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Users </router-link>
            </div>
          </div>



        </nav>
      </aside><!-- Overlay for mobile -->
      <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div><!-- Main Content -->

      <div class="main-content-wrapper">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center">

          <!-- Button Sidebar (Kiri) -->
          <button id="menu-toggle" onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Kanan: Notifications + User -->
          <div class="flex items-center space-x-4 ml-auto">

            <!-- Notifications -->
            <button class="relative text-gray-600 hover:text-gray-800">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User Profile -->
            <div class="relative">
              <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">

                <!-- Avatar huruf depan -->
                <div class="w-9 h-9 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 
                      flex items-center justify-center text-white font-semibold">
                  S
                </div>

                <span class="hidden sm:inline font-medium">
                  Andre
                </span>

              </button>
            </div>
          </div>

        </header>
        <div class="content-area">
          <RouterView></RouterView>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import loadingbar from './loadingbar.vue';
export default {
  components: {
    loadingbar
  }
}
</script>