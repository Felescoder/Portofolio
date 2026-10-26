<script setup>
import AdminCrudPanel from '../../components/admin/AdminCrudPanel.vue'
import AdminSectionForm from '../../components/admin/AdminSectionForm.vue'

const projectFields = [
  {
    key: 'title',
    label: 'Nama projek',
    placeholder: 'Matcha Muse',
    required: true,
    full: true,
  },
  {
    key: 'category',
    label: 'Kategori',
    placeholder: 'Toko Online Premium',
    required: true,
  },
  {
    key: 'url',
    label: 'URL projek',
    type: 'url',
    placeholder: 'https://example.com/nama-projek',
    hint: 'Tetap gunakan example.com sampai tautan asli tersedia.',
  },
  {
    key: 'description',
    label: 'Deskripsi',
    type: 'textarea',
    rows: 4,
    placeholder: 'Jelaskan fungsi, stack, dan nilai utama projek.',
    required: true,
  },
  {
    key: 'image',
    label: 'Gambar pratinjau',
    type: 'image',
    urlKey: 'image_url',
  },
  {
    key: 'image_alt',
    label: 'Teks alternatif gambar',
    placeholder: 'Tangkapan layar beranda Matcha Muse',
    hint: 'Penting untuk aksesibilitas dan SEO.',
    full: true,
  },
  {
    key: 'tags',
    label: 'Teknologi',
    type: 'tags',
    placeholder: 'Nuxt 4, Laravel 11, Tailwind CSS, MySQL',
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
    label: 'Status projek',
    type: 'switch',
    hint: 'Nonaktifkan untuk menyembunyikan projek dari situs publik.',
  },
]
</script>

<template>
  <div class="space-y-6">
    <AdminSectionForm
      section-key="projects"
      title="Judul seksi projek"
      description="Atur judul dan paragraf pengantar seksi Projek di halaman beranda."
      kicker-label="Label seksi"
      title-label="Judul seksi"
      highlight-label="Kata sorotan judul"
      description-label="Paragraf pendukung"
      save-label="Simpan judul projek"
    />

    <div
      class="flex items-start gap-3 rounded-2xl border border-amber-400/15 bg-amber-400/[0.05] px-4 py-3 text-[11px] leading-5 text-amber-200/90"
    >
      <i class="fa-solid fa-circle-info mt-0.5 text-amber-300" aria-hidden="true"></i>
      <p>
        Tautan projek masih memakai alamat dummy <span class="font-mono">example.com</span>.
        Perbarui kolom URL ketika tautan program yang sebenarnya sudah tersedia.
      </p>
    </div>

    <AdminCrudPanel
      title="Daftar projek"
      description="Kelola karya unggulan, gambar pratinjau, teknologi, tautan, dan status tampilnya."
      endpoint="/admin/projects"
      :fields="projectFields"
      item-label="projek"
      item-title-key="title"
      item-subtitle-key="description"
      item-badge-key="category"
      add-label="Tambah projek"
      empty-title="Belum ada projek"
      empty-description="Tambahkan projek pertama agar seksi Projek terisi di halaman publik."
      :initial-values="{ sort_order: 0, is_active: true }"
      modal-size="xl"
    />
  </div>
</template>
