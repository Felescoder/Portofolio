<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import { api, ApiError } from '../../admin/lib/api'
import { notify } from '../../admin/lib/toast'
import AdminField from './AdminField.vue'
import AdminModal from './AdminModal.vue'

const props = defineProps({
  title: { type: String, required: true },
  description: { type: String, default: '' },
  endpoint: { type: String, required: true },
  fields: { type: Array, required: true },
  itemTitleKey: { type: String, default: 'title' },
  itemSubtitleKey: { type: String, default: 'description' },
  itemBadgeKey: { type: String, default: '' },
  itemLabel: { type: String, default: 'item' },
  addLabel: { type: String, default: 'Tambah item' },
  emptyTitle: { type: String, default: 'Belum ada data' },
  emptyDescription: { type: String, default: 'Tambahkan item pertama untuk mulai mengisi seksi ini.' },
  initialValues: { type: Object, default: () => ({}) },
  modalSize: { type: String, default: 'lg' },
})

const items = ref([])
const loading = ref(true)
const loadError = ref('')
const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)
const deletingId = ref(null)
const errors = ref({})
const formMessage = ref('')
const imageFile = ref(null)
const imagePreview = ref('')
let previewObjectUrl = ''

const form = reactive({})
const formId = `admin-crud-form-${Math.random().toString(36).slice(2, 9)}`

const imageField = computed(() => props.fields.find((field) => field.type === 'image') ?? null)
const tagsField = computed(() => props.fields.find((field) => field.type === 'tags') ?? null)
const activeField = computed(
  () =>
    props.fields.find((field) => field.key === 'is_active' && field.type === 'switch') ??
    props.fields.find((field) => field.type === 'switch') ??
    null,
)
const sortField = computed(
  () => props.fields.find((field) => field.key === 'sort_order') ?? null,
)

const modalTitle = computed(() =>
  editing.value ? `Edit ${props.itemLabel}` : `Tambah ${props.itemLabel}`,
)

const modalDescription = computed(() =>
  editing.value
    ? `Perbarui data ${props.itemLabel} lalu simpan perubahan.`
    : `Isi data ${props.itemLabel} baru. Field bertanda * wajib diisi.`,
)

function normalizeForInput(field, value) {
  if (field.type === 'tags') {
    return Array.isArray(value) ? value.join(', ') : (value ?? '')
  }

  if (field.type === 'switch') return Boolean(value)
  if (field.type === 'number') return value === null || value === undefined ? 0 : value

  return value ?? ''
}

function createEmptyForm() {
  const values = {}

  for (const field of props.fields) {
    if (field.type === 'image') continue

    if (Object.prototype.hasOwnProperty.call(props.initialValues, field.key)) {
      values[field.key] = normalizeForInput(field, props.initialValues[field.key])
    } else if (field.type === 'switch') {
      values[field.key] = field.default ?? false
    } else if (field.type === 'number') {
      values[field.key] = field.default ?? 0
    } else {
      values[field.key] = field.default ?? ''
    }
  }

  return values
}

function resetForm(values) {
  for (const key of Object.keys(form)) delete form[key]
  Object.assign(form, values)
}

function errorFor(key) {
  return errors.value?.[key]?.[0] ?? ''
}

function clearPreviewObjectUrl() {
  if (previewObjectUrl) {
    URL.revokeObjectURL(previewObjectUrl)
    previewObjectUrl = ''
  }
}

function openCreate() {
  editing.value = null
  resetForm(createEmptyForm())
  errors.value = {}
  formMessage.value = ''
  imageFile.value = null
  clearPreviewObjectUrl()
  imagePreview.value = ''
  modalOpen.value = true
}

function openEdit(item) {
  editing.value = item
  const values = createEmptyForm()

  for (const field of props.fields) {
    if (field.type === 'image') continue
    if (item[field.key] !== undefined) {
      values[field.key] = normalizeForInput(field, item[field.key])
    }
  }

  resetForm(values)
  errors.value = {}
  formMessage.value = ''
  imageFile.value = null
  clearPreviewObjectUrl()
  imagePreview.value = imageField.value
    ? (item[imageField.value.urlKey ?? 'image_url'] ?? '')
    : ''
  modalOpen.value = true
}

