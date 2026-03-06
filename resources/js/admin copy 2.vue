<template>

  <div>
    <loadingbar />
    <div class="app-wrapper"><!-- Sidebar -->
      <aside id="sidebar" class="sidebar">
        <div class="p-6 border-b border-white border-opacity-20">
          <h1 id="platform-name" class="text-2xl font-bold text-white">LearnHub</h1>
          <p class="text-white text-opacity-80 text-sm mt-1">Platform Pembelajaran</p>
        </div>


        <nav class="p-4">
          <!-- Loop semua menu items -->
          <div v-for="(menu, index) in filteredMenuItems" :key="index" class="menu-item rounded-lg mb-2"
            :class="{ 'hover:shadow-[0_0_15px_rgba(255,255,255,0.1)] transition-all duration-300': menu.submenu }">

            <!-- Menu tanpa submenu -->
            <router-link v-if="!menu.submenu" :to="menu.url" class="flex items-center px-4 py-3 text-white">
              <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="menu.icon" />
              </svg>
              <span class="font-medium">{{ menu.title }}</span>
            </router-link>

            <!-- Menu dengan submenu -->
            <template v-else>
              <button @click="toggleSubmenu(menu.title)"
                class="w-full flex items-center justify-between px-4 py-3 text-white">
                <div class="flex items-center">
                  <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="menu.icon" />
                  </svg>
                  <span class="font-medium">{{ menu.title }}</span>
                </div>
                <svg class="w-6 h-4 chevron transition-transform duration-300"
                  :class="{ 'rotate-180': openMenus[menu.title] }" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <!-- Submenu -->
              <transition name="slide">
                <div v-show="openMenus[menu.title]" class="submenu">
                  <router-link v-for="(sub, subIndex) in menu.submenu" :key="subIndex" :to="sub.url"
                    class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                    {{ sub.title }}
                  </router-link>
                </div>
              </transition>
            </template>

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

                <!-- Student Name -->
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
import menus from '../assets/menus.json';
export default {
  data() {
    return {
      menuItems: menus,
      openMenus: {}
    };
  },

  components: {
    loadingbar
  },
  computed: {
    filteredMenuItems() {
      const userRole = [1]; // Ambil dari Vuex/store
      return this.menuItems.filter(menu => {
        if (menu.roles.length === 0) return true; // Semua role bisa akses
        return menu.roles.includes(userRole);
      });
    }
  },
}
</script>