<script setup>
import { useToasts } from '../../admin/lib/toast'

const { toasts, dismiss } = useToasts()

const iconByType = {
  success: 'fa-solid fa-check',
  error: 'fa-solid fa-exclamation',
  info: 'fa-solid fa-circle-info',
}

const toneByType = {
  success: 'border-emerald-400/20 bg-emerald-400/[0.08] text-emerald-300',
  error: 'border-red-400/20 bg-red-400/[0.08] text-red-300',
  info: 'border-brand/25 bg-brand/[0.08] text-brand',
}
</script>

<template>
  <Teleport to="body">
    <div
      class="pointer-events-none fixed inset-x-4 top-4 z-[200] flex flex-col items-end gap-2 sm:left-auto sm:right-6 sm:top-6 sm:w-[380px]"
      aria-live="polite"
    >
      <TransitionGroup name="admin-toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="[
            'pointer-events-auto flex w-full items-start gap-3 rounded-2xl border bg-panel/95 px-4 py-3.5 shadow-2xl backdrop-blur',
            toast.type === 'success'
              ? 'border-emerald-400/20'
              : toast.type === 'error'
                ? 'border-red-400/20'
                : 'border-brand/25',
          ]"
          role="status"
        >
          <span
            :class="[
              'mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px]',
              toneByType[toast.type] ?? toneByType.info,
            ]"
          >
            <i :class="iconByType[toast.type] ?? iconByType.info" aria-hidden="true"></i>
          </span>

          <p class="min-w-0 flex-1 text-xs leading-5 text-ink">{{ toast.message }}</p>

          <button
            type="button"
            class="text-[10px] text-muted transition-colors hover:text-ink"
            aria-label="Tutup notifikasi"
            @click="dismiss(toast.id)"
          >
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>
