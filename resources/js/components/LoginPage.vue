<template>

  <body class="h-full">
    <div id="app" class="h-full w-full">
      <div class="login-wrapper w-full h-full flex items-center justify-center p-4 relative overflow-auto"
        :style="wrapperStyle">
        <!-- <div class="wave"></div> -->
        <div class="glass-effect rounded-2xl p-8 w-full max-w-md relative z-10" :style="cardStyle">
          <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center shadow-lg"
              :style="iconBgStyle">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.31 0-6 2.69-6 6h12c0-3.31-2.69-6-6-6z" />
              </svg>
            </div>

            <h1 class="text-3xl font-bold mb-2" :style="titleStyle">
              {{ pageTitle }}
            </h1>


          </div>

          <form @submit.prevent="handleSubmit" class="space-y-6">
            <section v-if="useLoginForm">
              <div><label for="email" class="block text-sm font-medium mb-2" :style="labelStyle"> {{ emailLabel }}
                </label> <input type="text" id="emails" v-model="email" required
                  class="input-focus w-full px-4 py-3 rounded-lg border-2 border-blue-200 focus:border-blue-500 focus:outline-none transition-all duration-200"
                  placeholder="nama@email.com">
              </div>
              <div><label for="password" class="block text-sm font-medium mb-2 mt-3" :style="labelStyle"> {{
                passwordLabel }}
                </label> <input type="password" id="password" v-model="password" required
                  class="input-focus w-full px-4 py-3 rounded-lg border-2 border-blue-200 focus:border-blue-500 focus:outline-none transition-all duration-200"
                  placeholder="••••••••">
              </div>

              <div class="w-full mt-4 flex items-center justify-center">

                <LoadingButton :width="20" :height="20" v-if="loadingLogin" />

                <button type="submit" v-else
                  class="btn-hover w-full py-3 rounded-lg text-white font-semibold transition-all duration-200"
                  :style="buttonStyle"> {{ buttonText }} </button>

              </div>

              <div class="relative w-full my-6">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center">
                  <span class="bg-white px-4 text-sm text-gray-500">
                    atau
                  </span>
                </div>
              </div>


            </section>
            <div class=" w-full text-gray-600 flex items-center text-center justify-center">
              Gunakan akun SIM untuk login cepat dan terintegrasi.
            </div>
            <div class=" w-full  flex items-center justify-center">
              <LoadingButton :width="20" :height="20" v-if="loadingLoginSSO" />
              <a @click="loginDenganSIM" v-else class="w-full block py-3 text-center url
         bg-blue-500 
         rounded-lg 
         text-white 
         font-semibold
         transition-all duration-300 ease-out
         transform
         hover:bg-blue-600
         hover:-translate-y-1
         hover:scale-[1.02]
         hover:shadow-xl
         active:scale-95
         active:translate-y-0">
                Login dengan SIM
              </a>
            </div>


            <div class=" w-full  flex items-center justify-center text-gray-400 mt-3">
              v{{ appVersion.version }}
            </div>

            <a @click="useFormLoginForm" accesskey="l">
            </a>
          </form>
        </div>
      </div>
    </div>

  </body>
</template>
<script>
import toastService from './helper/toastService';
import RequestManager from './helper/RequestManager';
import { dekripsi } from './helper/crypt';
import { login_state } from '../stores/login_state';
import Loading from './global/Loading.vue';
import LoadingButton from './global/LoadingButton.vue';
import packageInfo from '../../../package.json';


const defaultConfig = {
  background_gradient_start: "#667eea",
  background_gradient_end: "#331da6",
  card_background: "#ffffff",
  primary_color: "#3b82f6",
  text_color: "#1e40af",
  page_title: "Selamat Datang",
  welcome_message: "Silakan masuk ke akun Anda",
  email_label: "Email",
  password_label: "Kata Sandi",
  button_text: "Masuk",
  forgot_password_text: "Lupa kata sandi?",
  font_family: "Segoe UI, Tahoma, Geneva, Verdana, sans-serif",
  font_size: 16
};

