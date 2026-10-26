<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { RouterLink } from 'vue-router'
import { useReveal } from '../composables/useReveal'
import { useSiteContent } from '../composables/useSiteContent'

const { content } = useSiteContent()

const profile = computed(() => content.value.profile)
const services = computed(() => content.value.services)
const skillGroups = computed(() => content.value.skill_groups)
const experiences = computed(() => content.value.experiences)
const projects = computed(() => content.value.projects)
const socialLinks = computed(() => content.value.social_links)
const skillSection = computed(() => content.value.sections.skills)
const skillStats = computed(() => {
  const stats = skillSection.value?.meta?.stats

  if (!Array.isArray(stats)) return []

  return stats.filter((stat) => stat && (stat.value || stat.label))
})

const typedRole = ref('')
const imageFailed = ref(false)

let typeTimer
let reduceMotion = false
let hasMounted = false

function startTypewriter(role) {
  window.clearTimeout(typeTimer)

  const target = String(role ?? '')

  if (!target) {
    typedRole.value = ''
    return
  }

  if (reduceMotion) {
    typedRole.value = target
    return
  }

  typedRole.value = ''
  let index = 0

  const typeNextCharacter = () => {
    typedRole.value = target.slice(0, index + 1)
    index += 1

    if (index < target.length) {
      typeTimer = window.setTimeout(typeNextCharacter, 90)
    }
  }

  typeTimer = window.setTimeout(typeNextCharacter, 550)
}

watch(
  () => profile.value.typed_role,
  (value) => {
    if (!hasMounted) return
    startTypewriter(value)
  },
)

watch(
  () => profile.value.portrait_url,
  () => {
    imageFailed.value = false
  },
)

useReveal()

onMounted(() => {
  reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
  hasMounted = true
  startTypewriter(profile.value.typed_role)
})

onBeforeUnmount(() => {
  window.clearTimeout(typeTimer)
})
</script>