function closeModal() {
  if (saving.value) return

  modalOpen.value = false
  clearPreviewObjectUrl()
  imagePreview.value = ''
  imageFile.value = null
}

function onImageChange(event) {
  const file = event.target.files?.[0] ?? null
  imageFile.value = file
  clearPreviewObjectUrl()

  if (file) {
    previewObjectUrl = URL.createObjectURL(file)
    imagePreview.value = previewObjectUrl
  } else {
    imagePreview.value = editing.value
      ? (editing.value[imageField.value?.urlKey ?? 'image_url'] ?? '')
      : ''
  }
}

function parseTags(value) {
  if (Array.isArray(value)) {
    return value.map((item) => String(item).trim()).filter(Boolean)
  }

  return String(value ?? '')
    .split(',')
    .map((item) => item.trim())
    .filter(Boolean)
}

function valueForPayload(field) {
  const value = form[field.key]

  if (field.type === 'tags') return parseTags(value)
  if (field.type === 'switch') return Boolean(value)

  if (field.type === 'number') {
    if (value === '' || value === null || value === undefined) return null
    const number = Number(value)
    return Number.isNaN(number) ? null : number
  }

  return typeof value === 'string' ? value.trim() : value
}

function buildPayload() {
  const payload = {}

  for (const field of props.fields) {
    if (field.type === 'image') continue
    payload[field.key] = valueForPayload(field)
  }

  return payload
}

function buildFormData(payload) {
  const data = new FormData()

  for (const [key, value] of Object.entries(payload)) {
    if (Array.isArray(value)) {
      data.append(key, JSON.stringify(value))
    } else if (typeof value === 'boolean') {
      data.append(key, value ? '1' : '0')
    } else if (value !== null && value !== undefined) {
      data.append(key, String(value))
    } else {
      data.append(key, '')
    }
  }

  if (imageField.value && imageFile.value) {
    data.append(imageField.value.key ?? 'image', imageFile.value)
  }

  return data
}

async function loadItems() {
  loading.value = true
  loadError.value = ''

  try {
    const payload = await api.get(props.endpoint)
    items.value = Array.isArray(payload?.data) ? payload.data : []
  } catch (error) {
    loadError.value = error.message || 'Data tidak dapat dimuat.'
  } finally {
    loading.value = false
  }
}

async function submit() {
  if (saving.value) return

  saving.value = true
  errors.value = {}
  formMessage.value = ''

  try {
    const payload = buildPayload()
    let response

    if (imageField.value) {
      const formData = buildFormData(payload)

      response = editing.value
        ? await api.post(`${props.endpoint}/${editing.value.id}`, formData)
        : await api.post(props.endpoint, formData)
    } else {
      response = editing.value
        ? await api.put(`${props.endpoint}/${editing.value.id}`, payload)
        : await api.post(props.endpoint, payload)
    }

    notify(
      response?.message || `${props.itemLabel} berhasil ${editing.value ? 'diperbarui' : 'ditambahkan'}.`,
      'success',
    )

    modalOpen.value = false
    clearPreviewObjectUrl()
    await loadItems()
  } catch (error) {
    if (error instanceof ApiError && error.status === 422) {
      errors.value = error.errors ?? {}
      formMessage.value = 'Periksa kembali field yang ditandai.'
    } else {
      formMessage.value = error.message || 'Data tidak dapat disimpan.'
      notify(formMessage.value, 'error')
    }
  } finally {
    saving.value = false
  }
}

