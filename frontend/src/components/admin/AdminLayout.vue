<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuth } from '../../admin/lib/session'
import AdminToaster from './AdminToaster.vue'

const route = useRoute()
const router = useRouter()
const { state: auth, logout } = useAuth()

const sidebarOpen = ref(false)
const currentYear = new Date().getFullYear()

const navigation = [
  { label: 'Dashboard', to: '/admin/main', icon: 'fa-solid fa-gauge-high' },
  { label: 'Beranda', to: '/admin/beranda', icon: 'fa-solid fa-house' },
  { label: 'Layanan', to: '/admin/layanan', icon: 'fa-solid fa-layer-group' },
  { label: 'Keahlian', to: '/admin/keahlian', icon: 'fa-solid fa-code' },
  { label: 'Pengalaman', to: '/admin/pengalaman', icon: 'fa-solid fa-briefcase' },
  { label: 'Projek', to: '/admin/proyek', icon: 'fa-solid fa-diagram-project' },
  { label: 'Contact', to: '/admin/contact', icon: 'fa-solid fa-envelope' },
]

const iconOptions = [
  'fa-solid fa-server',
  'fa-solid fa-layer-group',
  'fa-solid fa-diagram-project',
  'fa-solid fa-code',
  'fa-solid fa-database',
  'fa-solid fa-pen-ruler',
  'fa-solid fa-briefcase',
  'fa-solid fa-people-group',
  'fa-solid fa-comments',
  'fa-solid fa-location-dot',
  'fa-solid fa-graduation-cap',
  'fa-solid fa-bullseye',
  'fa-solid fa-envelope',
  'fa-solid fa-globe',
  'fa-solid fa-link',
  'fa-solid fa-arrow-right',
  'fa-solid fa-language',
  'fa-solid fa-laptop-code',
  'fa-solid fa-terminal',
  'fa-solid fa-cloud',
  'fa-solid fa-shield-halved',
  'fa-solid fa-rocket',
  'fa-solid fa-star',
  'fa-solid fa-user',
  'fa-solid fa-phone',
  'fa-solid fa-paper-plane',
  'fa-brands fa-instagram',
  'fa-brands fa-facebook-f',
  'fa-brands fa-github',
  'fa-brands fa-linkedin-in',
  'fa-brands fa-whatsapp',
]

const isPanel = computed(() => Boolean(route.meta.requiresAuth))

const operatorInitials = computed(() => {
  const name = auth.user?.name || 'Admin Hafiz'
  return name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part[0])
    .join('')
    .toUpperCase()
})

function isActive(path) {
  return route.path === path
}

function closeSidebar() {
  sidebarOpen.value = false
}

function onKeydown(event) {
  if (event.key === 'Escape') closeSidebar()
}

async function handleLogout() {
  await logout()
  closeSidebar()
  await router.replace({ name: 'admin-login' })
}

onMounted(() => {
  document.addEventListener('keydown', onKeydown)
})

onBeforeUnmount(() => {
  document.removeEventListener('keydown', onKeydown)
})

watch(
  () => route.fullPath,
  () => closeSidebar(),
)
</script>

