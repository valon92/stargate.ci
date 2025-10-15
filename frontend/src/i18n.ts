import { createI18n } from 'vue-i18n'
import en from './locales/en.json'

const messages = {
  en
}

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  messages
})

export default i18n
