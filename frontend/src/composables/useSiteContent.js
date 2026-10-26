import { readonly, ref } from 'vue'
import { defaultSiteContent } from '../data/siteDefaults'

const API_BASE_URL = (import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8001/api').replace(
  /\/+$/,
  '',
)

function cloneFallback() {
  return JSON.parse(JSON.stringify(defaultSiteContent))
}

function mergeSiteContent(incoming) {
  if (!incoming || typeof incoming !== 'object') return cloneFallback()

  const profile = { ...defaultSiteContent.profile, ...(incoming.profile ?? {}) }

  if (!profile.portrait_url) {
    profile.portrait_url = defaultSiteContent.profile.portrait_url
  }

  const sections = { ...defaultSiteContent.sections }

  for (const [key, value] of Object.entries(incoming.sections ?? {})) {
    if (!value || typeof value !== 'object') continue
    sections[key] = { ...(defaultSiteContent.sections[key] ?? {}), ...value }
  }

  return {
    profile,
    sections,
    social_links: Array.isArray(incoming.social_links)
      ? incoming.social_links
      : defaultSiteContent.social_links,
    services: Array.isArray(incoming.services) ? incoming.services : defaultSiteContent.services,
    skill_groups: Array.isArray(incoming.skill_groups)
      ? incoming.skill_groups
      : defaultSiteContent.skill_groups,
    experiences: Array.isArray(incoming.experiences)
      ? incoming.experiences
      : defaultSiteContent.experiences,
    projects: Array.isArray(incoming.projects) ? incoming.projects : defaultSiteContent.projects,
    contact_details: Array.isArray(incoming.contact_details)
      ? incoming.contact_details
      : defaultSiteContent.contact_details,
    contact_opportunities: Array.isArray(incoming.contact_opportunities)
      ? incoming.contact_opportunities
      : defaultSiteContent.contact_opportunities,
  }
}

const content = ref(cloneFallback())
const isLoaded = ref(false)
const isLoading = ref(false)
let loadPromise = null

export async function loadSiteContent(force = false) {
  if (loadPromise && !force) return loadPromise

  isLoading.value = true

  loadPromise = (async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/site`, {
        headers: {
          Accept: 'application/json',
        },
      })

      if (!response.ok) throw new Error(`API site merespons ${response.status}`)

      const payload = await response.json()
      content.value = mergeSiteContent(payload?.data)
      isLoaded.value = true
    } catch {
      // Data fallback tetap dipakai saat backend belum tersedia.
      content.value = mergeSiteContent(null)
    } finally {
      isLoading.value = false
    }

    return content.value
  })()

  try {
    return await loadPromise
  } finally {
    loadPromise = null
  }
}

export function useSiteContent() {
  if (!isLoaded.value && !loadPromise) {
    void loadSiteContent()
  }

  return {
    content: readonly(content),
    isLoaded: readonly(isLoaded),
    isLoading: readonly(isLoading),
    reload: () => loadSiteContent(true),
  }
}
