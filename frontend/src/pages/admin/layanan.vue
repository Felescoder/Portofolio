<script setup>
import AdminCrudPanel from '../../components/admin/AdminCrudPanel.vue'
import AdminSectionForm from '../../components/admin/AdminSectionForm.vue'

const serviceFields = [
  {
    key: 'number',
    label: 'Nomor tampilan',
    placeholder: '01',
    hint: 'Label kecil di kanan atas kartu layanan.',
  },
  {
    key: 'icon',
    label: 'Ikon',
    type: 'icon',
    placeholder: 'fa-solid fa-server',
    hint: 'Gunakan kelas Font Awesome yang sudah tersedia.',
  },
  {
    key: 'title',
    label: 'Nama layanan',
    placeholder: 'Rekayasa Backend',
    required: true,
    full: true,
  },
  {
    key: 'description',
    label: 'Deskripsi',
    type: 'textarea',
    rows: 4,
    placeholder: 'Jelaskan solusi dan nilai yang diberikan layanan ini.',
    required: true,
  },
  {
    key: 'tags',
    label: 'Teknologi terkait',
    type: 'tags',
    placeholder: 'Laravel, Node.js, Golang',
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
    label: 'Status layanan',
    type: 'switch',
    hint: 'Nonaktifkan untuk menyembunyikan layanan dari situs publik.',
  },
]
</script>

<template>
  <div class="space-y-6">
    <AdminSectionForm
      section-key="services"
      title="Judul seksi layanan"
      description="Atur label kecil, judul, dan paragraf pembuka pada seksi Layanan di beranda."
      kicker-label="Label seksi"
      title-label="Judul seksi"
      highlight-label="Kata sorotan judul"
      description-label="Paragraf pendukung"
      save-label="Simpan judul layanan"
    />

    <AdminCrudPanel
      title="Daftar layanan"
      description="Kelola kartu layanan beserta ikon, teknologi, urutan, dan status tampilnya."
      endpoint="/admin/services"
      :fields="serviceFields"
      item-label="layanan"
      item-title-key="title"
      item-subtitle-key="description"
      item-badge-key="number"
      add-label="Tambah layanan"
      empty-title="Belum ada layanan"
      empty-description="Tambahkan layanan pertama agar seksi Layanan terisi di halaman publik."
      :initial-values="{ icon: 'fa-solid fa-server', sort_order: 0, is_active: true }"
    />
  </div>
</template>
