<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'

const props = defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
  size: { type: String, default: 'lg' },
})

const emit = defineEmits(['close'])

const dialog = ref(null)
let previousOverflow = ''

const widthClass = computed(() => {
  if (props.size === 'sm') return 'max-w-lg'
  if (props.size === 'xl') return 'max-w-5xl'
  return 'max-w-3xl'
})

function close() {
  emit('close')
}

function onKeydown(event) {
  if (event.key === 'Escape') close()
}

onMounted(() => {
  previousOverflow = document.body.style.overflow
  document.body.style.overflow = 'hidden'
  document.addEventListener('keydown', onKeydown)
  window.setTimeout(() => dialog.value?.focus(), 40)
})

onBeforeUnmount(() => {
  document.body.style.overflow = previousOverflow
  document.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-[110] flex items-end justify-center sm:items-center sm:p-6">
      <div
        class="absolute inset-0 bg-black/70 backdrop-blur-[3px]"
        aria-hidden="true"
        @click="close"
      ></div>

      <div
        ref="dialog"
        tabindex="-1"
        role="dialog"
        aria-modal="true"
        aria-labelledby="admin-modal-title"
        :class="[
          'relative flex max-h-[92vh] w-full flex-col overflow-hidden rounded-t-3xl border border-stroke bg-panel shadow-2xl sm:rounded-3xl',
          widthClass,
        ]"
      >
        <header class="flex items-start justify-between gap-5 border-b border-white/[0.06] px-5 py-5 sm:px-7">
          <div class="min-w-0">
            <p class="text-[10px] uppercase tracking-[0.18em] text-brand">Panel Admin</p>
            <h2 id="admin-modal-title" class="mt-1 truncate text-lg font-semibold tracking-[-0.03em]">
              {{ title }}
            </h2>
            <p v-if="description" class="mt-1 max-w-xl text-xs leading-5 text-muted">
              {{ description }}
            </p>
          </div>

          <button
            type="button"
            class="admin-icon-button shrink-0"
            aria-label="Tutup dialog"
            @click="close"
          >
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
          </button>
        </header>

        <div class="min-h-0 flex-1 overflow-y-auto px-5 py-5 sm:px-7">
          <slot />
        </div>

        <footer
          v-if="$slots.footer"
          class="flex flex-col-reverse gap-2 border-t border-white/[0.06] px-5 py-4 sm:flex-row sm:justify-end sm:px-7"
        >
          <slot name="footer" />
        </footer>
      </div>
    </div>
  </Teleport>
</template>
