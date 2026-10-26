<script setup>
const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  label: { type: String, required: true },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  hint: { type: String, default: '' },
  error: { type: String, default: '' },
  required: { type: Boolean, default: false },
  textarea: { type: Boolean, default: false },
  rows: { type: Number, default: 4 },
  autocomplete: { type: String, default: 'off' },
  list: { type: String, default: '' },
  min: { type: [String, Number], default: undefined },
  max: { type: [String, Number], default: undefined },
  step: { type: [String, Number], default: undefined },
  disabled: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  emit('update:modelValue', event.target.value)
}
</script>

<template>
  <label class="block min-w-0">
    <span class="admin-label">
      {{ label }}
      <span v-if="required" class="text-brand" aria-hidden="true">*</span>
    </span>

    <textarea
      v-if="textarea"
      :value="modelValue"
      :rows="rows"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :aria-invalid="error ? 'true' : 'false'"
      class="admin-input admin-textarea"
      @input="updateValue"
    ></textarea>

    <input
      v-else
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :autocomplete="autocomplete"
      :list="list || undefined"
      :min="min"
      :max="max"
      :step="step"
      :aria-invalid="error ? 'true' : 'false'"
      class="admin-input"
      @input="updateValue"
    />

    <span v-if="error" class="mt-1.5 block text-xs text-red-400">{{ error }}</span>
    <span v-else-if="hint" class="mt-1.5 block text-[11px] leading-5 text-muted/60">{{ hint }}</span>
  </label>
</template>
