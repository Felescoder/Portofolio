<script setup>
import { computed, onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { api } from '../../admin/lib/api'
import { useAuth } from '../../admin/lib/session'

const { state: auth } = useAuth()

const loading = ref(true)
const loadError = ref('')
const dashboard = ref(null)

const statCards = computed(() => [
  {
    key: 'projects',
    label: 'Projek',
    caption: 'Karya yang tampil',
    icon: 'fa-solid fa-diagram-project',
    to: '/admin/proyek',
  },
  {
    key: 'services',
    label: 'Layanan',
    caption: 'Solusi yang ditawarkan',
    icon: 'fa-solid fa-layer-group',
    to: '/admin/layanan',
  },
  {
    key: 'skill_groups',
    label: 'Kelompok Keahlian',
    caption: 'Stack teknologi',
    icon: 'fa-solid fa-code',
    to: '/admin/keahlian',
  },
  {
    key: 'experiences',
    label: 'Pengalaman',
    caption: 'Perjalanan & fondasi',
    icon: 'fa-solid fa-briefcase',
    to: '/admin/pengalaman',
  },
  {
    key: 'contact_details',
    label: 'Detail Kontak',
    caption: 'Informasi kontak',
    icon: 'fa-solid fa-address-card',
    to: '/admin/contact',
  },
  {
    key: 'contact_opportunities',
    label: 'Peluang',
    caption: 'Ruang kolaborasi',
    icon: 'fa-solid fa-handshake',
    to: '/admin/contact',
  },
])

const quickLinks = [
  { label: 'Perbarui profil & hero', to: '/admin/beranda', icon: 'fa-solid fa-house' },
  { label: 'Kelola layanan', to: '/admin/layanan', icon: 'fa-solid fa-layer-group' },
  { label: 'Atur keahlian', to: '/admin/keahlian', icon: 'fa-solid fa-code' },
  { label: 'Tambah pengalaman', to: '/admin/pengalaman', icon: 'fa-solid fa-briefcase' },
  { label: 'Kelola projek', to: '/admin/proyek', icon: 'fa-solid fa-diagram-project' },
  { label: 'Perbarui halaman kontak', to: '/admin/contact', icon: 'fa-solid fa-envelope' },
]

const firstName = computed(() => {
  const name = auth.user?.name || 'Admin'
  return name.split(/\s+/).filter(Boolean)[0] ?? 'Admin'
})

const lastUpdated = computed(() => formatDateTime(dashboard.value?.last_updated_at))

function formatDateTime(value) {
  if (!value) return 'Belum ada perubahan'

  try {
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    }).format(new Date(value))
  } catch {
    return 'Baru saja'
  }
}

async function loadDashboard() {
  loading.value = true
  loadError.value = ''

  try {
    const payload = await api.get('/admin/dashboard')
    dashboard.value = payload?.data ?? null
  } catch (error) {
    loadError.value = error.message || 'Dashboard tidak dapat dimuat.'
  } finally {
    loading.value = false
  }
}

onMounted(loadDashboard)
</script>

