import axios from 'axios';
import type { ValidationErrors } from '@/types';

const TOKEN_KEY = 'stake_token';

export function getToken(): string | null {
    return localStorage.getItem(TOKEN_KEY);
}

export function setToken(token: string | null): void {
    if (token) {
        localStorage.setItem(TOKEN_KEY, token);
    } else {
        localStorage.removeItem(TOKEN_KEY);
    }
}

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8001/api',
    headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
    const token = getToken();
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            setToken(null);
        }
        return Promise.reject(error);
    },
);

/** Extract Laravel validation errors from an axios error, if any. */
export function validationErrors(error: unknown): ValidationErrors {
    if (axios.isAxiosError(error) && error.response?.status === 422) {
        return (error.response.data?.errors ?? {}) as ValidationErrors;
    }
    return {};
}

/** First error message for a field, or undefined. */
export function firstError(
    errors: ValidationErrors,
    field: string,
): string | undefined {
    return errors[field]?.[0];
}

export default api;
