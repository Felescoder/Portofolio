<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import AdminField from '../../components/admin/AdminField.vue'
import { api, ApiError } from '../../admin/lib/api'
import { notify } from '../../admin/lib/toast'

const loading = ref(true)
const saving = ref(false)
const loadError = ref('')
const formMessage = ref('')
const errors = ref({})
const imageFile = ref(null)
const imagePreview = ref('')
let previewObjectUrl = ''

const form = reactive({
  name: '',
  short_name: '',
  role: '',
  typed_role: '',
  hero_kicker: '',
  hero_greeting: '',
  hero_name_line_1: '',
  hero_name_line_2: '',
  hero_description: '',
  availability_label: '',
  location: '',
  school: '',
  major: '',
  email: '',
  hero_cta_label: '',
  footer_note: '',
  portrait_url: '',
})

const displayPortrait = computed(() => imagePreview.value || form.portrait_url)
const initials = computed(() => {
  const name = form.short_name || form.name || 'H'
  return name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part[0])
    .join('')
    .toUpperCase()
})

function errorFor(key) {
  return errors.value?.[key]?.[0] ?? ''
}

function setForm(data) {
  for (const key of Object.keys(form)) {
    form[key] = data?.[key] ?? ''
  }
}

async function loadProfile() {
  loading.value = true
  loadError.value = ''

  try {
    const payload = await api.get('/admin/profile')
    setForm(payload?.data ?? {})
  } catch (error) {
    loadError.value = error.message || 'Profil tidak dapat dimuat.'
  } finally {
    loading.value = false
  }
}

function clearPreviewObjectUrl() {
  if (previewObjectUrl) {
    URL.revokeObjectURL(previewObjectUrl)
    previewObjectUrl = ''
  }
}

function onPortraitChange(event) {
  const file = event.target.files?.[0] ?? null
  imageFile.value = file
  clearPreviewObjectUrl()

  if (file) {
    previewObjectUrl = URL.createObjectURL(file)
    imagePreview.value = previewObjectUrl
  } else {
    imagePreview.value = ''
  }
}

function buildFormData() {
  const data = new FormData()

  for (const key of [
    'name',
    'short_name',
    'role',
    'typed_role',
    'hero_kicker',
    'hero_greeting',
    'hero_name_line_1',
    'hero_name_line_2',
    'hero_description',
    'availability_label',
    'location',
    'school',
    'major',
    'email',
    'hero_cta_label',
    'footer_note',
  ]) {
    data.append(key, typeof form[key] === 'string' ? form[key].trim() : form[key])
  }

  if (imageFile.value) {
    data.append('portrait', imageFile.value)
  }

  return data
}

async function saveProfile() {
  if (saving.value) return

  saving.value = true
  errors.value = {}
  formMessage.value = ''

  try {
    const payload = await api.post('/admin/profile', buildFormData())

    setForm(payload?.data ?? {})
    imageFile.value = null
    imagePreview.value = ''
    clearPreviewObjectUrl()

    notify(payload?.message || 'Konten beranda berhasil diperbarui.', 'success')
  } catch (error) {
    if (error instanceof ApiError && error.status === 422) {
      errors.value = error.errors ?? {}
      formMessage.value = 'Periksa kembali field yang ditandai.'
    } else {
      formMessage.value = error.message || 'Perubahan tidak dapat disimpan.'
      notify(formMessage.value, 'error')
    }
  } finally {
    saving.value = false
  }
}

onMounted(loadProfile)
onBeforeUnmount(clearPreviewObjectUrl)
</script>

