import { readonly, ref, type DeepReadonly, type Ref } from 'vue';
import type { Theme } from '.';
import { applyTheme } from './apply';
import { resolveTheme } from './resolve';

/**
 * The active design is resolved once at boot (it follows the domain and does
 * not change during a session). Components read it reactively via `useTheme()`.
 */
const active = ref<Theme>(resolveTheme());

/** Resolve + apply the design for this load. Call once before mounting. */
export function initTheme(): Theme {
    active.value = resolveTheme();
    applyTheme(active.value);
    return active.value;
}

export function useTheme(): DeepReadonly<Ref<Theme>> {
    return readonly(active);
}