<template>
  <div v-if="!isPanel" class="min-h-screen bg-canvas text-ink">
    <slot />
    <AdminToaster />
  </div>

  <div v-else class="min-h-screen bg-canvas text-ink">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-black/65 backdrop-blur-[2px] lg:hidden"
      aria-hidden="true"
      @click="closeSidebar"
    ></div>

    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 flex w-[288px] flex-col border-r border-white/[0.06] bg-panel/95 transition-transform duration-200 lg:translate-x-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
      aria-label="Navigasi panel admin"
    >
      <div class="flex h-20 items-center justify-between border-b border-white/[0.06] px-5">
        <RouterLink to="/admin/main" class="flex items-center gap-3" @click="closeSidebar">
          <span
            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-brand text-sm font-semibold tracking-[-0.04em] text-white shadow-brand-glow"
          >
            H
          </span>
          <span>
            <span class="block text-base font-semibold leading-tight tracking-[-0.04em]">
              Hafiz<span class="text-brand">.</span>
            </span>
            <span class="block text-[9px] uppercase tracking-[0.2em] text-muted/70">
              Admin Panel
            </span>
          </span>
        </RouterLink>

        <button
          type="button"
          class="admin-icon-button lg:hidden"
          aria-label="Tutup menu"
          @click="closeSidebar"
        >
          <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
      </div>

      <nav class="flex-1 overflow-y-auto px-3 py-5">
        <p class="px-3 pb-3 text-[9px] uppercase tracking-[0.2em] text-muted/50">Konten</p>

        <div class="space-y-1">
          <RouterLink
            v-for="item in navigation"
            :key="item.to"
            :to="item.to"
            :aria-current="isActive(item.to) ? 'page' : undefined"
            :class="[
              'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors',
              isActive(item.to)
                ? 'bg-brand/[0.12] font-medium text-ink'
                : 'text-muted hover:bg-white/[0.035] hover:text-ink',
            ]"
            @click="closeSidebar"
          >
            <span
              :class="[
                'flex h-8 w-8 items-center justify-center rounded-lg border text-[11px] transition-colors',
                isActive(item.to)
                  ? 'border-brand/30 bg-brand/10 text-brand'
                  : 'border-white/[0.06] bg-white/[0.02] text-muted group-hover:text-ink',
              ]"
            >
              <i :class="item.icon" aria-hidden="true"></i>
            </span>
            {{ item.label }}
            <i
              v-if="isActive(item.to)"
              class="fa-solid fa-chevron-right ml-auto text-[8px] text-brand/70"
              aria-hidden="true"
            ></i>
          </RouterLink>
        </div>
      </nav>

      <div class="space-y-3 border-t border-white/[0.06] p-3">
        <a
          href="/"
          target="_blank"
          rel="noreferrer"
          class="flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] px-3 py-2.5 text-xs text-muted transition-colors hover:border-brand/25 hover:text-ink"
        >
          <span class="flex items-center gap-2.5">
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
            Lihat situs publik
          </span>
          <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        </a>

        <div class="rounded-2xl border border-white/[0.06] bg-canvas/50 p-3">
          <div class="flex items-center gap-3">
            <span
              class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-brand/25 bg-brand/10 text-[11px] font-semibold text-brand"
            >
              {{ operatorInitials }}
            </span>
            <div class="min-w-0 flex-1">
              <p class="truncate text-xs font-medium text-ink">
                {{ auth.user?.name || 'Admin Hafiz' }}
              </p>
              <p class="truncate text-[10px] text-muted/60">
                {{ auth.user?.email || 'Sesi admin' }}
              </p>
            </div>
          </div>

          <button
            type="button"
            class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg border border-white/[0.06] px-3 py-2 text-[11px] font-medium text-muted transition-colors hover:border-red-400/25 hover:text-red-300"
            @click="handleLogout"
          >
            <i class="fa-solid fa-arrow-right-from-bracket text-[10px]" aria-hidden="true"></i>
            Keluar
          </button>
        </div>
      </div>
    </aside>

    <div class="min-h-screen lg:pl-[288px]">
      <header
        class="sticky top-0 z-30 flex h-20 items-center justify-between gap-4 border-b border-white/[0.06] bg-canvas/85 px-4 backdrop-blur-xl sm:px-6 lg:px-8"
      >
        <div class="flex min-w-0 items-center gap-3">
          <button
            type="button"
            class="admin-icon-button lg:hidden"
            aria-label="Buka menu navigasi"
            @click="sidebarOpen = true"
          >
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
          </button>

          <div class="min-w-0">
            <p class="hidden text-[9px] uppercase tracking-[0.2em] text-muted/50 sm:block">
              Panel Admin
            </p>
            <h1 class="truncate text-base font-semibold tracking-[-0.03em] sm:text-lg">
              {{ route.meta.title || 'Dashboard' }}
            </h1>
          </div>
        </div>

        <div class="flex shrink-0 items-center gap-2">
          <span
            class="hidden items-center gap-2 rounded-full border border-emerald-400/15 bg-emerald-400/[0.06] px-3 py-1.5 text-[10px] text-emerald-300 sm:flex"
          >
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
            Admin aktif
          </span>

          <a
            href="/"
            target="_blank"
            rel="noreferrer"
            class="admin-icon-button"
            title="Lihat situs publik"
            aria-label="Lihat situs publik di tab baru"
          >
            <i class="fa-solid fa-arrow-up-right-from-square text-[11px]" aria-hidden="true"></i>
          </a>
        </div>
      </header>

      <main id="admin-main" class="mx-auto max-w-[1440px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <p
          v-if="route.meta.description"
          class="mb-6 max-w-3xl text-sm leading-6 text-muted sm:-mt-1"
        >
          {{ route.meta.description }}
        </p>

        <div
          v-if="auth.error"
          class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-400/20 bg-amber-400/[0.06] px-4 py-3 text-xs leading-5 text-amber-200"
          role="alert"
        >
          <i class="fa-solid fa-triangle-exclamation mt-0.5 text-amber-300" aria-hidden="true"></i>
          <span>{{ auth.error }} Periksa koneksi backend lalu muat ulang halaman.</span>
        </div>

        <slot />
      </main>

      <footer class="border-t border-white/[0.05] px-4 py-5 sm:px-6 lg:px-8">
        <p class="text-[10px] uppercase tracking-[0.14em] text-muted/40">
          © {{ currentYear }} Hafiz Admin Panel · Konten publik diperbarui setelah disimpan.
        </p>
      </footer>
    </div>

    <AdminToaster />

    <datalist id="admin-icon-options">
      <option v-for="icon in iconOptions" :key="icon" :value="icon"></option>
    </datalist>
  </div>
</template>
