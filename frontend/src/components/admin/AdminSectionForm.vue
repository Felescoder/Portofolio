<script setup>
import { onMounted, reactive, ref } from 'vue'
import { api, ApiError } from '../../admin/lib/api'
import { notify } from '../../admin/lib/toast'
import AdminField from './AdminField.vue'

const props = defineProps({
  sectionKey: { type: String, required: true },
  title: { type: String, required: true },
  description: { type: String, default: '' },
  kickerLabel: { type: String, default: 'Label kecil' },
  titleLabel: { type: String, default: 'Judul' },
  highlightLabel: { type: String, default: 'Kata sorotan' },
  descriptionLabel: { type: String, default: 'Deskripsi' },
  showKicker: { type: Boolean, default: true },
  showHighlight: { type: Boolean, default: true },
  saveLabel: { type: String, default: 'Simpan seksi' },
  defaultMeta: { type: Object, default: null },
})

const emit = defineEmits(['saved'])

const loading = ref(true)
const saving = ref(false)
const loadError = ref('')
const formMessage = ref('')
const errors = ref({})

const form = reactive({
  kicker: '',
  title: '',
  highlight: '',
  description: '',
  meta: {},
})

function errorFor(key) {
  return errors.value?.[key]?.[0] ?? ''
}

function mergeMeta(defaults, incoming) {
  if (!defaults) return incoming && typeof incoming === 'object' ? incoming : {}

  const result = JSON.parse(JSON.stringify(defaults))

  for (const [key, value] of Object.entries(incoming ?? {})) {
    const current = result[key]

    if (
      value &&
      typeof value === 'object' &&
      !Array.isArray(value) &&
      current &&
      typeof current === 'object' &&
      !Array.isArray(current)
    ) {
      result[key] = { ...current, ...value }
    } else {
      result[key] = value
    }
  }

  return result
}

async function loadSection() {
  loading.value = true
  loadError.value = ''

  try {
    const payload = await api.get(`/admin/sections/${props.sectionKey}`)
    const data = payload?.data ?? {}

    form.kicker = data.kicker ?? ''
    form.title = data.title ?? ''
    form.highlight = data.highlight ?? ''
    form.description = data.description ?? ''
    form.meta = mergeMeta(props.defaultMeta, data.meta)
  } catch (error) {
    loadError.value = error.message || 'Seksi tidak dapat dimuat.'
  } finally {
    loading.value = false
  }
}

async function saveSection() {
  if (saving.value) return

  saving.value = true
  errors.value = {}
  formMessage.value = ''

  try {
    const payload = await api.put(`/admin/sections/${props.sectionKey}`, {
      kicker: form.kicker,
      title: form.title,
      highlight: props.showHighlight ? form.highlight : null,
      description: form.description,
      meta: form.meta,
    })

    if (payload?.data) {
      form.kicker = payload.data.kicker ?? ''
      form.title = payload.data.title ?? ''
      form.highlight = payload.data.highlight ?? ''
      form.description = payload.data.description ?? ''
      form.meta = mergeMeta(props.defaultMeta, payload.data.meta)
    }

    notify(payload?.message || 'Seksi berhasil diperbarui.', 'success')
    emit('saved', payload?.data ?? null)
  } catch (error) {
    if (error instanceof ApiError && error.status === 422) {
      errors.value = error.errors ?? {}
    }

    formMessage.value = error.message || 'Perubahan tidak dapat disimpan.'
    notify(formMessage.value, 'error')
  } finally {
    saving.value = false
  }
}

onMounted(loadSection)
</script>

<template>
  <section class="admin-card">
    <header class="flex flex-col gap-3 border-b border-white/[0.06] pb-5 sm:flex-row sm:items-start sm:justify-between">
      <div class="flex items-start gap-3">
        <span
          class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-brand/20 bg-brand/[0.08] text-[11px] text-brand"
        >
          <i class="fa-solid fa-heading" aria-hidden="true"></i>
        </span>
        <div>
          <h2 class="text-sm font-medium text-ink">{{ title }}</h2>
          <p v-if="description" class="mt-1 max-w-2xl text-xs leading-5 text-muted">
            {{ description }}
          </p>
        </div>
      </div>

      <span
        class="w-fit rounded-full border border-white/[0.06] bg-white/[0.025] px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.12em] text-muted/60"
      >
        {{ sectionKey }}
      </span>
    </header>

    <div v-if="loading" class="space-y-4 pt-5">
      <div class="grid gap-4 md:grid-cols-2">
        <div class="admin-skeleton h-16"></div>
        <div class="admin-skeleton h-16"></div>
      </div>
      <div class="admin-skeleton h-24"></div>
    </div>

    <div v-else-if="loadError" class="pt-5">
      <div class="admin-inline-error">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span>{{ loadError }}</span>
        <button type="button" class="admin-btn admin-btn-secondary ml-auto" @click="loadSection">
          Coba lagi
        </button>
      </div>
    </div>

    <form v-else class="pt-5" @submit.prevent="saveSection">
      <div class="grid gap-4 md:grid-cols-2">
        <AdminField
          v-if="showKicker"
          v-model="form.kicker"
          :label="kickerLabel"
          placeholder="Contoh: Layanan"
          :error="errorFor('kicker')"
          hint="Teks kecil di atas judul seksi."
        />

        <AdminField
          v-model="form.title"
          :label="titleLabel"
          placeholder="Tulis bagian pertama judul"
          :error="errorFor('title')"
          required
          :class="{ 'md:col-span-2': !showKicker }"
        />

        <AdminField
          v-if="showHighlight"
          v-model="form.highlight"
          :label="highlightLabel"
          placeholder="Tulis bagian judul yang diberi warna"
          :error="errorFor('highlight')"
          :class="{ 'md:col-start-2': showKicker }"
          hint="Bagian ini tampil dengan warna aksen."
        />

        <AdminField
          v-model="form.description"
          :label="descriptionLabel"
          textarea
          :rows="4"
          placeholder="Tuliskan deskripsi seksi..."
          :error="errorFor('description')"
          class="md:col-span-2"
        />
      </div>

      <div v-if="$slots.extra" class="mt-5 border-t border-white/[0.06] pt-5">
        <slot name="extra" :meta="form.meta" :form="form" />
      </div>

      <div v-if="formMessage" class="mt-4 text-xs text-red-400">{{ formMessage }}</div>

      <div class="mt-5 flex justify-end">
        <button type="submit" class="admin-btn admin-btn-primary" :disabled="saving">
          <i
            :class="saving ? 'fa-solid fa-circle-notch animate-spin' : 'fa-solid fa-floppy-disk'"
            class="text-[10px]"
            aria-hidden="true"
          ></i>
          {{ saving ? 'Menyimpan...' : saveLabel }}
        </button>
      </div>
    </form>
  </section>
</template>
