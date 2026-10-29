import { onBeforeUnmount, onMounted } from 'vue'

/**
 * Animates every `.reveal` element once it enters the viewport.
 * The observer also watches DOM additions so content added later
 * receives the same treatment.
 */
export function useReveal() {
  let intersectionObserver
  let mutationObserver

  onMounted(() => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
    const items = document.querySelectorAll('.reveal')

    if (!('IntersectionObserver' in window) || reduceMotion) {
      items.forEach((item) => item.classList.add('is-visible'))
      return
    }

    intersectionObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return
          entry.target.classList.add('is-visible')
          intersectionObserver.unobserve(entry.target)
        })
      },
      { threshold: 0.12, rootMargin: '0px 0px -48px 0px' },
    )

    const observe = (element) => {
      if (!(element instanceof Element)) return
      if (element.classList.contains('reveal') && !element.classList.contains('is-visible')) {
        intersectionObserver.observe(element)
      }
      element
        .querySelectorAll('.reveal:not(.is-visible)')
        .forEach((child) => intersectionObserver.observe(child))
    }

    items.forEach(observe)

    mutationObserver = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => observe(node))
      })
    })

    mutationObserver.observe(document.body, {
      childList: true,
      subtree: true,
    })
  })

  onBeforeUnmount(() => {
    intersectionObserver?.disconnect()
    mutationObserver?.disconnect()
  })
}