<template>
  <div class="space-y-6">
    <section class="admin-card">
      <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-start gap-3">
          <span
            class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-brand/20 bg-brand/[0.08] text-[11px] text-brand"
          >
            <i class="fa-solid fa-house" aria-hidden="true"></i>
          </span>
          <div>
            <h2 class="text-sm font-medium text-ink">Profil &amp; halaman beranda</h2>
            <p class="mt-1 max-w-2xl text-xs leading-5 text-muted">
              Identitas ini dipakai di hero, header, footer, dan metadata halaman publik.
            </p>
          </div>
        </div>

        <a
          href="/"
          target="_blank"
          rel="noreferrer"
          class="admin-btn admin-btn-secondary shrink-0"
        >
          Lihat beranda
          <i class="fa-solid fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
        </a>
      </header>
    </section>

    <div v-if="loading" class="grid gap-6 xl:grid-cols-[0.72fr_1.28fr]">
      <div class="admin-skeleton h-[28rem]"></div>
      <div class="admin-skeleton h-[46rem]"></div>
    </div>

    <div v-else-if="loadError" class="admin-card">
      <div class="admin-inline-error">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span>{{ loadError }}</span>
        <button type="button" class="admin-btn admin-btn-secondary ml-auto" @click="loadProfile">
          Coba lagi
        </button>
      </div>
    </div>

    <div v-else class="grid items-start gap-6 xl:grid-cols-[0.72fr_1.28fr]">
      <aside class="admin-card xl:sticky xl:top-28">
        <p class="text-[10px] uppercase tracking-[0.16em] text-muted/50">Pratinjau identitas</p>

        <div class="mt-6 flex flex-col items-center text-center">
          <div
            class="relative flex h-36 w-36 items-center justify-center overflow-hidden rounded-full border border-brand/30 bg-elevated shadow-[0_0_50px_-16px_rgb(217_45_32_/_0.5)]"
          >
            <img
              v-if="displayPortrait"
              :src="displayPortrait"
              :alt="`Foto profil ${form.name || 'admin'}`"
              class="h-full w-full object-cover"
            />
            <span v-else class="text-4xl font-semibold tracking-[-0.05em] text-brand">
              {{ initials }}
            </span>
          </div>

          <p class="mt-5 text-lg font-semibold tracking-[-0.04em] text-ink">
            {{ form.name || 'Nama lengkap' }}
          </p>
          <p class="mt-1 text-xs text-muted">{{ form.role || 'Peran profesional' }}</p>

          <span
            class="mt-4 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brand/[0.06] px-3 py-1.5 text-[9px] uppercase tracking-[0.14em] text-brand"
          >
            <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
            {{ form.availability_label || 'Status ketersediaan' }}
          </span>
        </div>

        <dl class="mt-7 space-y-3 border-t border-white/[0.06] pt-5 text-xs">
          <div class="flex items-start justify-between gap-4">
            <dt class="text-muted/60">Lokasi</dt>
            <dd class="text-right text-ink/80">{{ form.location || '—' }}</dd>
          </div>
          <div class="flex items-start justify-between gap-4">
            <dt class="text-muted/60">Email</dt>
            <dd class="max-w-[62%] break-all text-right text-ink/80">{{ form.email || '—' }}</dd>
          </div>
          <div class="flex items-start justify-between gap-4">
            <dt class="text-muted/60">Sekolah</dt>
            <dd class="max-w-[62%] text-right text-ink/80">{{ form.school || '—' }}</dd>
          </div>
          <div class="flex items-start justify-between gap-4">
            <dt class="text-muted/60">Jurusan</dt>
            <dd class="max-w-[62%] text-right text-ink/80">{{ form.major || '—' }}</dd>
          </div>
        </dl>

        <RouterLink
          to="/contact"
          class="mt-6 flex items-center justify-between rounded-xl border border-white/[0.06] bg-white/[0.02] px-3 py-2.5 text-[11px] text-muted transition-colors hover:border-brand/25 hover:text-ink"
        >
          Halaman kontak publik
          <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        </RouterLink>
      </aside>

      <form class="admin-card" @submit.prevent="saveProfile">
        <div class="space-y-6">
          <section>
            <h3 class="text-sm font-medium text-ink">Identitas utama</h3>
            <p class="mt-1 text-xs leading-5 text-muted">
              Dipakai pada branding header, footer, dan informasi dasar portofolio.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <AdminField
                v-model="form.name"
                label="Nama lengkap"
                placeholder="Muhammad Akbar Hafiz Zain"
                :error="errorFor('name')"
                required
              />
              <AdminField
                v-model="form.short_name"
                label="Nama pendek"
                placeholder="Hafiz"
                :error="errorFor('short_name')"
                hint="Tampil pada logo header dan footer."
                required
              />
              <AdminField
                v-model="form.role"
                label="Peran utama"
                placeholder="Software Engineer"
                :error="errorFor('role')"
                required
              />
              <AdminField
                v-model="form.typed_role"
                label="Peran animasi"
                placeholder="Backend & Full-Stack Architect"
                :error="errorFor('typed_role')"
                hint="Teks yang diketik di bawah nama pada hero."
                required
              />
              <AdminField
                v-model="form.email"
                label="Email publik"
                type="email"
                placeholder="nama@email.com"
                autocomplete="email"
                :error="errorFor('email')"
                required
              />
              <AdminField
                v-model="form.location"
                label="Lokasi"
                placeholder="Jakarta, Indonesia"
                :error="errorFor('location')"
                required
              />
            </div>
          </section>

          <section class="border-t border-white/[0.06] pt-6">
            <h3 class="text-sm font-medium text-ink">Hero beranda</h3>
            <p class="mt-1 text-xs leading-5 text-muted">
              Susun sapaan, nama besar, dan deskripsi utama di halaman pertama.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <AdminField
                v-model="form.hero_kicker"
                label="Label kecil hero"
                placeholder="Portofolio"
                :error="errorFor('hero_kicker')"
                required
              />
              <AdminField
                v-model="form.hero_greeting"
                label="Sapaan"
                placeholder="Halo, saya"
                :error="errorFor('hero_greeting')"
                required
              />
              <AdminField
                v-model="form.hero_name_line_1"
                label="Nama baris pertama"
                placeholder="Muhammad Akbar"
                :error="errorFor('hero_name_line_1')"
                required
              />
              <AdminField
                v-model="form.hero_name_line_2"
                label="Nama baris kedua"
                placeholder="Hafiz Zain"
                :error="errorFor('hero_name_line_2')"
                hint="Opsional."
              />
              <AdminField
                v-model="form.availability_label"
                label="Label ketersediaan"
                placeholder="Terbuka untuk PKL"
                :error="errorFor('availability_label')"
                required
              />
              <AdminField
                v-model="form.hero_cta_label"
                label="Label tombol kontak"
                placeholder="Hubungi Saya"
                :error="errorFor('hero_cta_label')"
                required
              />
              <AdminField
                v-model="form.hero_description"
                label="Deskripsi hero"
                textarea
                :rows="4"
                placeholder="Tulis ringkasan singkat tentang fokus dan nilai yang Anda tawarkan."
                :error="errorFor('hero_description')"
                class="sm:col-span-2"
                required
              />
            </div>
          </section>

          <section class="border-t border-white/[0.06] pt-6">
            <h3 class="text-sm font-medium text-ink">Detail pendukung</h3>
            <p class="mt-1 text-xs leading-5 text-muted">
              Informasi tambahan untuk halaman beranda dan footer.
            </p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
              <AdminField
                v-model="form.school"
                label="Sekolah"
                placeholder="SMK Negeri 71 Jakarta"
                :error="errorFor('school')"
              />
              <AdminField
                v-model="form.major"
                label="Jurusan"
                placeholder="Rekayasa Perangkat Lunak"
                :error="errorFor('major')"
              />
              <AdminField
                v-model="form.footer_note"
                label="Catatan footer"
                placeholder="Dibangun dengan Vue.js & Tailwind CSS."
                :error="errorFor('footer_note')"
                class="sm:col-span-2"
                hint="Muncul setelah nama pada footer publik."
              />
            </div>
          </section>

          <section class="border-t border-white/[0.06] pt-6">
            <h3 class="text-sm font-medium text-ink">Foto profil</h3>
            <p class="mt-1 text-xs leading-5 text-muted">
              Gunakan foto potret dengan rasio persegi agar hasil lingkaran tetap rapi.
            </p>

            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center">
              <div
                class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-white/[0.07] bg-canvas"
              >
                <img
                  v-if="displayPortrait"
                  :src="displayPortrait"
                  alt="Pratinjau foto profil"
                  class="h-full w-full object-cover"
                />
                <span v-else class="text-xl font-semibold text-brand">{{ initials }}</span>
              </div>

              <div class="min-w-0 flex-1">
                <input
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  class="block w-full cursor-pointer rounded-xl border border-stroke bg-canvas px-3 py-2.5 text-xs text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-white/[0.06] file:px-3 file:py-1.5 file:text-[10px] file:text-ink hover:file:bg-white/[0.09]"
                  @change="onPortraitChange"
                />
                <p class="mt-1.5 text-[11px] leading-5 text-muted/60">
                  JPG, PNG, atau WebP. Maks. 4 MB. Biarkan kosong untuk mempertahankan foto saat
                  ini.
                </p>
                <p v-if="errorFor('portrait')" class="mt-1 text-xs text-red-400">
                  {{ errorFor('portrait') }}
                </p>
              </div>
            </div>
          </section>
        </div>

        <div
          v-if="formMessage"
          class="mt-6 rounded-xl border border-red-400/20 bg-red-400/[0.06] px-3 py-2 text-xs leading-5 text-red-300"
          role="alert"
        >
          {{ formMessage }}
        </div>

        <div class="mt-6 flex flex-col-reverse gap-2 border-t border-white/[0.06] pt-5 sm:flex-row sm:justify-end">
          <a href="/" target="_blank" rel="noreferrer" class="admin-btn admin-btn-secondary">
            Pratinjau situs
          </a>
          <button type="submit" class="admin-btn admin-btn-primary" :disabled="saving">
            <i
              :class="saving ? 'fa-solid fa-circle-notch animate-spin' : 'fa-solid fa-floppy-disk'"
              class="text-[10px]"
              aria-hidden="true"
            ></i>
            {{ saving ? 'Menyimpan...' : 'Simpan beranda' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
