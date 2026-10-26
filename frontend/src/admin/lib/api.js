import { clearAdminToken, getAdminToken } from './token'

export const API_BASE_URL = (
  import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8001/api'
).replace(/\/+$/, '')

export class ApiError extends Error {
  constructor(message, status = 0, errors = {}) {
    super(message)
    this.name = 'ApiError'
    this.status = status
    this.errors = errors
  }
}

function fallbackMessage(status) {
  if (status === 401) return 'Sesi admin telah berakhir. Silakan login kembali.'
  if (status === 403) return 'Anda tidak memiliki akses ke aksi ini.'
  if (status === 404) return 'Data yang diminta tidak ditemukan.'
  if (status === 422) return 'Periksa kembali data yang Anda masukkan.'
  if (status === 429) return 'Terlalu banyak percobaan. Coba lagi sebentar lagi.'
  if (status >= 500) return 'Terjadi kesalahan pada server.'
  return 'Permintaan tidak dapat diproses.'
}

export async function apiRequest(path, options = {}) {
  const {
    method = 'GET',
    body,
    headers = {},
    auth = true,
  } = options

  const token = auth ? getAdminToken() : null
  const isFormData = typeof FormData !== 'undefined' && body instanceof FormData
  const requestHeaders = {
    Accept: 'application/json',
    ...headers,
  }

  if (!isFormData && body !== undefined) {
    requestHeaders['Content-Type'] = 'application/json'
  }

  if (token) {
    requestHeaders.Authorization = `Bearer ${token}`
  }

  let response

  try {
    response = await fetch(`${API_BASE_URL}${path}`, {
      method,
      headers: requestHeaders,
      body: isFormData ? body : body !== undefined ? JSON.stringify(body) : undefined,
    })
  } catch {
    throw new ApiError(
      'Tidak dapat terhubung ke backend. Pastikan Laravel berjalan di port yang benar.',
      0,
    )
  }

  const payload = await response.json().catch(() => null)

  if (response.status === 401) {
    clearAdminToken()

    const currentPath = window.location.pathname.replace(/\/+$/, '')
    if (!currentPath.endsWith('/admin')) {
      window.location.assign('/admin?expired=1')
    }

    throw new ApiError(fallbackMessage(401), 401, payload?.errors ?? {})
  }

  if (!response.ok) {
    throw new ApiError(
      payload?.message || fallbackMessage(response.status),
      response.status,
      payload?.errors ?? {},
    )
  }

  return payload
}

export const api = {
  get(path, options = {}) {
    return apiRequest(path, { ...options, method: 'GET' })
  },
  post(path, body, options = {}) {
    return apiRequest(path, { ...options, method: 'POST', body })
  },
  put(path, body, options = {}) {
    return apiRequest(path, { ...options, method: 'PUT', body })
  },
  patch(path, body, options = {}) {
    return apiRequest(path, { ...options, method: 'PATCH', body })
  },
  delete(path, options = {}) {
    return apiRequest(path, { ...options, method: 'DELETE' })
  },
}
