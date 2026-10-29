import defaultTheme from 'tailwindcss/defaultTheme'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './main.js',
    './index.vue',
    './components/**/*.{vue,js}',
    './composables/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        canvas: 'rgb(var(--color-bg) / <alpha-value>)',
        panel: 'rgb(var(--color-surface) / <alpha-value>)',
        elevated: 'rgb(var(--color-surface-2) / <alpha-value>)',
        brand: {
          DEFAULT: 'rgb(var(--color-primary) / <alpha-value>)',
          hover: 'rgb(var(--color-primary-hover) / <alpha-value>)',
          dark: 'rgb(var(--color-primary-dark) / <alpha-value>)',
        },
        ink: 'rgb(var(--color-text) / <alpha-value>)',
        muted: 'rgb(var(--color-muted) / <alpha-value>)',
        stroke: 'rgb(var(--color-border) / <alpha-value>)',
      },
      fontFamily: {
        sans: ['Poppins', ...defaultTheme.fontFamily.sans],
      },
      boxShadow: {
        'brand-glow': '0 0 60px -18px rgb(var(--color-primary) / 0.65)',
      },
      keyframes: {
        blink: {
          '0%, 45%': { opacity: '1' },
          '50%, 95%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        'glow-pulse': {
          '0%, 100%': { opacity: '0.55', transform: 'scale(0.96)' },
          '50%': { opacity: '0.9', transform: 'scale(1.04)' },
        },
        'float-soft': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
      },
      animation: {
        blink: 'blink 1s step-end infinite',
        'glow-pulse': 'glow-pulse 7s ease-in-out infinite',
        'float-soft': 'float-soft 6s ease-in-out infinite',
      },
    },
  },
  plugins: [],
}