<template>
  <main id="main-content">
    <!-- =============================== HERO =============================== -->
    <section
      id="home"
      class="relative flex min-h-screen items-center overflow-hidden pb-20 pt-32 sm:pt-36 lg:pb-24 lg:pt-32"
    >
      <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="hero-grid absolute inset-0"></div>
        <div
          class="ambient-glow absolute -left-64 top-1/4 h-[36rem] w-[36rem] rounded-full opacity-70"
        ></div>
        <div
          class="ambient-glow absolute -right-48 -top-52 h-[34rem] w-[34rem] rounded-full opacity-45"
        ></div>
        <div
          class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"
        ></div>
      </div>

      <div
        class="relative mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-16 px-6 lg:grid-cols-[minmax(0,0.92fr)_minmax(0,1.08fr)] lg:gap-10 lg:px-8 xl:gap-20"
      >
        <!-- Portrait -->
        <div class="flex justify-center lg:justify-start">
          <div
            class="hero-reveal group relative aspect-square w-full max-w-[280px] sm:max-w-[340px] lg:max-w-[390px] xl:max-w-[410px]"
          >
            <span
              class="portrait-aura absolute -inset-16 rounded-full sm:-inset-20"
              aria-hidden="true"
            ></span>
            <span
              class="absolute -inset-6 rounded-full border border-brand/[0.13] sm:-inset-7"
              aria-hidden="true"
            ></span>
            <span
              class="absolute -inset-10 rounded-full border border-dashed border-white/[0.07] sm:-inset-12"
              aria-hidden="true"
            >
              <span
                class="absolute left-1/2 top-0 h-2 w-2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand shadow-[0_0_16px_4px_rgb(217_45_32_/_0.35)]"
              ></span>
            </span>

            <div
              class="relative h-full w-full rounded-full border border-brand/30 bg-panel p-2 shadow-[0_0_90px_-20px_rgb(217_45_32_/_0.55)]"
            >
              <div class="relative h-full w-full overflow-hidden rounded-full bg-elevated">
                <img
                  v-if="!imageFailed && profile.portrait_url"
                  :src="profile.portrait_url"
                  :alt="`Foto profil ${profile.name}`"
                  width="600"
                  height="600"
                  fetchpriority="high"
                  class="h-full w-full scale-[1.02] object-cover object-center transition-transform duration-[1200ms] ease-out group-hover:scale-[1.07]"
                  @error="imageFailed = true"
                />
                <div
                  v-else
                  class="flex h-full w-full items-center justify-center bg-[radial-gradient(circle_at_35%_25%,#2a2a2f,#0d0d0e)]"
                  role="img"
                  :aria-label="`Foto profil ${profile.name}`"
                >
                  <span class="text-7xl font-semibold tracking-[-0.06em] text-brand">
                    {{ profile.short_name?.charAt(0) || 'H' }}
                  </span>
                </div>

                <div
                  class="pointer-events-none absolute inset-0 bg-gradient-to-t from-canvas/60 via-transparent to-transparent"
                  aria-hidden="true"
                ></div>
                <div
                  class="pointer-events-none absolute inset-0 rounded-full shadow-[inset_0_0_80px_28px_rgba(10,10,10,0.88)]"
                  aria-hidden="true"
                ></div>
              </div>
            </div>

            <div
              class="hero-reveal hero-delay-4 absolute -bottom-2 right-0 inline-flex items-center gap-2.5 rounded-full border border-stroke/90 bg-panel px-4 py-2.5 shadow-[0_12px_35px_rgba(0,0,0,0.45)] sm:bottom-3 sm:right-1"
            >
              <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full rounded-full bg-brand opacity-30"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-brand"></span>
              </span>
              <span class="text-[10px] font-medium uppercase tracking-[0.16em] text-ink/90">
                {{ profile.availability_label }}
              </span>
            </div>
          </div>
        </div>

        <!-- Introduction -->
        <div class="max-w-2xl">
          <p
            class="hero-reveal hero-delay-1 flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.3em] text-muted sm:text-[11px]"
          >
            <span class="h-px w-9 bg-brand" aria-hidden="true"></span>
            {{ profile.hero_kicker }}
          </p>

          <h1
            class="hero-reveal hero-delay-2 mt-6 text-[2.2rem] font-semibold leading-[1.04] tracking-[-0.055em] text-ink sm:text-[2.9rem] lg:text-[3.35rem] xl:text-[3.75rem]"
          >
            {{ profile.hero_greeting }}
            <span class="mt-1 block text-brand">{{ profile.hero_name_line_1 }}</span>
            <span v-if="profile.hero_name_line_2" class="block text-brand">
              {{ profile.hero_name_line_2 }}
            </span>
          </h1>

          <h2
            class="hero-reveal hero-delay-3 mt-5 text-lg font-medium tracking-[-0.035em] text-ink sm:text-2xl lg:text-[1.6rem]"
          >
            <span class="sr-only">{{ profile.role }} | {{ profile.typed_role }}</span>
            <span class="block" aria-hidden="true">{{ profile.role }}</span>
            <span class="mt-1 flex items-center text-brand" aria-hidden="true">
              <span class="relative inline-grid align-baseline">
                <span class="invisible col-start-1 row-start-1" aria-hidden="true">
                  {{ profile.typed_role }}
                </span>
                <span class="col-start-1 row-start-1 inline-flex items-center whitespace-nowrap">
                  <span>{{ typedRole }}</span>
                  <span class="typing-cursor ml-1" aria-hidden="true"></span>
                </span>
              </span>
            </span>
          </h2>

          <p
            class="hero-reveal hero-delay-4 mt-7 max-w-[34rem] text-left text-sm font-light leading-7 text-muted sm:text-[0.95rem] sm:leading-8"
          >
            {{ profile.hero_description }}
          </p>

          <div class="hero-reveal hero-delay-5 mt-9 flex flex-wrap items-center gap-x-6 gap-y-5">
            <ul class="flex items-center gap-3" aria-label="Tautan media sosial">
              <li v-for="social in socialLinks" :key="social.label">
                <a
                  :href="social.href"
                  class="social-link"
                  :target="social.href.startsWith('http') ? '_blank' : undefined"
                  :rel="social.href.startsWith('http') ? 'noopener noreferrer' : undefined"
                  :aria-label="social.aria_label || social.label"
                  :title="social.label"
                >
                  <i :class="social.icon" aria-hidden="true"></i>
                </a>
              </li>
            </ul>

            <span class="hidden h-9 w-px bg-stroke/80 sm:block" aria-hidden="true"></span>

            <RouterLink
              to="/contact"
              class="inline-flex h-11 items-center justify-center gap-2 rounded-full border-2 border-brand bg-transparent px-6 text-sm font-medium text-brand transition-[background-color,color,box-shadow] duration-300 hover:bg-brand hover:text-white hover:shadow-[0_0_30px_-6px_rgb(217_45_32_/_0.6)] focus-visible:bg-brand focus-visible:text-white"
            >
              {{ profile.hero_cta_label }}
              <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
            </RouterLink>
          </div>
        </div>
      </div>

      <div
        class="pointer-events-none absolute bottom-8 right-8 hidden items-center gap-3 text-[9px] uppercase tracking-[0.28em] text-muted/60 xl:flex"
        aria-hidden="true"
      >
        <span>Gulir untuk menjelajah</span>
        <span class="h-9 w-px bg-gradient-to-b from-brand/70 to-transparent"></span>
      </div>
    </section>

    <!-- ============================= LAYANAN ============================== -->
    <section
      id="services"
      class="relative scroll-mt-24 border-t border-white/[0.055] py-24 sm:py-28 lg:py-32"
    >
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-14 flex flex-col justify-between gap-7 md:flex-row md:items-end">
          <div class="reveal">
            <p class="section-kicker">{{ content.sections.services.kicker }}</p>
            <h2 class="section-heading max-w-xl">
              {{ content.sections.services.title }}
              <span v-if="content.sections.services.highlight" class="text-brand">
                {{ content.sections.services.highlight }}
              </span>
            </h2>
          </div>
          <p class="reveal max-w-sm text-sm font-light leading-7 text-muted md:pb-1">
            {{ content.sections.services.description }}
          </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article
            v-for="(service, index) in services"
            :key="service.title"
            class="service-card reveal group relative flex min-h-[19rem] flex-col overflow-hidden rounded-3xl border border-stroke/80 bg-panel/70 p-7 hover:border-brand/40 hover:bg-elevated/80 sm:p-8"
            :style="{ '--reveal-delay': `${index * 90}ms` }"
          >
            <div
              class="absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-brand/60 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
              aria-hidden="true"
            ></div>

            <div class="mb-8 flex items-start justify-between">
              <span
                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-brand/20 bg-brand/[0.07] text-base text-brand transition-colors duration-300 group-hover:border-brand/40 group-hover:bg-brand/10"
              >
                <i :class="service.icon" aria-hidden="true"></i>
              </span>
              <span
                v-if="service.number"
                class="font-mono text-[10px] tracking-[0.2em] text-muted/50"
              >
                {{ service.number }}
              </span>
            </div>

            <h3 class="text-xl font-medium tracking-[-0.025em] text-ink">{{ service.title }}</h3>
            <p class="mt-3 text-sm font-light leading-7 text-muted">{{ service.description }}</p>

            <ul v-if="service.tags?.length" class="mt-auto flex flex-wrap gap-2 pt-7">
              <li
                v-for="tag in service.tags"
                :key="tag"
                class="rounded-full border border-stroke/80 px-3 py-1 text-[10px] text-muted/80"
              >
                {{ tag }}
              </li>
            </ul>
          </article>
        </div>
      </div>
    </section>

    <!-- ============================= KEAHLIAN ============================= -->
    <section
      id="skills"
      class="relative scroll-mt-24 overflow-hidden border-y border-white/[0.055] bg-panel/35 py-24 sm:py-28 lg:py-32"
    >
      <div
        class="ambient-glow pointer-events-none absolute -right-64 top-1/2 h-[35rem] w-[35rem] -translate-y-1/2 rounded-full opacity-60"
        aria-hidden="true"
      ></div>

      <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid gap-14 lg:grid-cols-[0.85fr_1.15fr] lg:gap-20">
          <div class="reveal lg:sticky lg:top-32 lg:self-start">
            <p class="section-kicker">{{ content.sections.skills.kicker }}</p>
            <h2 class="section-heading">
              {{ content.sections.skills.title }}
              <span v-if="content.sections.skills.highlight" class="text-brand">
                {{ content.sections.skills.highlight }}
              </span>
            </h2>
            <p class="section-copy mt-6 max-w-md">
              {{ content.sections.skills.description }}
            </p>

            <div v-if="skillStats.length" class="mt-10 grid max-w-sm grid-cols-2 gap-3">
              <div
                v-for="(stat, index) in skillStats"
                :key="index"
                class="rounded-2xl border border-stroke/80 bg-canvas/55 p-5"
              >
                <p class="text-3xl font-semibold tracking-[-0.045em] text-ink">
                  {{ stat.value }}
                </p>
                <p class="mt-1.5 text-[10px] uppercase tracking-[0.16em] text-muted">
                  {{ stat.label }}
                </p>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <article
              v-for="(group, index) in skillGroups"
              :key="group.title"
              class="reveal group rounded-2xl border border-stroke/80 bg-panel/75 p-6 transition-colors duration-300 hover:border-brand/30 sm:p-7"
              :style="{ '--reveal-delay': `${index * 100}ms` }"
            >
              <div class="flex items-center gap-3.5">
                <span
                  class="flex h-10 w-10 items-center justify-center rounded-xl border border-brand/20 bg-brand/[0.06] text-sm text-brand"
                >
                  <i :class="group.icon" aria-hidden="true"></i>
                </span>
                <h3 class="text-base font-medium tracking-[-0.01em] text-ink">{{ group.title }}</h3>
                <span class="ml-auto font-mono text-[10px] tracking-[0.16em] text-muted/50">
                  0{{ index + 1 }}
                </span>
              </div>

              <ul class="mt-5 flex flex-wrap gap-2">
                <li
                  v-for="skill in group.items"
                  :key="skill"
                  class="rounded-full border border-stroke bg-canvas px-3.5 py-1.5 text-[11px] text-muted transition-colors duration-300 hover:border-brand/40 hover:text-ink"
                >
                  {{ skill }}
                </li>
              </ul>
            </article>
          </div>
        </div>
      </div>
    </section>

    <!-- ======================= PENGALAMAN & PENDIDIKAN ==================== -->
    <section
      id="experience"
      class="relative scroll-mt-24 border-b border-white/[0.055] bg-panel/35 py-24 sm:py-28 lg:py-32"
    >
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-14 flex flex-col justify-between gap-7 md:flex-row md:items-end">
          <div class="reveal">
            <p class="section-kicker">{{ content.sections.experience.kicker }}</p>
            <h2 class="section-heading max-w-xl">
              {{ content.sections.experience.title }}
              <span v-if="content.sections.experience.highlight" class="text-brand">
                {{ content.sections.experience.highlight }}
              </span>
            </h2>
          </div>
          <p class="reveal max-w-sm text-sm font-light leading-7 text-muted md:pb-1">
            {{ content.sections.experience.description }}
          </p>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
          <article
            v-for="(item, index) in experiences"
            :key="item.role"
            class="reveal group flex flex-col rounded-3xl border border-stroke/80 bg-canvas/55 p-6 transition-[transform,border-color,background-color] duration-300 hover:-translate-y-1.5 hover:border-brand/35 hover:bg-panel sm:p-7"
            :style="{ '--reveal-delay': `${index * 100}ms` }"
          >
            <div class="flex items-center justify-between gap-4">
              <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-brand">
                {{ item.period }}
              </p>
              <i
                :class="[item.icon, 'text-[11px] text-muted/40 transition-colors group-hover:text-brand']"
                aria-hidden="true"
              ></i>
            </div>

            <div class="mt-7">
              <h3 class="text-lg font-medium tracking-[-0.025em] text-ink">{{ item.role }}</h3>
              <p class="mt-1.5 text-xs text-muted">{{ item.company }}</p>
            </div>

            <p class="mt-4 text-sm font-light leading-7 text-muted">{{ item.description }}</p>

            <ul v-if="item.tags?.length" class="mt-auto flex flex-wrap gap-2 pt-7">
              <li
                v-for="tag in item.tags"
                :key="tag"
                class="rounded-full border border-stroke/80 px-3 py-1 text-[10px] text-muted/80"
              >
                {{ tag }}
              </li>
            </ul>
          </article>
        </div>
      </div>
    </section>

    <!-- ============================== PROJEK ============================== -->
    <section id="projects" class="relative scroll-mt-24 overflow-hidden py-24 sm:py-28 lg:py-32">
      <div
        class="ambient-glow pointer-events-none absolute -left-80 top-1/3 h-[40rem] w-[40rem] rounded-full opacity-40"
        aria-hidden="true"
      ></div>

      <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-14 flex flex-col justify-between gap-7 md:flex-row md:items-end">
          <div class="reveal">
            <p class="section-kicker">{{ content.sections.projects.kicker }}</p>
            <h2 class="section-heading max-w-xl">
              {{ content.sections.projects.title }}
              <span v-if="content.sections.projects.highlight" class="text-brand">
                {{ content.sections.projects.highlight }}
              </span>
            </h2>
          </div>
          <p class="reveal max-w-sm text-sm font-light leading-7 text-muted md:pb-1">
            {{ content.sections.projects.description }}
          </p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
          <article
            v-for="(project, index) in projects"
            :key="project.title"
            class="reveal group flex flex-col overflow-hidden rounded-3xl border border-stroke/80 bg-panel/60 transition-[transform,border-color,background-color] duration-300 hover:-translate-y-1.5 hover:border-brand/40 hover:bg-panel"
            :style="{ '--reveal-delay': `${index * 110}ms` }"
          >
            <div class="relative aspect-[16/9] overflow-hidden border-b border-stroke/70 bg-elevated">
              <img
                v-if="project.image_url"
                :src="project.image_url"
                :alt="project.image_alt || project.title"
                :width="project.width ?? undefined"
                :height="project.height ?? undefined"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-[1.025]"
              />
              <div
                v-else
                class="flex h-full w-full items-center justify-center bg-[radial-gradient(circle_at_35%_25%,#2a2a2f,#0d0d0e)] text-muted/40"
                role="img"
                :aria-label="`Gambar projek ${project.title}`"
              >
                <i class="fa-regular fa-image text-3xl" aria-hidden="true"></i>
              </div>
              <div
                class="pointer-events-none absolute inset-0 bg-gradient-to-t from-canvas/45 via-transparent to-transparent"
                aria-hidden="true"
              ></div>
              <span
                class="absolute left-4 top-4 rounded-full border border-white/10 bg-canvas/90 px-3 py-1 text-[9px] font-medium uppercase tracking-[0.14em] text-ink/85"
              >
                {{ project.category }}
              </span>
            </div>

            <div class="flex flex-1 flex-col p-6 sm:p-7">
              <h3 class="text-xl font-medium tracking-[-0.025em] text-ink">{{ project.title }}</h3>
              <p class="mt-3 text-sm font-light leading-7 text-muted">{{ project.description }}</p>

              <ul v-if="project.tags?.length" class="mt-5 flex flex-wrap gap-2">
                <li
                  v-for="tag in project.tags"
                  :key="tag"
                  class="rounded-full border border-stroke/80 px-3 py-1 text-[10px] text-muted/80"
                >
                  {{ tag }}
                </li>
              </ul>

              <div class="mt-auto pt-7">
                <a
                  v-if="project.url"
                  :href="project.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex h-10 w-full items-center justify-between rounded-full border border-brand/30 bg-brand/[0.07] px-4 text-xs font-medium text-brand transition-colors duration-300 hover:border-brand/60 hover:bg-brand/10 hover:text-brand-hover"
                >
                  Lihat Program
                  <i
                    class="fa-solid fa-arrow-up-right-from-square text-[9px]"
                    aria-hidden="true"
                  ></i>
                </a>
                <span
                  v-else
                  class="inline-flex h-10 w-full items-center justify-between rounded-full border border-stroke/70 px-4 text-xs font-medium text-muted/50"
                >
                  Tautan belum tersedia
                  <i class="fa-solid fa-link-slash text-[9px]" aria-hidden="true"></i>
                </span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>
  </main>
