<script setup>
import AdminCrudPanel from '../../components/admin/AdminCrudPanel.vue'
import AdminField from '../../components/admin/AdminField.vue'
import AdminSectionForm from '../../components/admin/AdminSectionForm.vue'

const statsDefaults = {
  stats: [
    { value: '3', label: 'Proyek Full-Stack' },
    { value: 'XI', label: 'Kelas · RPL' },
  ],
}

const skillGroupFields = [
  {
    key: 'icon',
    label: 'Ikon',
    type: 'icon',
    placeholder: 'fa-solid fa-code',
    hint: 'Gunakan kelas Font Awesome yang sudah tersedia.',
  },
  {
    key: 'title',
    label: 'Nama kelompok',
    placeholder: 'Frontend & Full-Stack',
    required: true,
    full: true,
  },
  {
    key: 'items',
    label: 'Daftar keahlian',
    type: 'tags',
    placeholder: 'Laravel, Nuxt, MySQL',
    hint: 'Pisahkan setiap keahlian dengan koma.',
    full: true,
  },
  {
    key: 'sort_order',
    label: 'Urutan tampil',
    type: 'number',
    min: 0,
    hint: 'Angka kecil tampil lebih dulu.',
  },
  {
    key: 'is_active',
    label: 'Status kelompok',
    type: 'switch',
    hint: 'Nonaktifkan untuk menyembunyikan kelompok dari situs publik.',
  },
]
</script>

<template>
  <div class="space-y-6">
    <AdminSectionForm
      section-key="skills"
      title="Judul seksi keahlian"
      description="Perbarui judul, paragraf pengantar, dan dua statistik pendukung di samping daftar keahlian."
      kicker-label="Label seksi"
      title-label="Judul seksi"
      highlight-label="Kata sorotan judul"
      description-label="Paragraf pendukung"
      save-label="Simpan judul keahlian"
      :default-meta="statsDefaults"
    >
      <template #extra="{ meta }">
        <div>
          <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
              <h3 class="text-xs font-medium uppercase tracking-[0.14em] text-muted">
                Statistik pendukung
              </h3>
              <p class="mt-1 text-[11px] leading-5 text-muted/60">
                Dua kartu angka yang tampil di bawah deskripsi keahlian.
              </p>
            </div>

            <button
              v-if="meta.stats.length < 4"
              type="button"
              class="admin-btn admin-btn-secondary w-fit"
              @click="meta.stats.push({ value: '', label: '' })"
            >
              <i class="fa-solid fa-plus text-[9px]" aria-hidden="true"></i>
              Tambah statistik
            </button>
          </div>

          <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <div
              v-for="(stat, index) in meta.stats"
              :key="index"
              class="relative rounded-xl border border-white/[0.06] bg-canvas/40 p-3 pt-9"
            >
              <button
                v-if="meta.stats.length > 1"
                type="button"
                class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-lg text-muted/50 transition-colors hover:bg-red-400/[0.07] hover:text-red-300"
                :aria-label="`Hapus statistik ${index + 1}`"
                @click="meta.stats.splice(index, 1)"
              >
                <i class="fa-solid fa-xmark text-[9px]" aria-hidden="true"></i>
              </button>

              <div class="grid gap-2">
                <AdminField
                  v-model="stat.value"
                  :label="`Nilai ${index + 1}`"
                  placeholder="3"
                  required
                />
                <AdminField
                  v-model="stat.label"
                  :label="`Keterangan ${index + 1}`"
                  placeholder="Proyek Full-Stack"
                  required
                />
              </div>
            </div>
          </div>
        </div>
      </template>
    </AdminSectionForm>

    <AdminCrudPanel
      title="Kelompok keahlian"
      description="Atur kelompok teknologi, daftar kemampuan di dalamnya, serta urutan tampil."
      endpoint="/admin/skill-groups"
      :fields="skillGroupFields"
      item-label="kelompok keahlian"
      item-title-key="title"
      item-subtitle-key=""
      add-label="Tambah kelompok"
      empty-title="Belum ada kelompok keahlian"
      empty-description="Tambahkan kelompok pertama agar seksi Keahlian terisi di halaman publik."
      :initial-values="{ icon: 'fa-solid fa-code', sort_order: 0, is_active: true }"
    />
  </div>
</template>
