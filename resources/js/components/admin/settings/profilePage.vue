<template>
    <main class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex justify-center px-4">

        <!-- Wrapper -->
        <div class="w-full max-w-5xl py-10">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
                    <p class="text-sm text-gray-500">Informasi detail pengguna</p>
                </div>


            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-10">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-center">

                    <!-- Avatar Section -->
                    <div class="flex flex-col items-center text-center">
                        <div v-if="user?.name"
                            class="w-38 h-38 rounded-full overflow-hidden ring-2 ring-blue-100 shadow">
                            <img :src="`https://storage.googleapis.com/public.simbankntt.com/photos/${user?.pegawai_id}.jpeg` || 'https://storage.googleapis.com/public.simbankntt.com/image/aiAgent.png'"
                                alt="" class="w-full h-full cover" />
                        </div>
                    </div>

                    <!-- Detail Section -->
                    <div class="md:col-span-2 w-full">
                        <div class="gap-y-6 gap-x-12 text-sm">

                            <div class="mb-3">
                                <p class="text-gray-500">Nama</p>
                                <p class="font-medium text-gray-900 break-words">
                                    {{ user?.name || user?.name || '-' }}
                                </p>
                            </div>


                            <div class="mb-3">
                                <p class="text-gray-500">NPP</p>
                                <p class="font-medium text-gray-900 break-words">
                                    {{ user?.npp || user?.email || '-' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <p class="text-gray-500">Jabatan</p>
                                <p class="font-medium text-gray-900 break-words">
                                    {{ user?.jabatans?.nama || '-' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <p class="text-gray-500">Kantor</p>
                                <p class="font-medium text-gray-900 break-words">
                                    {{ user?.kantor?.nama || '-' }}
                                </p>
                            </div>

                            <div class="mb-3 sm:col-span-2">
                                <p class="text-gray-500">Role</p>
                                <p class="font-medium text-gray-900 break-words">
                                    {{ formatRoles(user?.role_details) || '-' }}
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>
</template>

<script>
import RequestManager from '../../helper/RequestManager'
import toastService from '../../helper/toastService';

export default {
    name: 'ProfilePage',

    components: {
        ReadonlyField: {
            props: { label: String, value: [String, Number] },
            template: `
        <div class="rounded-lg border border-gray-200 p-3">
          <div class="text-xs text-gray-500 mb-1">{{ label }}</div>
          <div class="text-sm font-medium text-gray-900 break-words">{{ value ?? '-' }}</div>
        </div>
      `,
        },
    },

    data() {
        return {
            user: null,
            loadingProfile: false,

            showPass: false,
            submitting: false,

            form: {
                password: '',
                password_confirmation: '',
            },

            errors: {
                password: '',
                password_confirmation: '',
            },

            serverType: 'success',
            serverMessage: '',
        }
    },

    computed: {
        hasMinLen() {
            return (this.form.password || '').length >= 8
        },
        hasUpper() {
            return /[A-Z]/.test(this.form.password || '')
        },
        hasLower() {
            return /[a-z]/.test(this.form.password || '')
        },
        hasNumber() {
            return /[0-9]/.test(this.form.password || '')
        },
        hasSpecialChar() {
            // karakter khusus yang diizinkan
            return /[!@#$%^&*()_\-+=]/.test(this.form.password || '')
        },
        matchesConfirm() {
            return (this.form.password || '').length > 0 && this.form.password === this.form.password_confirmation
        },
    },

    mounted() {
        this.fetchProfile()
    },

    methods: {
        initials(name) {
            if (!name) return 'U'
            const parts = String(name).trim().split(/\s+/).slice(0, 2)
            return parts.map(p => (p[0] || '').toUpperCase()).join('') || 'U'
        },

        formatRoles(roles) {
            if (!roles || !Array.isArray(roles) || roles.length === 0) return '-'

            return roles
                .map(r => r?.nama_role)
                .filter(Boolean)
                .join(', ')
        },


        ruleClass(ok) {

            return this.form.password && !ok ? 'text-red-700' : ok ? 'text-green-700' : 'text-gray-600'
        },

        resetErrors() {
            this.errors.password = ''
            this.errors.password_confirmation = ''
            this.serverMessage = ''
        },

        validatePassword() {
            this.resetErrors()

            if (!this.form.password) {
                this.errors.password = 'Password wajib diisi.'
                return false
            }
            if (!this.hasMinLen) {
                this.errors.password = 'Password minimal 8 karakter.'
                return false
            }
            if (!this.hasLower || !this.hasUpper || !this.hasNumber || !this.hasSpecialChar) {
                this.errors.password =
                    'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.'
                return false
            }
            if (!this.form.password_confirmation) {
                this.errors.password_confirmation = 'Konfirmasi password wajib diisi.'
                return false
            }
            if (this.form.password !== this.form.password_confirmation) {
                this.errors.password_confirmation = 'Konfirmasi password tidak sesuai.'
                return false
            }
            return true
        },


        async fetchProfile() {
            this.loadingProfile = true
            try {
                const res = await RequestManager('_b/me', { action: 'view' })
                if (res.data.type === "success") {
                    this.user = res.data.data;
                } else {
                    toastService.show("", res.data.message, res.data.type);
                }

            } catch (e) {
                console.error(e)
                toastService.show("error", e.message, "error")
            } finally {
                this.loadingProfile = false
            }
        },

        async submitPassword() {
            if (!this.validatePassword()) return

            this.submitting = true
            this.serverMessage = ''
            try {
                const res = await RequestManager('_b/me', {
                    action: 'updatePassword',
                    password: this.form.password,
                    password_confirmation: this.form.password_confirmation,
                })
                if (res.data.type === 'success') {
                    this.form.password = ''
                    this.form.password_confirmation = ''
                    this.showPass = false
                    toastService.show("Pesan", res.data.message, res.data.type);
                    localStorage.clear();
                    setTimeout(() => {
                        window.location = "/";
                    }, 2000);


                } else {
                    toastService.show("", res.data.message, res.data.type);
                }
            } catch (e) {
                this.serverType = 'error'
                this.serverMessage =
                    e?.response?.data?.message ||
                    e?.message ||
                    'Terjadi kesalahan saat menyimpan password.'
            } finally {
                this.submitting = false
            }
        },
    },
}
</script>
