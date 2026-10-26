import { reactive, readonly } from 'vue'
import { api } from './api'
import { clearAdminToken, getAdminToken, setAdminToken } from './token'

const state = reactive({
  user: null,
  loading: false,
  initialized: false,
  error: '',
})

let ensurePromise = null

async function login(email, password) {
  state.loading = true
  state.error = ''

  try {
    const payload = await api.post(
      '/admin/login',
      {
        email,
        password,
        device_name: 'panel-admin-portofolio',
      },
      { auth: false },
    )

    setAdminToken(payload.data.token)
    state.user = payload.data.user
    state.initialized = true

    return payload.data.user
  } catch (error) {
    state.error = error.message || 'Login tidak dapat diproses.'
    throw error
  } finally {
    state.loading = false
  }
}

async function ensureUser() {
  if (!getAdminToken()) {
    state.user = null
    state.initialized = true
    return null
  }

  if (state.user && state.initialized) return state.user
  if (ensurePromise) return ensurePromise

  state.loading = true
  state.error = ''

  ensurePromise = (async () => {
    try {
      const payload = await api.get('/admin/me')
      state.user = payload.data
      return state.user
    } catch (error) {
      if (error.status === 401) {
        state.user = null
      } else {
        state.error = error.message || 'Gagal memuat sesi admin.'
      }

      return null
    } finally {
      state.initialized = true
      state.loading = false
      ensurePromise = null
    }
  })()

  return ensurePromise
}

async function logout() {
  state.loading = true

  try {
    await api.post('/admin/logout')
  } catch {
    // Token lokal tetap harus dibersihkan jika backend tidak merespons.
  } finally {
    clearAdminToken()
    state.user = null
    state.initialized = true
    state.loading = false
  }
}

export { ensureUser }

export function useAuth() {
  return {
    state: readonly(state),
    login,
    logout,
    ensureUser,
    hasToken: () => Boolean(getAdminToken()),
  }
}
