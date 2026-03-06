<template>

  <div>
    <loadingbar />
    <div class="app-wrapper">
      <aside id="sidebar" class="sidebar" v-if="!$route.meta.hideSidebar">
        <div class="p-6 border-b border-white border-opacity-20 flex items-center justify-between">
          <div>
            <h1 id="platform-name" class="text-2xl font-bold text-white">𝕊𝕂𝕐𝕃𝔸</h1>
            <p class="text-white text-opacity-80 text-sm mt-1">Smart Knowledge & Learning App</p>
          </div>

          <button onclick="toggleSidebar()" class="lg:hidden text-white hover:text-red-800">🗙</button>
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
            <button onclick="toggleSubmenu('resources')"
              class="w-full flex items-center justify-between px-4 py-3 text-white">
              <div class="flex items-center">
                <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg><span class="font-medium">Knowledge</span>
              </div>
              <svg id="chevron-resources" class="w-6 h-4 chevron" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div id="submenu-resources" class="submenu">
              <router-link to="/resources/peraturan"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Regulations </router-link>
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
                </svg><span class="font-medium">Quizzes</span>
              </div>
              <svg id="chevron-course" class="w-6 h-4 chevron" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div id="submenu-course" class="submenu">
              <router-link to="/course/jadwal-aktif"
                class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Exam Materials & Schedule
              </router-link>
            </div>
          </div>
        </nav>
      </aside>
      <div id="sidebar-overlay" class="sidebar-overlay" v-if="!$route.meta.hideSidebar" onclick="closeSidebar()"></div>

      <div class="main-content-wrapper transition-all">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center" v-if="!$route.meta.hideSidebar">
          <button id="menu-toggle" onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div class="flex items-center space-x-4 ml-auto">

            <button class="relative text-gray-600 hover:text-gray-800">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <div class="relative">
              <button @click="isOpen = !isOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                <div class="w-9 h-9 rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 
          flex items-center justify-center text-white font-semibold">
                  {{ userData?.name?.charAt(0)?.toUpperCase() || 'S' }}
                </div>
                <span class="hidden sm:inline font-medium">
                  {{ userData?.name }}
                </span>
              </button>

              <!-- Dropdown -->
              <div v-if="isOpen" class="absolute right-0 mt-2 w-40 z-20 bg-white rounded-lg shadow-lg py-1">
                <a @click="goToProfile()" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                  Profile
                </a>
                <a @click="toLogout(true)" class="block px-4 py-2 text-red-600 hover:bg-gray-100 cursor-pointer">
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
</template>

<script>
import loadingbar from './loadingbar.vue';
import { login_state } from './stores/login_state';
import { dekripsi } from './components/helper/crypt';
import toastService from './components/helper/toastService';
import Swal from 'sweetalert2';
export default {
  data() {
    return {
      isOpen: false
    }
  },
  components: {
    loadingbar
  },
  mounted() {
    this.getLocalStorage();
    setTimeout(() => {
      toggleSidebar();
    }, 200);

  },

  computed: {
    isLogedin() {
      return login_state.state.isLogin;
    },
    userData() {
      return login_state.state.userDetail;
    },
    userRole() {
      return login_state.state.userRole;
    },
    pathPage() {
      return pages_state.state.pathPage;
    },
    currentPage() {
      return pages_state.state.currentPage;
    },

    isExamPage() {
      return this.$route.name === 'exampage';
    },

    notifikasi() {
      return pelamar_notifikasi_state.state.notifikasi;
    },
  },

  methods: {
    getLocalStorage() {
      try {
        let userDetail = JSON.parse(
          dekripsi(localStorage.getItem("u"))
        );
        if (userDetail) {
          this.role = userDetail.roles;
          if (![3].some(r => this.role.includes(r))) {
            this.toLogout();
          }
          login_state.commit("setuserDetail", userDetail);
          login_state.commit("setuserRole", this.role);
        } else {
          this.toLogout();
        }
      } catch (error) {
        toastService.show("Error", "Token is not valid", "error");
        this.toLogout();
      }
    },

    async toLogout(confirm = false) {
      this.isOpen = false;
      if (confirm) {
        const result = await Swal.fire({
          title: 'Konfirmasi?',
          text: "Anda ingin logout!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#d33333', // Merah untuk Delete
          cancelButtonColor: '#3085d6', // Biru
          confirmButtonText: 'Ya, Logout!',
          cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
          try {
            const next = encodeURIComponent(window.location.pathname + window.location.search);
            localStorage.clear();
            window.location = `/`;
          } catch (error) {
            toastService.show("Error", error.message || "Gagal logout", "error");
          }
        }
      } else {
        localStorage.clear();
        window.location = `/`;
      }

    },
    goToProfile() {
      this.isOpen = false;
      this.$router.push("/me");
    }

  }
}
</script>