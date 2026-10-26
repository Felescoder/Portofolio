<script setup>
import { computed, defineAsyncComponent } from 'vue'
import { RouterView, useRoute } from 'vue-router'
import SiteFooter from './components/SiteFooter.vue'
import SiteHeader from './components/SiteHeader.vue'

const AdminLayout = defineAsyncComponent(() => import('./components/admin/AdminLayout.vue'))

const route = useRoute()
const isAdminLayout = computed(() => route.meta.layout === 'admin')
</script>

<template>
  <AdminLayout v-if="isAdminLayout">
    <RouterView />
  </AdminLayout>

  <div v-else class="relative min-h-screen overflow-x-clip">
    <a
      href="#main-content"
      class="sr-only fixed left-4 top-4 z-[70] rounded-full bg-brand px-5 py-3 text-sm font-medium text-white focus:not-sr-only"
    >
      Lewati ke konten
    </a>

    <SiteHeader />

    <RouterView />

    <SiteFooter />
  </div>
</template>