async function removeItem(item) {
  if (deletingId.value) return

  const label = item[props.itemTitleKey] ?? props.itemLabel
  const confirmed = window.confirm(
    `Hapus ${props.itemLabel} "${label}"? Tindakan ini tidak dapat dibatalkan.`,
  )

  if (!confirmed) return

  deletingId.value = item.id

  try {
    const response = await api.delete(`${props.endpoint}/${item.id}`)
    notify(response?.message || `${props.itemLabel} berhasil dihapus.`, 'success')
    await loadItems()
  } catch (error) {
    notify(error.message || `${props.itemLabel} tidak dapat dihapus.`, 'error')
  } finally {
    deletingId.value = null
  }
}

function subtitleFor(item) {
  if (!props.itemSubtitleKey) return ''
  const value = item[props.itemSubtitleKey]
  return typeof value === 'string' ? value : ''
}

function chipsFor(item) {
  if (!tagsField.value) return []
  const value = item[tagsField.value.key]
  return Array.isArray(value) ? value : []
}

function previewFor(item) {
  if (!imageField.value) return ''
  return item[imageField.value.urlKey ?? 'image_url'] ?? ''
}

function altFor(item) {
  return item.image_alt || item[props.itemTitleKey] || props.itemLabel
}

function activeFor(item) {
  if (!activeField.value) return true
  return Boolean(item[activeField.value.key])
}

onMounted(loadItems)

onBeforeUnmount(clearPreviewObjectUrl)
</script>

