import { computed, type ComputedRef } from 'vue';
import { useTheme } from '@/themes/useTheme';
import { messages, type Locale, type MessageKey } from './messages';

/**
 * Brand → locale mapping. Stake is the original English product; every other
 * white-label brand ships in Russian. Because the active theme is baked per
 * build (`VITE_THEME`) and also resolved by hostname at runtime, the locale
 * follows the brand automatically with no extra build flag.
 */
export function localeForTheme(themeKey: string): Locale {
    return themeKey === 'stake' ? 'en' : 'ru';
}

export interface I18n {
    /** Active locale, reactive to the resolved brand/theme. */
    readonly locale: ComputedRef<Locale>;
    /** Translate a key; falls back to the English copy, then the key itself. */
    t: (key: MessageKey) => string;
}

/**
 * Translation helper bound to the active brand's locale. The returned `t` reads
 * the locale reactively, so templates update if the theme changes at runtime.
 */
export function useI18n(): I18n {
    const theme = useTheme();
    const locale = computed<Locale>(() => localeForTheme(theme.value.key));

    function t(key: MessageKey): string {
        return messages[locale.value][key] ?? messages.en[key] ?? key;
    }

    return { locale, t };
}