</template>

<style scoped>
/* ---------------- Hero background ---------------- */
.hero-grid {
  background-image:
    linear-gradient(rgb(255 255 255 / 0.028) 1px, transparent 1px),
    linear-gradient(90deg, rgb(255 255 255 / 0.028) 1px, transparent 1px);
  background-size: 72px 72px;
  mask-image: radial-gradient(ellipse 76% 66% at 50% 45%, black 12%, transparent 78%);
  -webkit-mask-image: radial-gradient(ellipse 76% 66% at 50% 45%, black 12%, transparent 78%);
}

/* ---------------- Portrait glow & orbit ---------------- */
.portrait-aura {
  background: radial-gradient(
    circle,
    rgb(var(--color-primary) / 0.4) 0%,
    rgb(var(--color-primary) / 0.18) 42%,
    rgb(var(--color-primary) / 0.06) 62%,
    transparent 84%
  );
}

/* ---------------- Typewriter cursor ---------------- */
.typing-cursor {
  display: inline-block;
  width: 2px;
  height: 1.05em;
  border-radius: 999px;
  background: rgb(var(--color-primary));
  box-shadow: 0 0 12px rgb(var(--color-primary) / 0.6);
  animation: cursor-blink 1s step-end infinite;
}

@keyframes cursor-blink {
  0%,
  45% {
    opacity: 1;
  }
  50%,
  95% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

/* ---------------- Entrance animation ---------------- */
.hero-reveal {
  animation: hero-rise 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.hero-delay-1 {
  animation-delay: 0.08s;
}

.hero-delay-2 {
  animation-delay: 0.16s;
}

.hero-delay-3 {
  animation-delay: 0.24s;
}

.hero-delay-4 {
  animation-delay: 0.32s;
}

.hero-delay-5 {
  animation-delay: 0.4s;
}

@keyframes hero-rise {
  from {
    opacity: 0;
    transform: translateY(24px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (prefers-reduced-motion: reduce) {
  .hero-reveal {
    animation: none !important;
  }

  .typing-cursor {
    animation: none !important;
  }
}
</style>