<template>
  <section class="admin-card">
    <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
      <div class="flex items-start gap-3">
        <span
          class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/[0.07] bg-white/[0.025] text-[11px] text-muted"
        >
          <i class="fa-solid fa-list-ul" aria-hidden="true"></i>
        </span>
        <div>
          <h2 class="text-sm font-medium text-ink">{{ title }}</h2>
          <p v-if="description" class="mt-1 max-w-2xl text-xs leading-5 text-muted">
            {{ description }}
          </p>
        </div>
      </div>

      <button type="button" class="admin-btn admin-btn-primary shrink-0" @click="openCreate">
        <i class="fa-solid fa-plus text-[10px]" aria-hidden="true"></i>
        {{ addLabel }}
      </button>
    </header>

    <div v-if="loading" class="mt-5 space-y-3 border-t border-white/[0.06] pt-5">
      <div class="admin-skeleton h-20"></div>
      <div class="admin-skeleton h-20"></div>
    </div>

    <div v-else-if="loadError" class="mt-5 border-t border-white/[0.06] pt-5">
      <div class="admin-inline-error">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span>{{ loadError }}</span>
        <button type="button" class="admin-btn admin-btn-secondary ml-auto" @click="loadItems">
          Coba lagi
        </button>
      </div>
    </div>

    <div
      v-else-if="items.length === 0"
      class="mt-5 flex flex-col items-center justify-center rounded-2xl border border-dashed border-white/[0.08] bg-canvas/30 px-6 py-12 text-center"
    >
      <span
        class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/[0.06] bg-white/[0.025] text-muted"
      >
        <i class="fa-solid fa-inbox text-sm" aria-hidden="true"></i>
      </span>
      <p class="mt-4 text-sm font-medium text-ink">{{ emptyTitle }}</p>
      <p class="mt-1 max-w-md text-xs leading-5 text-muted">{{ emptyDescription }}</p>
      <button type="button" class="admin-btn admin-btn-secondary mt-4" @click="openCreate">
        <i class="fa-solid fa-plus text-[10px]" aria-hidden="true"></i>
        {{ addLabel }}
      </button>
    </div>

    <div v-else class="mt-3 divide-y divide-white/[0.05] border-t border-white/[0.06]">
      <article
        v-for="item in items"
        :key="item.id"
        class="flex flex-col gap-4 py-5 sm:flex-row sm:items-start"
      >
        <div class="flex min-w-0 flex-1 items-start gap-3">
          <img
            v-if="imageField && previewFor(item)"
            :src="previewFor(item)"
            :alt="altFor(item)"
            class="h-16 w-24 shrink-0 rounded-xl border border-white/[0.06] object-cover"
            loading="lazy"
          />

          <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
              <h3 class="truncate text-sm font-medium text-ink">
                {{ item[itemTitleKey] || `Tanpa ${itemLabel}` }}
              </h3>

              <span
                v-if="itemBadgeKey && item[itemBadgeKey]"
                class="rounded-full border border-white/[0.07] bg-white/[0.025] px-2 py-0.5 text-[9px] uppercase tracking-[0.1em] text-muted"
              >
                {{ item[itemBadgeKey] }}
              </span>

              <span
                v-if="activeField"
                :class="[
                  'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] uppercase tracking-[0.08em]',
                  activeFor(item)
                    ? 'bg-emerald-400/[0.08] text-emerald-300'
                    : 'bg-white/[0.035] text-muted/70',
                ]"
              >
                <span
                  :class="[
                    'h-1 w-1 rounded-full',
                    activeFor(item) ? 'bg-emerald-400' : 'bg-muted/60',
                  ]"
                ></span>
                {{ activeFor(item) ? 'Aktif' : 'Nonaktif' }}
              </span>
            </div>

            <p v-if="subtitleFor(item)" class="mt-1 line-clamp-2 text-xs leading-5 text-muted">
              {{ subtitleFor(item) }}
            </p>

            <div v-if="chipsFor(item).length" class="mt-2 flex flex-wrap gap-1.5">
              <span
                v-for="chip in chipsFor(item).slice(0, 8)"
                :key="chip"
                class="rounded-full border border-white/[0.06] px-2 py-0.5 text-[9px] text-muted/80"
              >
                {{ chip }}
              </span>
              <span
                v-if="chipsFor(item).length > 8"
                class="rounded-full border border-white/[0.06] px-2 py-0.5 text-[9px] text-muted/50"
              >
                +{{ chipsFor(item).length - 8 }}
              </span>
            </div>

            <p
              v-if="sortField"
              class="mt-2 font-mono text-[9px] uppercase tracking-[0.1em] text-muted/40"
            >
              Urutan {{ item[sortField.key] ?? 0 }}
            </p>
          </div>
        </div>

        <div class="flex shrink-0 items-center gap-2 sm:pl-3">
          <button
            type="button"
            class="admin-btn admin-btn-secondary"
            :aria-label="`Edit ${item[itemTitleKey] || itemLabel}`"
            @click="openEdit(item)"
          >
            <i class="fa-solid fa-pen text-[10px]" aria-hidden="true"></i>
            Edit
          </button>

          <button
            type="button"
            class="admin-btn admin-btn-danger"
            :disabled="deletingId === item.id"
            :aria-label="`Hapus ${item[itemTitleKey] || itemLabel}`"
            @click="removeItem(item)"
          >
            <i
              :class="
                deletingId === item.id
                  ? 'fa-solid fa-circle-notch animate-spin'
                  : 'fa-solid fa-trash'
              "
              class="text-[10px]"
              aria-hidden="true"
            ></i>
            {{ deletingId === item.id ? 'Menghapus' : 'Hapus' }}
          </button>
        </div>
      </article>
    </div>

    <AdminModal
      v-if="modalOpen"
      :title="modalTitle"
      :description="modalDescription"
      :size="modalSize"
      @close="closeModal"
    >
      <form :id="formId" class="space-y-4" @submit.prevent="submit">
        <div class="grid gap-4 sm:grid-cols-2">
          <template v-for="field in fields" :key="field.key">
            <div
              v-if="field.type === 'image'"
              class="rounded-2xl border border-white/[0.06] bg-canvas/40 p-4 sm:col-span-2"
            >
              <span class="admin-label">{{ field.label }}</span>

              <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div
                  class="flex aspect-[2/1] w-full items-center justify-center overflow-hidden rounded-xl border border-white/[0.07] bg-white/[0.02] sm:w-44"
                >
                  <img
                    v-if="imagePreview"
                    :src="imagePreview"
                    alt="Pratinjau gambar"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="text-center text-muted/50">
                    <i class="fa-regular fa-image text-lg" aria-hidden="true"></i>
                    <p class="mt-1 text-[9px] uppercase tracking-[0.1em]">Belum ada gambar</p>
                  </div>
                </div>

                <div class="min-w-0 flex-1">
                  <input
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full cursor-pointer rounded-xl border border-stroke bg-canvas px-3 py-2.5 text-xs text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-white/[0.06] file:px-3 file:py-1.5 file:text-[10px] file:text-ink hover:file:bg-white/[0.09]"
                    @change="onImageChange"
                  />
                  <p class="mt-1.5 text-[11px] leading-5 text-muted/60">
                    JPG, PNG, atau WebP. Maks. 4 MB. Biarkan kosong untuk mempertahankan gambar
                    saat ini.
                  </p>
                  <p v-if="errorFor(field.key)" class="mt-1 text-xs text-red-400">
                    {{ errorFor(field.key) }}
                  </p>
                </div>
              </div>
            </div>

            <div v-else-if="field.type === 'switch'" class="flex flex-col justify-center">
              <span class="admin-label">{{ field.label }}</span>
              <div class="flex items-center gap-3">
                <button
                  type="button"
                  role="switch"
                  :aria-checked="Boolean(form[field.key])"
                  :class="[
                    'relative inline-flex h-6 w-11 shrink-0 items-center rounded-full border transition-colors',
                    form[field.key]
                      ? 'border-brand/40 bg-brand'
                      : 'border-stroke bg-white/[0.04]',
                  ]"
                  @click="form[field.key] = !form[field.key]"
                >
                  <span
                    :class="[
                      'inline-block h-4 w-4 rounded-full bg-white shadow transition-transform',
                      form[field.key] ? 'translate-x-6' : 'translate-x-1',
                    ]"
                  ></span>
                </button>
                <span class="text-xs text-muted">
                  {{ form[field.key] ? 'Tampil di situs publik' : 'Disembunyikan dari situs' }}
                </span>
              </div>
              <p v-if="field.hint" class="mt-1.5 text-[11px] text-muted/60">{{ field.hint }}</p>
              <p v-if="errorFor(field.key)" class="mt-1 text-xs text-red-400">
                {{ errorFor(field.key) }}
              </p>
            </div>

            <AdminField
              v-else
              v-model="form[field.key]"
              :label="field.label"
              :type="field.type === 'number' ? 'number' : field.type === 'url' ? 'url' : 'text'"
              :placeholder="field.placeholder || ''"
              :hint="field.hint || (field.type === 'tags' ? 'Pisahkan setiap item dengan koma.' : '')"
              :error="errorFor(field.key)"
              :required="Boolean(field.required)"
              :textarea="field.type === 'textarea'"
              :rows="field.rows || 4"
              :list="field.type === 'icon' ? 'admin-icon-options' : ''"
              :min="field.min"
              :max="field.max"
              :step="field.step"
              :class="field.full || field.type === 'textarea' ? 'sm:col-span-2' : ''"
            />
          </template>
        </div>

        <p class="text-[10px] text-muted/50">
          Field dengan tanda <span class="text-brand">*</span> wajib diisi.
        </p>

        <div
          v-if="formMessage"
          class="rounded-xl border border-red-400/20 bg-red-400/[0.06] px-3 py-2 text-xs leading-5 text-red-300"
          role="alert"
        >
          {{ formMessage }}
        </div>
      </form>

      <template #footer>
        <button type="button" class="admin-btn admin-btn-secondary" @click="closeModal">
          Batal
        </button>
        <button
          type="submit"
          :form="formId"
          class="admin-btn admin-btn-primary"
          :disabled="saving"
        >
          <i
            :class="saving ? 'fa-solid fa-circle-notch animate-spin' : 'fa-solid fa-floppy-disk'"
            class="text-[10px]"
            aria-hidden="true"
          ></i>
          {{ saving ? 'Menyimpan...' : editing ? 'Simpan perubahan' : 'Tambah data' }}
        </button>
      </template>
    </AdminModal>
  </section>
</template>
