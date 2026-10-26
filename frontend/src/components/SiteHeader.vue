<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useSiteContent } from '../composables/useSiteContent'

const route = useRoute()
const { content } = useSiteContent()

const shortName = computed(() => content.value.profile.short_name || 'Hafiz')
const fullName = computed(
  () => content.value.profile.name || 'Muhammad Akbar Hafiz Zain',
)

const navigation = [
  { id: 'home', label: 'Beranda' },
  { id: 'services', label: 'Layanan' },
  { id: 'skills', label: 'Keahlian' },
  { id: 'experience', label: 'Pengalaman' },
  { id: 'projects', label: 'Projek' },
  { id: 'contact', label: 'Kontak', to: '/contact' },
]

const sectionItems = navigation.filter((item) => !item.to)

const activeSection = ref('home')
const isMenuOpen = ref(false)
const isScrolled = ref(false)
const isContactPage = computed(() => route.name === 'contact')
const headerSolid = computed(() => isScrolled.value || isMenuOpen.value || isContactPage.value)

let scrollFrame
let sectionPositions = []

function linkFor(item) {
  return item.to ?? { path: '/', hash: `#${item.id}` }
}

function isItemActive(id) {
  if (id === 'contact') return isContactPage.value
  return !isContactPage.value && activeSection.value === id
}

function cacheSectionPositions() {
  sectionPositions = sectionItems.map(({ id }) => {
    const section = document.getElementById(id)
    return { id, top: section ? section.offsetTop : 0 }
  })
}

function updateActiveSection() {
  const scrollY = window.scrollY
  const nextScrolled = scrollY > 24

  if (isScrolled.value !== nextScrolled) {
    isScrolled.value = nextScrolled
  }

  if (isContactPage.value) return

  const marker = scrollY + Math.min(window.innerHeight * 0.35, 260)
  let current = 'home'

  for (const section of sectionPositions) {
    if (section.top <= marker) current = section.id
  }

  if (activeSection.value !== current) {
    activeSection.value = current
  }
}

function handleScroll() {
  if (scrollFrame) return
  scrollFrame = window.requestAnimationFrame(() => {
    updateActiveSection()
    scrollFrame = undefined
  })
}

function handleLoad() {
  if (isContactPage.value) return
  cacheSectionPositions()
  updateActiveSection()
}

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value
}

function closeMenu() {
  isMenuOpen.value = false
}

function handleKeydown(event) {
  if (event.key === 'Escape') closeMenu()
}

function handleResize() {
  if (window.innerWidth >= 1024) closeMenu()
  if (!isContactPage.value) {
    cacheSectionPositions()
    updateActiveSection()
  }
}

watch(
  () => route.fullPath,
  async () => {
    closeMenu()
    await nextTick()
    cacheSectionPositions()
    updateActiveSection()
  },
)

onMounted(() => {
  cacheSectionPositions()
  updateActiveSection()

  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('resize', handleResize, { passive: true })
  window.addEventListener('load', handleLoad)
  window.addEventListener('keydown', handleKeydown)

  document.fonts?.ready
    .then(() => {
      if (!isContactPage.value) {
        cacheSectionPositions()
        updateActiveSection()
      }
    })
    .catch(() => {})

  if (document.readyState === 'complete') handleLoad()
})

onBeforeUnmount(() => {
  if (scrollFrame) window.cancelAnimationFrame(scrollFrame)

  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', handleResize)
  window.removeEventListener('load', handleLoad)
  window.removeEventListener('keydown', handleKeydown)
})
</script>

<template>
  <header
    class="fixed inset-x-0 top-0 z-50 border-b transition-colors duration-300"
    :class="
      headerSolid ? 'border-white/[0.06] bg-canvas/95' : 'border-transparent bg-transparent'
    "
  >
    <div class="mx-auto flex h-[84px] max-w-7xl items-center justify-between px-6 lg:px-8">
      <RouterLink
        :to="{ path: '/', hash: '#home' }"
        class="inline-flex items-center text-[1.7rem] font-semibold tracking-[-0.05em] text-brand transition-opacity hover:opacity-80"
        :aria-label="`${fullName} — kembali ke beranda`"
        @click="closeMenu"
      >
        {{ shortName }}
      </RouterLink>

      <nav class="hidden lg:block" aria-label="Navigasi utama">
        <ul class="flex items-center gap-6 xl:gap-8">
          <li v-for="item in navigation" :key="item.id">
            <RouterLink
              :to="linkFor(item)"
              class="nav-link"
              :class="
                isItemActive(item.id) ? 'is-active text-brand' : 'text-ink/80 hover:text-brand'
              "
            >
              {{ item.label }}
            </RouterLink>
          </li>
        </ul>
      </nav>

      <button
        type="button"
        class="flex h-10 w-10 items-center justify-center rounded-full border border-stroke/80 text-sm text-ink transition-colors hover:border-brand/60 hover:text-brand lg:hidden"
        :aria-expanded="isMenuOpen"
        aria-controls="mobile-menu"
        :aria-label="isMenuOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'"
        @click="toggleMenu"
      >
        <i
          :class="isMenuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars-staggered'"
          aria-hidden="true"
        ></i>
      </button>
    </div>

    <Transition name="menu">
      <nav
        v-if="isMenuOpen"
        id="mobile-menu"
        class="border-t border-white/[0.06] bg-canvas px-6 pb-7 pt-3 lg:hidden"
        aria-label="Navigasi seluler"
      >
        <ul class="space-y-1">
          <li v-for="(item, index) in navigation" :key="item.id">
            <RouterLink
              :to="linkFor(item)"
              class="flex items-center justify-between rounded-xl px-3 py-3.5 text-sm font-medium transition-colors"
              :class="
                isItemActive(item.id)
                  ? 'bg-brand/[0.08] text-brand'
                  : 'text-ink/80 hover:bg-white/[0.035] hover:text-brand'
              "
              @click="closeMenu"
            >
              <span>{{ item.label }}</span>
              <span
                class="font-mono text-[10px] tracking-[0.18em]"
                :class="isItemActive(item.id) ? 'text-brand/70' : 'text-muted/50'"
              >
                0{{ index + 1 }}
              </span>
            </RouterLink>
          </li>
        </ul>
      </nav>
    </Transition>
  </header>
</template>

<style scoped>
.menu-enter-active,
.menu-leave-active {
  transition:
    opacity 0.25s ease,
    transform 0.25s ease;
}

.menu-enter-from,
.menu-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
