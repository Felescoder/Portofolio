<script setup>
import { computed } from 'vue'
import { RouterLink, useRoute } from 'vue-router'

const route = useRoute()
const attemptedPath = computed(() => route.fullPath)
</script>

<template>
  <main
    id="main-content"
    class="relative flex min-h-[72vh] items-center justify-center overflow-hidden px-6 py-24 sm:py-32"
  >
    <div
      class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgb(217_45_32/0.13),transparent_58%)]"
      aria-hidden="true"
    ></div>

    <div class="relative mx-auto w-full max-w-2xl text-center">
      <p class="font-mono text-[11px] uppercase tracking-[0.28em] text-brand">Error 404</p>

      <p
        class="mt-6 select-none text-[clamp(6rem,22vw,11rem)] font-semibold leading-none tracking-[-0.09em] text-white/[0.045]"
        aria-hidden="true"
      >
        404
      </p>

      <h1 class="mt-2 text-balance text-3xl font-semibold tracking-[-0.045em] sm:text-5xl">
        Halaman tidak ditemukan.
      </h1>

      <p class="mx-auto mt-5 max-w-xl text-pretty text-sm leading-7 text-muted sm:text-base">
        Tautan yang Anda buka mungkin sudah dipindahkan atau tidak pernah ada. Silakan kembali ke
        beranda untuk melanjutkan menjelajahi portofolio.
      </p>

      <p
        v-if="attemptedPath !== '/'"
        class="mx-auto mt-4 max-w-xl truncate rounded-full border border-white/[0.06] bg-white/[0.025] px-4 py-2 font-mono text-[10px] text-muted/60"
      >
        {{ attemptedPath }}
      </p>

      <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
        <RouterLink
          :to="{ path: '/', hash: '#home' }"
          class="inline-flex items-center gap-2 rounded-full bg-brand px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-brand-hover"
        >
          <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
          Kembali ke Beranda
        </RouterLink>

        <RouterLink
          to="/contact"
          class="inline-flex items-center gap-2 rounded-full border border-stroke bg-panel/60 px-6 py-3 text-sm font-medium text-ink transition-colors hover:border-brand/40 hover:text-brand"
        >
          Hubungi Saya
          <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
        </RouterLink>
      </div>
    </div>
  </main>
</template>
