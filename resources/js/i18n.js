import { createI18n } from 'vue-i18n';
import es from '../js/locales/es.json';
import en from '../js/locales/en.json';

const messages = {
    es,
    en,
};
const i18n = createI18n({
    locale: 'es',
    messages,
    legacy: false,
});

export default i18n;