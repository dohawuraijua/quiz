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
          <nav class="p-4"><!-- Dashboard Menu -->
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
                <svg id="chevron-resources" class="w-6 h-4 chevron" fill="none" stroke="currentColor"
                  viewbox="0 0 24 24">
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
                <router-link v-if="hasRoles([1, 3])" to="/course/modul-ujian"
                  class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                  Category </router-link>
                <router-link v-if="hasRoles([1, 3])" to="/course/soal"
                  class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                  Exam Questions </router-link>
                <router-link v-if="hasRoles([1, 3])" to="/course/event"
                  class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                  Schedule Settings </router-link>
                <router-link v-if="hasRoles([1, 3])" to="/course/export"
                  class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm">
                  Export </router-link>

                <router-link to="/course/jadwal-aktif"
                  class="submenu-item flex items-center px-4 py-2 pl-14 text-white text-sm"> Exam Materials & Schedule
                </router-link>
              </div>
            </div>
            <div class="menu-item rounded-lg mb-2" v-if="hasRoles([1])">
              <button onclick="toggleSubmenu('settings')"
                class="w-full flex items-center justify-between px-4 py-3 text-white">
                <div class="flex items-center">
                  <svg class="w-6 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span class="font-medium">Settings</span>
                </div>
                <svg id="chevron-settings" class="w-6 h-4 chevron" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
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

            <div class="flex items-center space-x-4 ml-auto">
              <div class="relative">
                <button @click="isOpen = !isOpen" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                  <span class="hidden sm:inline font-medium">
                    {{ userData?.name }}
                  </span>
                  <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-100">
                    <img
                      :src="`https://storage.googleapis.com/public.simbankntt.com/photos/${userData?.pegawai_id}.jpeg` || 'https://via.placeholder.com/150'"
                      alt="" class="w-full h-full cover" />
                  </div>

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
    <div v-else>
      <LoginPage />
    </div>
  </section>
</template>


<script>
import loadingbar from './loadingbar.vue';
import { login_state } from './stores/login_state';
import { dekripsi } from './components/helper/crypt';
import toastService from './components/helper/toastService';
import Swal from 'sweetalert2';
import hasRole from './components/helper/hasRole';
import LoginPage from './components/LoginPage.vue';
export default {
  data() {
    return {
      isOpen: false
    }
  },
  components: {
    loadingbar, LoginPage
  },
  watch: {
    isLogedin(val) {
      setTimeout(() => {
        this.getLocalStorage();
      }, 2000)
    }
  },
  mounted() {
    // this.getLocalStorage();
    setTimeout(() => {
      try {
        // toggleSidebar();
        this.getLocalStorage();
      } catch (e) {
        console.error(e);
      }
    }, 200);
  },

  computed: {
    isLogedin() {
      return login_state.state.userDetail;
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
    hasRoles(rl) {
      return hasRole(rl);
    },
    getLocalStorage() {
      try {
        let userDetail = JSON.parse(
          dekripsi(localStorage.getItem("u"))
        );
        if (userDetail) {
          this.role = userDetail.roles;
          if (![1, 2, 3, 4].some(r => this.role.includes(r))) {
            this.toLogout();
          }
          login_state.commit("setuserDetail", userDetail);
          login_state.commit("setuserRole", this.role);
        } else {
          this.toLogout();
        }
      } catch (error) {
        console.log(error);

        this.toLogout();
      }
    },

    async toLogout(confirm = false) {
      this.isOpen = false;

      const performLogout = () => {
        localStorage.clear();
        login_state.commit("logout");
      };

      if (confirm) {
        const result = await Swal.fire({
          title: 'Konfirmasi?',
          text: "Anda ingin logout!",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#d33333',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Logout!',
          cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
          performLogout();
        }
      } else {
        performLogout();
      }
    },
    goToProfile() {
      this.isOpen = false;
      this.$router.push("/me");
    }

  }
}
</script>