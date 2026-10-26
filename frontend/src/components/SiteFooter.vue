<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useSiteContent } from '../composables/useSiteContent'

const { content } = useSiteContent()

const currentYear = new Date().getFullYear()
const shortName = computed(() => content.value.profile.short_name || 'Hafiz')
const fullName = computed(
  () => content.value.profile.name || 'Muhammad Akbar Hafiz Zain',
)
const footerNote = computed(() => content.value.profile.footer_note?.trim() || '')

function scrollToTop() {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
  window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' })
}
</script>

<template>
  <footer class="border-t border-white/[0.055]">
    <div
      class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-6 py-8 sm:flex-row lg:px-8"
    >
      <RouterLink
        :to="{ path: '/', hash: '#home' }"
        class="text-xl font-semibold tracking-[-0.04em] text-brand transition-opacity hover:opacity-80"
        :aria-label="`${fullName} — kembali ke beranda`"
      >
        {{ shortName }}
      </RouterLink>

      <p
        class="order-3 text-center text-[10px] uppercase tracking-[0.15em] text-muted/60 sm:order-2"
      >
        © {{ currentYear }} {{ fullName }}.<template v-if="footerNote"> {{ footerNote }}</template>
      </p>

      <div class="order-2 flex items-center gap-2 sm:order-3">
        <RouterLink
          to="/contact"
          class="flex h-9 w-9 items-center justify-center rounded-full border border-stroke text-xs text-muted transition-colors duration-300 hover:border-brand/50 hover:text-brand"
          aria-label="Buka halaman kontak"
        >
          <i class="fa-regular fa-envelope text-[11px]" aria-hidden="true"></i>
        </RouterLink>

        <button
          type="button"
          class="flex h-9 w-9 items-center justify-center rounded-full border border-stroke text-xs text-muted transition-colors duration-300 hover:border-brand/50 hover:text-brand"
          aria-label="Kembali ke atas"
          @click="scrollToTop"
        >
          <i class="fa-solid fa-arrow-up text-[10px]" aria-hidden="true"></i>
        </button>
      </div>
    </div>
  </footer>
</template>