<template>
  <div class="space-y-6">
    <section
      class="relative overflow-hidden rounded-3xl border border-stroke/80 bg-panel/70 p-6 sm:p-8"
    >
      <div
        class="pointer-events-none absolute -right-24 -top-28 h-72 w-72 rounded-full bg-brand/[0.11] blur-3xl"
        aria-hidden="true"
      ></div>
      <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_85%_8%,rgb(217_45_32/0.08),transparent_34%)]"
        aria-hidden="true"
      ></div>

      <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
          <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-brand">
            Dashboard Admin
          </p>
          <h2 class="mt-4 text-balance text-3xl font-semibold tracking-[-0.055em] sm:text-4xl">
            Halo, {{ firstName }}.
          </h2>
          <p class="mt-3 max-w-xl text-sm leading-7 text-muted">
            Semua konten portofolio dapat diperbarui dari sini. Perubahan akan langsung dipakai
            halaman publik setelah tombol simpan ditekan.
          </p>

          <div class="mt-6 flex flex-wrap gap-2">
            <RouterLink to="/admin/beranda" class="admin-btn admin-btn-primary">
              <i class="fa-solid fa-pen-to-square text-[10px]" aria-hidden="true"></i>
              Kelola Beranda
            </RouterLink>
            <a
              href="/"
              target="_blank"
              rel="noreferrer"
              class="admin-btn admin-btn-secondary"
            >
              Lihat Situs
              <i class="fa-solid fa-arrow-up-right-from-square text-[10px]" aria-hidden="true"></i>
            </a>
          </div>
        </div>

        <div class="w-full max-w-xs rounded-2xl border border-white/[0.06] bg-canvas/45 p-4">
          <p class="text-[9px] uppercase tracking-[0.16em] text-muted/50">Pembaruan terakhir</p>
          <p class="mt-2 text-sm font-medium text-ink">{{ lastUpdated }}</p>
          <div class="mt-3 flex items-center gap-2 text-[10px] text-muted/60">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
            API Laravel terhubung
          </div>
        </div>
      </div>
    </section>

    <div v-if="loading" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
      <div v-for="index in 6" :key="index" class="admin-skeleton h-32"></div>
    </div>

    <div
      v-else-if="loadError"
      class="rounded-2xl border border-red-400/20 bg-red-400/[0.06] p-5 text-sm text-red-300"
    >
      <div class="flex items-start gap-3">
        <i class="fa-solid fa-triangle-exclamation mt-0.5" aria-hidden="true"></i>
        <div class="min-w-0 flex-1">
          <p class="font-medium">Dashboard tidak dapat dimuat.</p>
          <p class="mt-1 text-xs leading-5 text-red-300/80">{{ loadError }}</p>
        </div>
        <button type="button" class="admin-btn admin-btn-secondary" @click="loadDashboard">
          Coba lagi
        </button>
      </div>
    </div>

    <template v-else>
      <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <RouterLink
          v-for="card in statCards"
          :key="card.key"
          :to="card.to"
          class="group rounded-2xl border border-stroke/80 bg-panel/60 p-5 transition-colors hover:border-brand/30 hover:bg-panel"
        >
          <div class="flex items-start justify-between gap-4">
            <span
              class="flex h-10 w-10 items-center justify-center rounded-xl border border-brand/20 bg-brand/[0.08] text-[12px] text-brand transition-colors group-hover:bg-brand/[0.13]"
            >
              <i :class="card.icon" aria-hidden="true"></i>
            </span>
            <i
              class="fa-solid fa-arrow-up-right-from-square text-[9px] text-muted/30 transition-colors group-hover:text-brand"
              aria-hidden="true"
            ></i>
          </div>

          <p class="mt-5 text-3xl font-semibold tracking-[-0.05em] text-ink">
            {{ dashboard?.stats?.[card.key] ?? 0 }}
          </p>
          <p class="mt-1 text-xs font-medium text-ink/80">{{ card.label }}</p>
          <p class="mt-0.5 text-[10px] text-muted/55">{{ card.caption }}</p>
        </RouterLink>
      </section>

      <div class="grid gap-6 xl:grid-cols-[1.45fr_0.75fr]">
        <section class="admin-card">
          <header class="flex items-center justify-between gap-4">
            <div>
              <h2 class="text-sm font-medium text-ink">Projek terbaru</h2>
              <p class="mt-1 text-xs text-muted">Tiga projek yang terakhir diperbarui.</p>
            </div>
            <RouterLink
              to="/admin/proyek"
              class="text-[10px] uppercase tracking-[0.12em] text-brand transition-opacity hover:opacity-75"
            >
              Kelola semua
            </RouterLink>
          </header>

          <div
            v-if="dashboard?.recent_projects?.length"
            class="mt-5 divide-y divide-white/[0.05] border-t border-white/[0.06]"
          >
            <article
              v-for="project in dashboard.recent_projects"
              :key="project.id"
              class="flex items-center gap-3 py-4"
            >
              <img
                v-if="project.image_url"
                :src="project.image_url"
                :alt="project.image_alt || project.title"
                class="h-14 w-20 shrink-0 rounded-xl border border-white/[0.06] object-cover"
                loading="lazy"
              />
              <div
                v-else
                class="flex h-14 w-20 shrink-0 items-center justify-center rounded-xl border border-white/[0.06] bg-white/[0.02] text-muted/40"
              >
                <i class="fa-regular fa-image" aria-hidden="true"></i>
              </div>

              <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-medium text-ink">{{ project.title }}</p>
                <p class="mt-0.5 truncate text-[10px] text-muted/60">{{ project.category }}</p>
                <p class="mt-1 text-[9px] text-muted/40">
                  Diperbarui {{ formatDateTime(project.updated_at) }}
                </p>
              </div>

              <RouterLink
                to="/admin/proyek"
                class="admin-icon-button h-8 w-8 shrink-0"
                aria-label="Kelola projek"
              >
                <i class="fa-solid fa-pen text-[9px]" aria-hidden="true"></i>
              </RouterLink>
            </article>
          </div>

          <div v-else class="mt-5 rounded-2xl border border-dashed border-white/[0.08] p-8 text-center">
            <p class="text-xs text-muted">Belum ada projek. Tambahkan projek pertama Anda.</p>
          </div>
        </section>

        <section class="admin-card">
          <h2 class="text-sm font-medium text-ink">Akses cepat</h2>
          <p class="mt-1 text-xs text-muted">Langsung menuju seksi yang ingin diperbarui.</p>

          <div class="mt-4 space-y-1.5">
            <RouterLink
              v-for="link in quickLinks"
              :key="link.label"
              :to="link.to"
              class="group flex items-center gap-3 rounded-xl px-2.5 py-2.5 text-xs text-muted transition-colors hover:bg-white/[0.03] hover:text-ink"
            >
              <span
                class="flex h-7 w-7 items-center justify-center rounded-lg border border-white/[0.06] text-[9px] text-muted/70 transition-colors group-hover:border-brand/25 group-hover:text-brand"
              >
                <i :class="link.icon" aria-hidden="true"></i>
              </span>
              {{ link.label }}
              <i
                class="fa-solid fa-chevron-right ml-auto text-[8px] text-muted/30"
                aria-hidden="true"
              ></i>
            </RouterLink>
          </div>
        </section>
      </div>
    </template>
  </div>
</template>
