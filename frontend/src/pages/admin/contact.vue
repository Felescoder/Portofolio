<script setup>
import AdminCrudPanel from '../../components/admin/AdminCrudPanel.vue'
import AdminSectionForm from '../../components/admin/AdminSectionForm.vue'

const socialLinkFields = [
  {
    key: 'label',
    label: 'Nama saluran',
    placeholder: 'Instagram',
    required: true,
  },
  {
    key: 'value',
    label: 'Nilai yang tampil',
    placeholder: '@imhafiiiiz',
    required: true,
  },
  {
    key: 'href',
    label: 'Tautan',
    placeholder: 'https://instagram.com/... atau mailto:...',
    hint: 'Gunakan URL lengkap untuk web atau mailto: untuk email.',
    required: true,
    full: true,
  },
  {
    key: 'icon',
    label: 'Ikon',
    type: 'icon',
    placeholder: 'fa-brands fa-instagram',
    hint: 'Gunakan kelas Font Awesome yang sudah tersedia.',
  },
  {
    key: 'aria_label',
    label: 'Label aksesibilitas',
    placeholder: 'Buka profil Instagram Hafiz',
  },
  {
    key: 'description',
    label: 'Deskripsi singkat',
    type: 'textarea',
    rows: 3,
    placeholder: 'Jelaskan fungsi saluran kontak ini.',
  },
  {
    key: 'sort_order',
    label: 'Urutan tampil',
    type: 'number',
    min: 0,
  },
  {
    key: 'is_active',
    label: 'Status saluran',
    type: 'switch',
    hint: 'Nonaktifkan untuk menyembunyikan saluran dari situs publik.',
  },
]

const contactDetailFields = [
  {
    key: 'label',
    label: 'Label',
    placeholder: 'Domisili',
    required: true,
  },
  {
    key: 'value',
    label: 'Nilai',
    placeholder: 'Jakarta, Indonesia',
    required: true,
  },
  {
    key: 'icon',
    label: 'Ikon',
    type: 'icon',
    placeholder: 'fa-solid fa-location-dot',
    hint: 'Gunakan kelas Font Awesome yang sudah tersedia.',
    full: true,
  },
  {
    key: 'sort_order',
    label: 'Urutan tampil',
    type: 'number',
    min: 0,
  },
]

const opportunityFields = [
  {
    key: 'icon',
    label: 'Ikon',
    type: 'icon',
    placeholder: 'fa-solid fa-briefcase',
    hint: 'Gunakan kelas Font Awesome yang sudah tersedia.',
  },
  {
    key: 'title',
    label: 'Nama peluang',
    placeholder: 'Praktik Kerja Lapangan',
    required: true,
    full: true,
  },
  {
    key: 'description',
    label: 'Deskripsi',
    type: 'textarea',
    rows: 4,
    placeholder: 'Jelaskan bentuk kolaborasi atau peluang yang ditawarkan.',
    required: true,
  },
  {
    key: 'sort_order',
    label: 'Urutan tampil',
    type: 'number',
    min: 0,
  },
]
</script>

<template>
  <div class="space-y-6">
    <div
      class="flex items-start gap-3 rounded-2xl border border-white/[0.06] bg-white/[0.02] px-4 py-3 text-[11px] leading-5 text-muted"
    >
      <i class="fa-solid fa-envelope mt-0.5 text-brand" aria-hidden="true"></i>
      <p>
        Halaman ini mengatur isi halaman Kontak publik. Email utama diambil dari
        <span class="text-ink">Profil &amp; Beranda</span>, sedangkan saluran, detail singkat, dan
        peluang dikelola dari bawah.
      </p>
    </div>

    <AdminSectionForm
      section-key="contact_hero"
      title="Hero halaman kontak"
      description="Atur label, judul besar, dan paragraf pembuka di bagian paling atas halaman Kontak."
      kicker-label="Label hero"
      title-label="Judul hero"
      highlight-label="Kata sorotan"
      description-label="Paragraf pembuka"
      save-label="Simpan hero kontak"
    />

    <AdminSectionForm
      section-key="contact_channels"
      title="Judul seksi saluran kontak"
      description="Perbarui judul dan pengantar sebelum daftar saluran kontak."
      kicker-label="Label seksi"
      title-label="Judul seksi"
      highlight-label="Kata sorotan"
      description-label="Paragraf pendukung"
      save-label="Simpan judul saluran"
    />

    <AdminSectionForm
      section-key="contact_opportunities"
      title="Judul seksi peluang"
      description="Atur judul dan pengantar sebelum kartu peluang kolaborasi."
      kicker-label="Label seksi"
      title-label="Judul seksi"
      highlight-label="Kata sorotan"
      description-label="Paragraf pendukung"
      save-label="Simpan judul peluang"
    />

    <AdminSectionForm
      section-key="contact_principle"
      title="Prinsip kerja"
      description="Kutipan dan penjelasan pada kartu prinsip kerja di bagian bawah halaman Kontak."
      kicker-label="Label kutipan"
      title-label="Kutipan"
      description-label="Penjelasan"
      :show-highlight="false"
      save-label="Simpan prinsip kerja"
    />

    <AdminCrudPanel
      title="Saluran kontak"
      description="Kelola email, media sosial, dan tautan komunikasi lain yang tampil di halaman Kontak."
      endpoint="/admin/social-links"
      :fields="socialLinkFields"
      item-label="saluran kontak"
      item-title-key="label"
      item-subtitle-key="description"
      add-label="Tambah saluran"
      empty-title="Belum ada saluran kontak"
      empty-description="Tambahkan setidaknya satu saluran seperti email atau Instagram."
      :initial-values="{ icon: 'fa-solid fa-link', sort_order: 0, is_active: true }"
    />

    <AdminCrudPanel
      title="Detail singkat"
      description="Informasi ringkas seperti domisili, status, fokus, dan target yang tampil di bawah hero."
      endpoint="/admin/contact-details"
      :fields="contactDetailFields"
      item-label="detail kontak"
      item-title-key="label"
      item-subtitle-key="value"
      add-label="Tambah detail"
      empty-title="Belum ada detail kontak"
      empty-description="Tambahkan detail singkat untuk melengkapi halaman Kontak."
      :initial-values="{ icon: 'fa-solid fa-circle-info', sort_order: 0 }"
    />

    <AdminCrudPanel
      title="Peluang kolaborasi"
      description="Kelola kartu peluang yang ditampilkan pada seksi Peluang di halaman Kontak."
      endpoint="/admin/contact-opportunities"
      :fields="opportunityFields"
      item-label="peluang"
      item-title-key="title"
      item-subtitle-key="description"
      add-label="Tambah peluang"
      empty-title="Belum ada peluang"
      empty-description="Tambahkan peluang pertama agar seksi Peluang terisi di halaman publik."
      :initial-values="{ icon: 'fa-solid fa-briefcase', sort_order: 0 }"
    />
  </div>
</template>
