import { createI18n } from 'vue-i18n';
import kk from './kk.json';
import ru from './ru.json';
import en from './en.json';

const i18n = createI18n({
  locale: localStorage.getItem('entries_lang') || 'kk', //import.meta.env.VITE_DEFAULT_LOCALE, 
	fallbackLocale:'en',
  messages: {
    kk,
		ru,
    en,
  },
  allowComposition: true, 
})

export default i18n;