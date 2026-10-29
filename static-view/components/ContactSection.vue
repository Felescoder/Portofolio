<script setup>
import { siteContent } from '../data'

const profile = siteContent.profile
const heroSection = siteContent.sections.contact_hero
const channelsSection = siteContent.sections.contact_channels
const opportunitiesSection = siteContent.sections.contact_opportunities
const principleSection = siteContent.sections.contact_principle
const socialLinks = siteContent.social_links
const details = siteContent.contact_details
const opportunities = siteContent.contact_opportunities

const emailUser = profile.email?.split('@')[0] ?? ''
const emailDomain = profile.email?.split('@')[1] ?? ''
</script>

<template>
  <div id="contact" class="relative scroll-mt-24 overflow-hidden border-t border-white/[0.055]">
    <!-- =============================== HERO =============================== -->
    <section class="relative pb-20 pt-24 sm:pb-24 sm:pt-28 lg:pb-28 lg:pt-32">
      <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="contact-hero-grid absolute inset-0"></div>
        <div
          class="ambient-glow absolute -right-52 -top-56 h-[38rem] w-[38rem] rounded-full opacity-55"
        ></div>
        <div
          class="ambient-glow absolute -left-72 top-1/2 h-[34rem] w-[34rem] -translate-y-1/2 rounded-full opacity-35"
        ></div>
      </div>

      <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid items-center gap-14 lg:grid-cols-[1.05fr_0.95fr] lg:gap-20">
          <div class="reveal">
            <p class="section-kicker">{{ heroSection.kicker }}</p>
            <h2
              class="mt-6 max-w-2xl text-[2.35rem] font-semibold leading-[1.06] tracking-[-0.05em] text-ink sm:text-5xl lg:text-[4rem]"
            >
              {{ heroSection.title }}
              <span v-if="heroSection.highlight" class="text-brand">
                {{ heroSection.highlight }}
              </span>
            </h2>
            <p class="mt-6 max-w-xl text-sm font-light leading-7 text-muted sm:text-[0.95rem] sm:leading-8">
              {{ heroSection.description }}
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
              <span
                class="inline-flex items-center gap-2 rounded-full border border-brand/25 bg-brand/[0.07] px-4 py-2 text-[10px] font-medium uppercase tracking-[0.16em] text-brand"
              >
                <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                {{ profile.availability_label }}
              </span>
              <span
                class="inline-flex items-center gap-2 rounded-full border border-stroke/80 bg-panel/60 px-4 py-2 text-[10px] font-medium uppercase tracking-[0.16em] text-muted"
              >
                <i class="fa-solid fa-location-dot text-[9px] text-brand/80" aria-hidden="true"></i>
                {{ profile.location }}
              </span>
            </div>
          </div>

          <div class="reveal" :style="{ '--reveal-delay': '120ms' }">
            <div
              class="relative overflow-hidden rounded-[2rem] border border-brand/20 bg-panel/90 p-7 shadow-[0_24px_80px_-48px_rgb(217_45_32_/_0.45)] sm:p-9"
            >
              <div
                class="ambient-glow pointer-events-none absolute -right-36 -top-40 h-[24rem] w-[24rem] rounded-full opacity-70"
                aria-hidden="true"
              ></div>

              <div class="relative">
                <div class="flex items-start justify-between gap-6">
                  <div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-muted">
                      Email langsung
                    </p>
                    <a
                      :href="`mailto:${profile.email}`"
                      class="mt-3 block break-words text-base font-medium tracking-[-0.02em] text-ink transition-colors hover:text-brand sm:text-xl"
                    >
                      <span class="block sm:inline">{{ emailUser }}</span><span class="block sm:inline">@{{ emailDomain }}</span>
                    </a>
                  </div>
                  <span
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-brand/20 bg-brand/[0.07] text-sm text-brand"
                  >
                    <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                  </span>
                </div>

                <p class="mt-4 text-sm font-light leading-7 text-muted">
                  Ceritakan ide, kebutuhan proyek, atau peluang PKL yang ingin Anda diskusikan.
                </p>

                <a
                  :href="`mailto:${profile.email}`"
                  class="mt-7 inline-flex h-11 w-full items-center justify-between rounded-full bg-brand px-5 text-sm font-medium text-white transition-[background-color,box-shadow] duration-300 hover:bg-brand-hover hover:shadow-[0_0_35px_-6px_rgb(217_45_32_/_0.65)]"
                >
                  Kirim Email
                  <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
                </a>

                <div class="mt-7 flex items-center gap-3 border-t border-white/[0.07] pt-6">
                  <p class="mr-auto text-[10px] uppercase tracking-[0.16em] text-muted/70">
                    Media sosial
                  </p>
                  <a
                    v-for="social in socialLinks"
                    :key="social.label"
                    :href="social.href"
                    :target="social.href.startsWith('http') ? '_blank' : undefined"
                    :rel="social.href.startsWith('http') ? 'noopener noreferrer' : undefined"
                    class="social-link h-9 w-9 text-xs"
                    :aria-label="social.aria_label || social.label"
                    :title="social.label"
                  >
                    <i :class="social.icon" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================ DETAIL SINGKAT ======================== -->
    <section
      v-if="details.length"
      class="border-y border-white/[0.055] bg-panel/35 py-14 sm:py-16"
    >
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <dl class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
          <div
            v-for="(detail, index) in details"
            :key="detail.label"
            class="reveal flex gap-3.5"
            :style="{ '--reveal-delay': `${index * 80}ms` }"
          >
            <span
              class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-brand/20 bg-brand/[0.06] text-[11px] text-brand"
            >
              <i :class="detail.icon" aria-hidden="true"></i>
            </span>
            <div>
              <dt class="text-[10px] font-medium uppercase tracking-[0.16em] text-muted/70">
                {{ detail.label }}
              </dt>
              <dd class="mt-1.5 text-sm font-light leading-6 text-ink/90">{{ detail.value }}</dd>
            </div>
          </div>
        </dl>
      </div>
    </section>

    <!-- ============================== SALURAN ============================= -->
    <section class="relative py-24 sm:py-28 lg:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end">
          <div class="reveal">
            <p class="section-kicker">{{ channelsSection.kicker }}</p>
            <h2 class="section-heading max-w-xl">
              {{ channelsSection.title }}
              <span v-if="channelsSection.highlight" class="text-brand">
                {{ channelsSection.highlight }}
              </span>
            </h2>
          </div>
          <p class="reveal max-w-sm text-sm font-light leading-7 text-muted md:pb-1">
            {{ channelsSection.description }}
          </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <a
            v-for="(channel, index) in socialLinks"
            :key="channel.label"
            :href="channel.href"
            :target="channel.href.startsWith('http') ? '_blank' : undefined"
            :rel="channel.href.startsWith('http') ? 'noopener noreferrer' : undefined"
            class="reveal group flex flex-col rounded-3xl border border-stroke/80 bg-panel/60 p-6 transition-[transform,border-color,background-color] duration-300 hover:-translate-y-1.5 hover:border-brand/35 hover:bg-panel sm:p-7"
            :style="{ '--reveal-delay': `${index * 90}ms` }"
          >
            <div class="flex items-center justify-between">
              <span
                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-brand/20 bg-brand/[0.07] text-sm text-brand transition-colors duration-300 group-hover:border-brand/40 group-hover:bg-brand/10"
              >
                <i :class="channel.icon" aria-hidden="true"></i>
              </span>
              <i
                class="fa-solid fa-arrow-up-right-from-square text-[10px] text-muted/40 transition-colors group-hover:text-brand"
                aria-hidden="true"
              ></i>
            </div>

            <h3 class="mt-7 text-lg font-medium tracking-[-0.02em] text-ink">
              {{ channel.label }}
            </h3>
            <p class="mt-1.5 break-words text-xs text-brand/85">{{ channel.value }}</p>
            <p v-if="channel.description" class="mt-4 text-sm font-light leading-7 text-muted">
              {{ channel.description }}
            </p>
          </a>
        </div>
      </div>
    </section>

    <!-- ============================== PELUANG ============================= -->
    <section class="relative overflow-hidden border-y border-white/[0.055] bg-panel/35 py-24 sm:py-28 lg:py-32">
      <div
        class="ambient-glow pointer-events-none absolute -right-72 top-1/2 h-[38rem] w-[38rem] -translate-y-1/2 rounded-full opacity-45"
        aria-hidden="true"
      ></div>

      <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-12 max-w-2xl reveal">
          <p class="section-kicker">{{ opportunitiesSection.kicker }}</p>
          <h2 class="section-heading">
            {{ opportunitiesSection.title }}
            <span v-if="opportunitiesSection.highlight" class="text-brand">
              {{ opportunitiesSection.highlight }}
            </span>
          </h2>
          <p class="section-copy mt-6">
            {{ opportunitiesSection.description }}
          </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          <article
            v-for="(opportunity, index) in opportunities"
            :key="opportunity.title"
            class="reveal rounded-3xl border border-stroke/80 bg-canvas/55 p-6 transition-colors duration-300 hover:border-brand/30 sm:p-7"
            :style="{ '--reveal-delay': `${index * 90}ms` }"
          >
            <span
              class="flex h-11 w-11 items-center justify-center rounded-2xl border border-brand/20 bg-brand/[0.07] text-sm text-brand"
            >
              <i :class="opportunity.icon" aria-hidden="true"></i>
            </span>
            <h3 class="mt-6 text-lg font-medium tracking-[-0.02em] text-ink">
              {{ opportunity.title }}
            </h3>
            <p class="mt-3 text-sm font-light leading-7 text-muted">{{ opportunity.description }}</p>
          </article>
        </div>

        <div
          class="reveal relative mt-12 overflow-hidden rounded-[2rem] border border-brand/20 bg-panel px-6 py-10 sm:px-10 sm:py-12 lg:px-14"
        >
          <div
            class="contact-pattern pointer-events-none absolute inset-0 opacity-35"
            aria-hidden="true"
          ></div>

          <div class="relative grid items-center gap-8 lg:grid-cols-[1fr_auto] lg:gap-14">
            <div>
              <p class="text-[10px] font-medium uppercase tracking-[0.22em] text-brand">
                {{ principleSection.kicker }}
              </p>
              <blockquote
                class="mt-4 max-w-2xl text-xl font-medium leading-[1.45] tracking-[-0.025em] text-ink sm:text-2xl"
              >
                {{ principleSection.title }}
              </blockquote>
              <p class="mt-4 text-sm font-light leading-7 text-muted">
                {{ principleSection.description }}
              </p>
            </div>

            <div class="flex flex-col items-start gap-3 sm:flex-row lg:flex-col lg:items-stretch">
              <a
                :href="`mailto:${profile.email}`"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-brand px-6 text-sm font-medium text-white transition-[background-color,box-shadow] duration-300 hover:bg-brand-hover hover:shadow-[0_0_35px_-6px_rgb(217_45_32_/_0.65)]"
              >
                Mulai Diskusi
                <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
              </a>
              <a
                href="#projects"
                class="inline-flex h-11 items-center justify-center gap-2 rounded-full border border-stroke px-6 text-sm font-medium text-muted transition-colors duration-300 hover:border-brand/40 hover:text-brand"
              >
                Lihat Projek
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.contact-hero-grid {
  background-image:
    linear-gradient(rgb(255 255 255 / 0.026) 1px, transparent 1px),
    linear-gradient(90deg, rgb(255 255 255 / 0.026) 1px, transparent 1px);
  background-size: 72px 72px;
  mask-image: radial-gradient(ellipse 82% 72% at 50% 38%, black 10%, transparent 78%);
  -webkit-mask-image: radial-gradient(ellipse 82% 72% at 50% 38%, black 10%, transparent 78%);
}

.contact-pattern {
  background-image:
    linear-gradient(rgb(255 255 255 / 0.028) 1px, transparent 1px),
    linear-gradient(90deg, rgb(255 255 255 / 0.028) 1px, transparent 1px);
  background-size: 54px 54px;
  mask-image: linear-gradient(to right, transparent, black 55%, transparent);
  -webkit-mask-image: linear-gradient(to right, transparent, black 55%, transparent);
}
</style>
