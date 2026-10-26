import { reactive, readonly } from 'vue'

const toasts = reactive([])
let nextId = 1

export function dismissToast(id) {
  const index = toasts.findIndex((toast) => toast.id === id)
  if (index >= 0) toasts.splice(index, 1)
}

export function notify(message, type = 'success') {
  const id = nextId++
  const toast = { id, message, type }

  toasts.push(toast)
  window.setTimeout(() => dismissToast(id), 4200)

  return id
}

export function useToasts() {
  return {
    toasts: readonly(toasts),
    dismiss: dismissToast,
    notify,
  }
}
