/**
 * Brand/design registry.
 *
 * Each theme bundles the brand metadata that lives in TypeScript (name, logo,
 * document title, optional web font). The *visual* palette for each theme is
 * defined in `style.css` under `html[data-theme='<key>']`; the two are linked
 * by the `key` field. Add a new design by adding an entry here plus a matching
 * `html[data-theme='...']` block in `style.css`.
 */
export interface Theme {
    /** Stable identifier, also used as the `data-theme` attribute value. */
    readonly key: string;
    /** Human-readable brand name, shown in copy ("© Vodka", etc.). */
    readonly brand: string;
    /** `document.title` for this brand. */
    readonly title: string;
    /** Logo served from `public/` (wordmark, sized by height). */
    readonly logo: string;
    /** Favicon served from `public/`. */
    readonly favicon: string;
    /**
     * Optional web font to load when this theme is active. `href` is injected
     * as a stylesheet link; the matching `font-family` is set in CSS.
     */
    readonly fontHref?: string;
}

export const themes = {
    stake: {
        key: 'stake',
        brand: 'Stake',
        title: 'Stake — Affiliate',
        logo: '/logo.svg',
        favicon: '/logo.svg',
    },
    vodka: {
        key: 'vodka',
        brand: 'Vodka',
        title: 'Vodka — Affiliate',
        logo: '/vodka.svg',
        favicon: '/vodka.svg',
        fontHref:
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
    },
    dragon: {
        key: 'dragon',
        brand: 'Dragon Money',
        title: 'Dragon Money — Affiliate',
        logo: '/dragon.svg',
        favicon: '/dragon.svg',
        fontHref:
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
    },
    qzino: {
        key: 'qzino',
        brand: 'Qzino',
        title: 'Qzino — Affiliate',
        logo: '/qzino.svg',
        favicon: '/qzino.svg',
        fontHref:
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
    },
    '1win': {
        key: '1win',
        brand: '1win',
        title: '1win — Affiliate',
        logo: '/1win.svg',
        favicon: '/1win.svg',
        fontHref:
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
    },
    zooma: {
        key: 'zooma',
        brand: 'Zooma',
        title: 'Zooma — Affiliate',
        logo: '/zooma2026logo.png',
        favicon: '/zooma2026logo.png',
        fontHref:
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
    },
} as const satisfies Record<string, Theme>;

export type ThemeKey = keyof typeof themes;

export const DEFAULT_THEME: ThemeKey = 'stake';

export function isThemeKey(value: string | null | undefined): value is ThemeKey {
    return value != null && value in themes;
}
