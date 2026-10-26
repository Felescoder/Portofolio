import { createRouter, createWebHistory } from 'vue-router'
import IndexPage from './pages/index.vue'
import { ensureUser } from './admin/lib/session'
import { getAdminToken } from './admin/lib/token'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: IndexPage,
      meta: { title: 'Muhammad Akbar Hafiz Zain — Software Engineer' },
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('./pages/contact.vue'),
      meta: { title: 'Kontak — Muhammad Akbar Hafiz Zain' },
    },
    {
      path: '/admin',
      name: 'admin-login',
      component: () => import('./pages/admin/index.vue'),
      meta: {
        title: 'Login Admin',
        layout: 'admin',
      },
    },
    {
      path: '/admin/main',
      name: 'admin-main',
      component: () => import('./pages/admin/main.vue'),
      meta: {
        title: 'Dashboard',
        description: 'Ringkasan konten, statistik singkat, dan akses cepat ke setiap seksi.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/admin/beranda',
      name: 'admin-beranda',
      component: () => import('./pages/admin/beranda.vue'),
      meta: {
        title: 'Beranda',
        description:
          'Kelola identitas, teks hero, foto profil, dan catatan footer yang tampil di halaman utama.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/admin/layanan',
      name: 'admin-layanan',
      component: () => import('./pages/admin/layanan.vue'),
      meta: {
        title: 'Layanan',
        description:
          'Atur judul seksi dan daftar layanan yang ditampilkan pada halaman beranda publik.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/admin/keahlian',
      name: 'admin-keahlian',
      component: () => import('./pages/admin/keahlian.vue'),
      meta: {
        title: 'Keahlian',
        description:
          'Kelola kelompok teknologi, daftar kemampuan, dan statistik pendukung seksi keahlian.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/admin/pengalaman',
      name: 'admin-pengalaman',
      component: () => import('./pages/admin/pengalaman.vue'),
      meta: {
        title: 'Pengalaman',
        description:
          'Perbarui judul seksi serta riwayat pendidikan, proyek mandiri, dan fondasi profesional.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/admin/proyek',
      name: 'admin-proyek',
      component: () => import('./pages/admin/proyek.vue'),
      meta: {
        title: 'Projek',
        description:
          'Kelola karya unggulan, gambar pratinjau, teknologi, tautan, dan urutan tampil projek.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/admin/contact',
      name: 'admin-contact',
      component: () => import('./pages/admin/contact.vue'),
      meta: {
        title: 'Contact',
        description:
          'Atur seluruh konten halaman Kontak: hero, saluran, detail singkat, peluang, dan prinsip kerja.',
        layout: 'admin',
        requiresAuth: true,
      },
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('./error.vue'),
      meta: { title: 'Halaman Tidak Ditemukan — Muhammad Akbar Hafiz Zain' },
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    if (to.meta.layout === 'admin') return { top: 0 }

    if (to.hash) {
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
      return { el: to.hash, top: 96, behavior: reduceMotion ? 'auto' : 'smooth' }
    }

    return { top: 0 }
  },
})

router.beforeEach(async (to) => {
  const hasToken = Boolean(getAdminToken())

  if (to.meta.requiresAuth) {
    if (!hasToken) {
      return {
        name: 'admin-login',
        query: { redirect: to.fullPath },
      }
    }

    await ensureUser()

    if (!getAdminToken()) {
      return {
        name: 'admin-login',
        query: { expired: '1', redirect: to.fullPath },
      }
    }
  }

  if (to.name === 'admin-login' && hasToken) {
    return { name: 'admin-main' }
  }

  return true
})

router.afterEach((to) => {
  const baseTitle = to.meta.title ?? 'Muhammad Akbar Hafiz Zain — Software Engineer'
  document.title =
    to.meta.layout === 'admin' ? `${baseTitle} — Admin Portofolio` : baseTitle
})

export default router
