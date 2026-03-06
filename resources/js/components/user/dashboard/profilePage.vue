<template>
    <main class="bg-gray-50 min-h-screen p-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
                    <p class="text-sm text-gray-600">Data profil bersifat read-only. Anda hanya dapat mengganti
                        password.</p>
                </div>

                <button class="text-sm px-4 py-2 rounded-lg border border-gray-200 hover:bg-white disabled:opacity-60"
                    @click="fetchProfile" :disabled="loadingProfile">
                    {{ loadingProfile ? 'Loading...' : 'Refresh' }}
                </button>
            </div>

            <!-- Profile Card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-semibold">
                        {{ initials(user?.name) }}
                    </div>

                    <div class="min-w-0">
                        <div class="text-lg font-semibold text-gray-900 truncate">
                            {{ user?.name || '-' }}
                        </div>
                        <div class="text-sm text-gray-600">
                            NPP: <span class="font-medium text-gray-800">{{ user?.npp || user?.email || '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <ReadonlyField label="Nama" :value="user?.name" />
                    <ReadonlyField label="NPP" :value="user?.npp" />
                    <ReadonlyField label="Username" :value="user?.email" />
                    <ReadonlyField label="Kantor" :value="user?.kantor?.nama || user?.kantor_nama" />
                    <ReadonlyField label="Jabatan" :value="user?.jabatans?.nama" />
                    <ReadonlyField label="Roles" :value="formatRoles(user?.role_details)" />
                </div>
            </div>

            <!-- Change Password -->
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Ganti Password</h2>
                    <p class="text-sm text-gray-600">Minimal 8 karakter, kombinasi huruf besar-kecil dan angka.</p>
                </div>

                <form class="mt-5 space-y-4" @submit.prevent="submitPassword">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input v-model.trim="form.password" :type="showPass ? 'text' : 'password'"
                                autocomplete="new-password" placeholder="Contoh: Password123"
                                class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-200"
                                :class="errors.password ? 'border-red-300' : 'border-gray-200'" />
                            <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input v-model.trim="form.password_confirmation" :type="showPass ? 'text' : 'password'"
                                autocomplete="new-password" placeholder="Ulangi password baru"
                                class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-200"
                                :class="errors.password_confirmation ? 'border-red-300' : 'border-gray-200'" />
                            <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-600">
                                {{ errors.password_confirmation }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none"
                            v-if="form.password">
                            <input type="checkbox" v-model="showPass" class="rounded border-gray-300" />
                            Tampilkan password
                        </label>
                        <label v-else></label>

                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 disabled:opacity-60"
                            :disabled="submitting">
                            {{ submitting ? 'Menyimpan...' : 'Simpan Password' }}
                        </button>
                    </div>

                    <!-- Rules indicator -->
                    <div class="rounded-lg bg-white border border-gray-200 p-3  text-gray-700">
                        <div class="font-medium mb-1">Standar Password</div>
                        <ul class="list-disc pl-5 space-y-1 text-sm font-bold text-gray-600">
                            <li :class="ruleClass(hasMinLen)">Minimal 8 karakter</li>
                            <li :class="ruleClass(hasUpper)">Mengandung huruf besar (A-Z)</li>
                            <li :class="ruleClass(hasLower)">Mengandung huruf kecil (a-z)</li>
                            <li :class="ruleClass(hasNumber)">Mengandung angka (0-9)</li>
                            <li :class="ruleClass(hasSpecialChar)">Mengandung karakter khusus (!@#$%^&*)</li>
                            <li :class="ruleClass(matchesConfirm)">Konfirmasi sama</li>
                        </ul>
                    </div>

                    <p v-if="serverMessage" class="text-sm"
                        :class="serverType === 'success' ? 'text-emerald-700' : 'text-red-700'">
                        {{ serverMessage }}
                    </p>
                </form>
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
