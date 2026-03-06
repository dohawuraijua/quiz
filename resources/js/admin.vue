<template>
  <section>
    <div v-if="isLogedin">
      <loadingbar />
      <div class="app-wrapper"><!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
          <div class="p-4 border-b border-white border-opacity-20 flex items-center justify-between">
            <div>
              <h2 id="platform-name" class="text-xl font-bold text-white">E-Learning (𝕊𝕂𝕐𝕃𝔸)</h2>
              <p class="text-white text-opacity-80 text-sm mt-1">Smart Knowledge & Learning App</p>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-white hover:text-red-800">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 20 20"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <nav class="p-4">
            <div class="menu-item rounded-lg mb-2"><router-link to="/" class="flex items-center px-4 py-3 text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg><span class="font-medium">Dashboard</span> </router-link>
            </div>

            <div
              class="menu-item rounded-lg mb-2 hover:shadow-[0_0_15px_rgba(255,255,255,0.1)] transition-all duration-300">
              <button onclick="toggleSubmenu('quiz')"
                class="w-full flex items-center justify-between px-4 py-3 text-white">
                <div class="flex items-center">
                  <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                  </svg><span class="font-medium">Quiz</span>
                </div>
                <svg id="chevron-quiz" class="w-6 h-4 chevron" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div id="submenu-quiz" class="submenu">
                <router-link to="/quiz/isian" class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                  Isian </router-link>
              </div>
            </div>

          </nav>
        </aside>
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div><!-- Main Content -->

        <div class="main-content-wrapper">
          <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center">

            <!-- Button Sidebar (Kiri) -->
            <button id="menu-toggle" onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-800">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>

            <div class="flex items-center space-x-4 ml-auto">
              <div class="relative">
                <button @click="isOpen = !isOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                  <span class="hidden sm:inline font-medium">
                    Andre
                  </span>
                  <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-100">
                    <img src="https://via.placeholder.com/150" alt="" class="w-full h-full cover" />
                  </div>

                </button>

                <!-- Dropdown -->
                <div v-if="isOpen" class="absolute right-0 mt-2 w-40 z-20 bg-white rounded-lg shadow-lg py-1">
                  <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    Profile
                  </a>
                  <a class="block px-4 py-2 text-red-600 hover:bg-gray-100 cursor-pointer">
                    Logout
                  </a>
                </div>

                <!-- Close on outside click -->
                <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0 z-10"></div>
              </div>
            </div>

          </header>
          <div class="content-area">
            <RouterView></RouterView>
          </div>
        </div>

      </div>
    </div>
    <div v-else>
      <LoginPage />
    </div>
  </section>
</template>


<script>
import loadingbar from './loadingbar.vue';
import LoginPage from './components/LoginPage.vue';
export default {
  data() {
    return {
      isOpen: false,
      isLogedin: true
    }
  },
  components: {
    loadingbar, LoginPage
  },
  watch: {
    isLogedin(val) {
      setTimeout(() => {
        // this.getLocalStorage();
      }, 2000)
    }
  },
  mounted() {
    setTimeout(() => {
      try {
        // this.getLocalStorage();
      } catch (e) {
        console.error(e);
      }
    }, 200);
  },

  computed: {

  },

  methods: {


  }
}
</script>