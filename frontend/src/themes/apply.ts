import type { Theme } from '.';

const FONT_LINK_ID = 'brand-font';

/** Point the existing favicon `<link>` (or create one) at the brand icon. */
function setFavicon(href: string): void {
    let link = document.querySelector<HTMLLinkElement>('link[rel="icon"]');
    if (!link) {
        link = document.createElement('link');
        link.rel = 'icon';
        document.head.appendChild(link);
    }
    link.type = href.endsWith('.png') ? 'image/png' : 'image/svg+xml';
    link.href = href;
}

/** Load (or remove) the brand web font without duplicating link tags. */
function setBrandFont(href: string | undefined): void {
    const existing = document.getElementById(FONT_LINK_ID);

    if (!href) {
        existing?.remove();
        return;
    }
    if (existing instanceof HTMLLinkElement && existing.href === href) {
        return;
    }

    const link = existing instanceof HTMLLinkElement
        ? existing
        : document.createElement('link');
    link.id = FONT_LINK_ID;
    link.rel = 'stylesheet';
    link.href = href;
    if (!link.isConnected) {
        document.head.appendChild(link);
    }
}

/**
 * Apply a theme to the document: the `data-theme` attribute drives every CSS
 * variable override in `style.css`, plus title/favicon/font side effects.
 */
export function applyTheme(theme: Theme): void {
    document.documentElement.dataset.theme = theme.key;
    document.title = theme.title;
    setFavicon(theme.favicon);
    setBrandFont(theme.fontHref);
}
