const TOKEN_KEY = 'hafiz-admin-token'

export function getAdminToken() {
  try {
    return window.localStorage.getItem(TOKEN_KEY)
  } catch {
    return null
  }
}

export function setAdminToken(token) {
  try {
    window.localStorage.setItem(TOKEN_KEY, token)
  } catch {
    // Ignore private mode/storage quota errors.
  }
}

export function clearAdminToken() {
  try {
    window.localStorage.removeItem(TOKEN_KEY)
  } catch {
    // Ignore private mode/storage quota errors.
  }
}

export function hasAdminToken() {
  return Boolean(getAdminToken())
}
