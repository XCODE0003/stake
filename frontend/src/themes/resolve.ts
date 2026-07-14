import { DEFAULT_THEME, isThemeKey, themes, type Theme, type ThemeKey } from '.';

/**
 * Resolve which design to show.
 *
 * Resolution order (first match wins):
 *   1. `?theme=<key>` query param — for quick testing; persisted to
 *      localStorage so it survives client-side navigation. `?theme=reset`
 *      clears the override and falls back to the host-based mapping.
 *   2. A previously persisted query override (localStorage).
 *   3. `VITE_THEME` build-time env — for per-brand builds that force a design.
 *   4. The hostname → theme mapping below (the production default: one build
 *      serving multiple domains).
 */

const OVERRIDE_KEY = 'theme_override';

/** Hostname substring → theme. First matching pattern wins. */
const HOSTNAME_PATTERNS: ReadonlyArray<readonly [RegExp, ThemeKey]> = [
    [/vodka/i, 'vodka'],
    [/dragon|drgn/i, 'dragon'],
    [/qzino/i, 'qzino'],
    [/1win|onewin/i, '1win'],
    [/zooma/i, 'zooma'],
    [/stake/i, 'stake'],
];

function themeFromHostname(hostname: string): ThemeKey {
    for (const [pattern, key] of HOSTNAME_PATTERNS) {
        if (pattern.test(hostname)) {
            return key;
        }
    }
    return DEFAULT_THEME;
}

function queryOverride(search: string): ThemeKey | null {
    const requested = new URLSearchParams(search).get('theme');
    if (requested === null) {
        return null;
    }

    try {
        if (requested === 'reset') {
            localStorage.removeItem(OVERRIDE_KEY);
        } else if (isThemeKey(requested)) {
            localStorage.setItem(OVERRIDE_KEY, requested);
        }
    } catch {
        // localStorage may be unavailable (private mode); ignore.
    }

    return isThemeKey(requested) ? requested : null;
}

function persistedOverride(): ThemeKey | null {
    try {
        const stored = localStorage.getItem(OVERRIDE_KEY);
        return isThemeKey(stored) ? stored : null;
    } catch {
        return null;
    }
}

export function resolveThemeKey(
    location: Pick<Location, 'hostname' | 'search'> = window.location,
): ThemeKey {
    const fromQuery = queryOverride(location.search);
    if (fromQuery) {
        return fromQuery;
    }

    const fromStorage = persistedOverride();
    if (fromStorage) {
        return fromStorage;
    }

    const fromEnv = import.meta.env.VITE_THEME;
    if (isThemeKey(fromEnv)) {
        return fromEnv;
    }

    return themeFromHostname(location.hostname);
}

export function resolveTheme(
    location?: Pick<Location, 'hostname' | 'search'>,
): Theme {
    return themes[resolveThemeKey(location)];
}
