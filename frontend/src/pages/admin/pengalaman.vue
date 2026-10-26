<script setup>
import AdminCrudPanel from '../../components/admin/AdminCrudPanel.vue'
import AdminSectionForm from '../../components/admin/AdminSectionForm.vue'

const experienceFields = [
  {
    key: 'period',
    label: 'Periode',
    placeholder: 'Kelas XI · Sekarang',
    hint: 'Label waktu atau fase pengalaman.',
    required: true,
  },
  {
    key: 'icon',
    label: 'Ikon',
    type: 'icon',
    placeholder: 'fa-solid fa-briefcase',
    hint: 'Gunakan kelas Font Awesome yang sudah tersedia.',
  },
  {
    key: 'role',
    label: 'Peran atau pendidikan',
    placeholder: 'Pengembang Full-Stack Mandiri',
    required: true,
    full: true,
  },
  {
    key: 'company',
    label: 'Institusi / lokasi',
    placeholder: 'Jakarta, Indonesia',
    required: true,
    full: true,
  },
  {
    key: 'description',
    label: 'Deskripsi',
    type: 'textarea',
    rows: 4,
    placeholder: 'Ceritakan pengalaman, fokus, dan pencapaian utama.',
    required: true,
  },
  {
    key: 'tags',
    label: 'Tag terkait',
    type: 'tags',
    placeholder: 'Nuxt, Laravel, MySQL',
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
    label: 'Status pengalaman',
    type: 'switch',
    hint: 'Nonaktifkan untuk menyembunyikan pengalaman dari situs publik.',
  },
]
</script>

<template>
  <div class="space-y-6">
    <AdminSectionForm
      section-key="experience"
      title="Judul seksi pengalaman"
      description="Atur judul dan paragraf pembuka seksi Pengalaman &amp; Pendidikan di beranda."
      kicker-label="Label seksi"
      title-label="Judul seksi"
      highlight-label="Kata sorotan judul"
      description-label="Paragraf pendukung"
      save-label="Simpan judul pengalaman"
    />

    <AdminCrudPanel
      title="Daftar pengalaman"
      description="Kelola perjalanan pendidikan, proyek mandiri, dan fondasi lain yang relevan."
      endpoint="/admin/experiences"
      :fields="experienceFields"
      item-label="pengalaman"
      item-title-key="role"
      item-subtitle-key="description"
      item-badge-key="period"
      add-label="Tambah pengalaman"
      empty-title="Belum ada pengalaman"
      empty-description="Tambahkan pengalaman pertama agar seksi Pengalaman terisi di halaman publik."
      :initial-values="{ icon: 'fa-solid fa-briefcase', sort_order: 0, is_active: true }"
    />
  </div>
</template>
