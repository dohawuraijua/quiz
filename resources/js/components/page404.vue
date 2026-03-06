<template>

  <body class="h-full">
    <div id="app" class="h-full w-full">
      <div class="login-wrapper w-full h-full flex items-center justify-center p-4 relative overflow-auto"
        :style="wrapperStyle">
        <h1>404</h1>
        <h3 class="w-full">Halaman tidak ditemukan</h3>
        <router-link to="/" class="text-white underline">Kembali ke Beranda</router-link>
      </div>
    </div>
  </body>
</template>
<script>
import toastService from './helper/toastService';
import RequestManager from './helper/RequestManager';
import { dekripsi } from './helper/crypt';
import { login_state } from '../stores/login_state';


const defaultConfig = {
  background_gradient_start: "#667eea",
  background_gradient_end: "#764ba2",
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
      redirectPath: this.$route.query || '/'

    };
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
          } else {

          }
          window.location = "" + this.redirectPath;
          // this.$router.push(this.redirectPath)

        }
        else {
          toastService.show("Gagal", response.data.message, 'error');
        }
      } catch (error) {
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
    }
  },
  mounted() {
    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange: this.onConfigChange,
        mapToCapabilities: this.mapToCapabilities,
        mapToEditPanelValues: this.mapToEditPanelValues
      });
    }
  }
}
</script>