export default {
  data() {
    return {
      email: '',
      password: '',
      rememberMe: false,
      showSuccess: false,
      config: { ...defaultConfig },
      loadingLogin: false,
      loadingLoginSSO: false,
      appVersion: packageInfo,
      useLoginForm: false,
      redirectPath: '/'
    };
  },
  components: {
    Loading,
    LoadingButton
  },
  computed: {
    baseFontSize() {
      return this.config.font_size || defaultConfig.font_size;
    },
    fontFamily() {
      const customFont = this.config.font_family || defaultConfig.font_family;
      return `${customFont}, Tahoma, Geneva, Verdana, sans-serif`;
    },
    wrapperStyle() {
      return {
        background: `linear-gradient(135deg, ${this.config.background_gradient_start || defaultConfig.background_gradient_start} 0%, ${this.config.background_gradient_end || defaultConfig.background_gradient_end} 100%)`,
        fontFamily: this.fontFamily
      };
    },
    cardStyle() {
      return {
        background: this.config.card_background || defaultConfig.card_background
      };
    },
    iconBgStyle() {
      const primaryColor = this.config.primary_color || defaultConfig.primary_color;
      return {
        background: `linear-gradient(135deg, ${primaryColor} 0%, ${primaryColor} 100%)`
      };
    },
    titleStyle() {
      return {
        color: this.config.text_color || defaultConfig.text_color,
        fontSize: `${this.baseFontSize * 1.875}px`
      };
    },
    labelStyle() {
      return {
        color: this.config.text_color || defaultConfig.text_color,
        fontSize: `${this.baseFontSize * 0.875}px`
      };
    },
    buttonStyle() {
      const primaryColor = this.config.primary_color || defaultConfig.primary_color;
      return {
        background: `linear-gradient(135deg, ${primaryColor} 0%, ${primaryColor} 100%)`,
        fontSize: `${this.baseFontSize}px`
      };
    },
    pageTitle() {
      return this.config.page_title || defaultConfig.page_title;
    },
    welcomeMessage() {
      return this.config.welcome_message || defaultConfig.welcome_message;
    },
    emailLabel() {
      return this.config.email_label || defaultConfig.email_label;
    },
    passwordLabel() {
      return this.config.password_label || defaultConfig.password_label;
    },
    buttonText() {
      return this.config.button_text || defaultConfig.button_text;
    },
    forgotPasswordText() {
      return this.config.forgot_password_text || defaultConfig.forgot_password_text;
    }
  },

  methods: {

    useFormLoginForm() {
      this.useLoginForm = !this.useLoginForm;
    },
    async handleSubmit() {


      if (!this.email || !this.password) {
        toastService.show("Perhatian", ' Mohon isi email dan kata sandi Anda.', 'warning');
        return;
      }

      try {
        let parameters = {
          action: "login",
          username: this.email,
          password: this.password,
          remember_me: this.rememberMe
        };

        this.loadingLogin = true;
        const response = await RequestManager('/login', parameters);

        if (response.data.type === "success") {
          let te = response.data.data.token;
          let uu = response.data.data.user;
          localStorage.setItem('t', te);
          localStorage.setItem('u', uu);

          let userDetail = JSON.parse(
            dekripsi(uu)
          );
          if (userDetail) {
            let role = userDetail.role_akun;
            login_state.commit("setuserDetail", userDetail);
            login_state.commit("setuserRole", role);
          }
          this.loadingLogin = false;
        }
        else {
          this.loadingLogin = false;
          toastService.show("Gagal", response.data.message, 'error');
        }
      } catch (error) {
        this.loadingLogin = false;
        console.log(error);
        toastService.show("Error", ' Terjadi kesalahan saat memproses permintaan Anda.', 'error');

      }


    },
    async onConfigChange(newConfig) {
      this.config = { ...this.config, ...newConfig };
    },
    mapToCapabilities(cfg) {
      return {
        recolorables: [
          {
            get: () => cfg.background_gradient_start || defaultConfig.background_gradient_start,
            set: (value) => {
              cfg.background_gradient_start = value;
              window.elementSdk.setConfig({ background_gradient_start: value });
            }
          },
          {
            get: () => cfg.card_background || defaultConfig.card_background,
            set: (value) => {
              cfg.card_background = value;
              window.elementSdk.setConfig({ card_background: value });
            }
          },
          {
            get: () => cfg.text_color || defaultConfig.text_color,
            set: (value) => {
              cfg.text_color = value;
              window.elementSdk.setConfig({ text_color: value });
            }
          },
          {
            get: () => cfg.primary_color || defaultConfig.primary_color,
            set: (value) => {
              cfg.primary_color = value;
              window.elementSdk.setConfig({ primary_color: value });
            }
          }
        ],
        borderables: [],
        fontEditable: {
          get: () => cfg.font_family || defaultConfig.font_family,
          set: (value) => {
            cfg.font_family = value;
            window.elementSdk.setConfig({ font_family: value });
          }
        },
        fontSizeable: {
          get: () => cfg.font_size || defaultConfig.font_size,
          set: (value) => {
            cfg.font_size = value;
            window.elementSdk.setConfig({ font_size: value });
          }
        }
      };
    },
    mapToEditPanelValues(cfg) {
      return new Map([
        ["page_title", cfg.page_title || defaultConfig.page_title],
        ["welcome_message", cfg.welcome_message || defaultConfig.welcome_message],
        ["email_label", cfg.email_label || defaultConfig.email_label],
        ["password_label", cfg.password_label || defaultConfig.password_label],
        ["button_text", cfg.button_text || defaultConfig.button_text],
        ["forgot_password_text", cfg.forgot_password_text || defaultConfig.forgot_password_text]
      ]);
    },


    loginDenganSIM() {
      let todirect = this.$route.path;
      if (todirect) {
        localStorage.setItem("direct", todirect);
      }
      this.loadingLoginSSO = true;
      const client_id = "a11b2be3-2c42-4a12-b9db-5a8496de6585";
      const redirect_uri = encodeURIComponent("https://elearning.simbankntt.com/callback");
      const response_type = "code";
      const scope = "*";
      const state = crypto.randomUUID()

      let urlToOpen = `https://auth.simbankntt.com/oauth/authorize?client_id=${client_id}&redirect_uri=${redirect_uri}&response_type=${response_type}&scope=${scope}&state=${state}`;
      this.loadingLoginSSO = false;
      window.location = urlToOpen;
    },


    async getSimSSOParameter() {
      const ssoCode = this.$route.query.code;
      if (ssoCode && ssoCode.length > 30) {
        let direct = localStorage.getItem("direct") ?? null;
        try {
          let parameters = {
            action: "loginWithSim",
            code: ssoCode,
            state: crypto.randomUUID()
          };
          this.loadingLoginSSO = true;
          const response = await RequestManager('/login', parameters);

          if (response.data.type === "success") {
            let te = response.data.data.token;
            let uu = response.data.data.user;

            localStorage.setItem('t', te);
            localStorage.setItem('u', uu);

            let userDetail = JSON.parse(
              dekripsi(uu)
            );
            if (userDetail) {
              let role = userDetail.role_akun;
              login_state.commit("setuserDetail", userDetail);
              login_state.commit("setuserRole", role);
            }

            if (direct) {
              this.$router.push(direct);
            } else {
              this.$router.push("/");
            }
            // setTimeout(() => {

            // }, 2000)


            this.loadingLoginSSO = false;
          }
          else {
            this.loadingLoginSSO = false;
            toastService.show("Gagal", response.data.message, 'error');
          }
        } catch (err) {
          this.loadingLoginSSO = false;
          console.log(err);
        }

      }
    }
  },
  mounted() {
    setTimeout(() => {
      this.getSimSSOParameter();
    }, 1000)

  }
}
</script>

<style scoped>
body {
  box-sizing: border-box;
  height: 100vh;
}

.login-wrapper {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.glass-effect {
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}

.input-focus:focus {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-hover:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
}

.wave {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100px;
  background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom;
  background-size: cover;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>