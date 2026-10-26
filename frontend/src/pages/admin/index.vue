<script setup>
import { computed, reactive, ref } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import AdminField from '../../components/admin/AdminField.vue'
import { useAuth } from '../../admin/lib/session'

const route = useRoute()
const router = useRouter()
const { state, login } = useAuth()

const form = reactive({
  email: '',
  password: '',
})

const showPassword = ref(false)
const errors = ref({})
const message = ref(
  route.query.expired
    ? 'Sesi admin sebelumnya telah berakhir. Silakan login kembali.'
    : '',
)

const isSubmitting = computed(() => state.loading)
const passwordError = computed(() => errors.value?.password?.[0] ?? '')
const emailError = computed(() => errors.value?.email?.[0] ?? '')

async function submit() {
  errors.value = {}
  message.value = ''

  try {
    await login(form.email.trim(), form.password)

    const redirect =
      typeof route.query.redirect === 'string' && route.query.redirect.startsWith('/admin')
        ? route.query.redirect
        : '/admin/main'

    await router.replace(redirect)
  } catch (error) {
    errors.value = error.errors ?? {}
    message.value = error.message || 'Login tidak dapat diproses.'
  }
}
</script>

<template>
  <main class="relative min-h-screen overflow-hidden">
    <div
      class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_10%,rgb(217_45_32/0.15),transparent_38%),radial-gradient(circle_at_82%_88%,rgb(217_45_32/0.08),transparent_42%)]"
      aria-hidden="true"
    ></div>

    <div class="relative mx-auto grid min-h-screen max-w-[1440px] lg:grid-cols-[1.05fr_0.95fr]">
      <section
        class="relative hidden flex-col justify-between overflow-hidden border-r border-white/[0.05] px-12 py-10 lg:flex xl:px-16"
      >
        <div class="flex items-center gap-3">
          <span
            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand text-sm font-semibold text-white shadow-brand-glow"
          >
            H
          </span>
          <div>
            <p class="text-base font-semibold leading-tight tracking-[-0.04em]">
              Hafiz<span class="text-brand">.</span>
            </p>
            <p class="text-[9px] uppercase tracking-[0.2em] text-muted/60">Portofolio Admin</p>
          </div>
        </div>

        <div class="max-w-xl py-16">
          <p class="font-mono text-[10px] uppercase tracking-[0.28em] text-brand">
            Kendali Konten
          </p>
          <h1 class="mt-5 text-balance text-4xl font-semibold leading-[1.08] tracking-[-0.055em] xl:text-5xl">
            Kelola portofolio dari satu panel yang terarah.
          </h1>
          <p class="mt-5 max-w-lg text-pretty text-sm leading-7 text-muted">
            Perbarui profil, layanan, keahlian, pengalaman, projek, dan halaman kontak. Semua
            perubahan tersimpan ke API Laravel dan langsung dipakai oleh situs publik.
          </p>

          <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
              <i class="fa-solid fa-shield-halved text-xs text-brand" aria-hidden="true"></i>
              <p class="mt-2 text-xs font-medium text-ink">Admin Tunggal</p>
              <p class="mt-1 text-[11px] leading-5 text-muted/70">
                Akses berbasis token Sanctum tanpa alur registrasi publik.
              </p>
            </div>

            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.02] p-4">
              <i class="fa-solid fa-rotate text-xs text-brand" aria-hidden="true"></i>
              <p class="mt-2 text-xs font-medium text-ink">Decoupled API</p>
              <p class="mt-1 text-[11px] leading-5 text-muted/70">
                Frontend Vue dan backend Laravel berjalan terpisah.
              </p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between border-t border-white/[0.06] pt-6 text-[10px] text-muted/40">
          <span>© {{ new Date().getFullYear() }} Muhammad Akbar Hafiz Zain</span>
          <span class="font-mono uppercase tracking-[0.15em]">Laravel · MySQL · Vue</span>
        </div>
      </section>

      <section class="flex items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
        <div class="w-full max-w-[430px]">
          <RouterLink
            to="/"
            class="mb-8 inline-flex items-center gap-2 text-xs text-muted transition-colors hover:text-ink lg:hidden"
          >
            <i class="fa-solid fa-arrow-left text-[10px]" aria-hidden="true"></i>
            Kembali ke portofolio
          </RouterLink>

          <div class="flex items-center gap-3 lg:hidden">
            <span
              class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand text-sm font-semibold text-white shadow-brand-glow"
            >
              H
            </span>
            <div>
              <p class="text-base font-semibold leading-tight tracking-[-0.04em]">
                Hafiz<span class="text-brand">.</span>
              </p>
              <p class="text-[9px] uppercase tracking-[0.2em] text-muted/60">Portofolio Admin</p>
            </div>
          </div>

          <div class="mt-8 lg:mt-0">
            <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-brand">Panel Admin</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-[-0.05em] sm:text-4xl">
              Selamat datang kembali.
            </h2>
            <p class="mt-3 text-sm leading-6 text-muted">
              Masuk dengan akun admin tunggal untuk mulai mengelola konten portofolio.
            </p>
          </div>

          <div
            v-if="message"
            class="mt-6 flex items-start gap-3 rounded-2xl border border-amber-400/20 bg-amber-400/[0.06] px-4 py-3 text-xs leading-5 text-amber-200"
            role="alert"
          >
            <i class="fa-solid fa-circle-info mt-0.5 text-amber-300" aria-hidden="true"></i>
            <span>{{ message }}</span>
          </div>

          <form class="mt-8 space-y-5" @submit.prevent="submit">
            <AdminField
              v-model="form.email"
              label="Email admin"
              type="email"
              placeholder="nama@email.com"
              autocomplete="username"
              :error="emailError"
              required
            >
            </AdminField>

            <label class="block">
              <span class="admin-label">
                Password <span class="text-brand" aria-hidden="true">*</span>
              </span>

              <span class="relative block">
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  autocomplete="current-password"
                  placeholder="Masukkan password admin"
                  required
                  :aria-invalid="passwordError ? 'true' : 'false'"
                  class="admin-input pr-12"
                />
                <button
                  type="button"
                  class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-muted transition-colors hover:text-ink"
                  :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                  @click="showPassword = !showPassword"
                >
                  <i
                    :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"
                    class="text-xs"
                    aria-hidden="true"
                  ></i>
                </button>
              </span>

              <span v-if="passwordError" class="mt-1.5 block text-xs text-red-400">
                {{ passwordError }}
              </span>
            </label>

            <button
              type="submit"
              class="admin-btn admin-btn-primary w-full py-3 text-sm"
              :disabled="isSubmitting"
            >
              <i
                :class="isSubmitting ? 'fa-solid fa-circle-notch animate-spin' : 'fa-solid fa-arrow-right-to-bracket'"
                class="text-[11px]"
                aria-hidden="true"
              ></i>
              {{ isSubmitting ? 'Memproses login...' : 'Masuk ke panel admin' }}
            </button>
          </form>

          <div class="mt-6 flex items-center justify-between gap-4 text-[11px] text-muted/60">
            <span>Hanya tersedia satu akun admin.</span>
            <RouterLink to="/" class="inline-flex items-center gap-1.5 transition-colors hover:text-ink">
              Lihat situs
              <i class="fa-solid fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
            </RouterLink>
          </div>
        </div>
      </section>
    </div>
  </main>
</template>
