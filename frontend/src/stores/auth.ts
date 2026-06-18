import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import api, { getToken, setToken } from '@/lib/api';
import type {
    LoginPayload,
    NetworkOption,
    RegisterPayload,
    Ticket,
    User,
    WalletPayload,
} from '@/types';

export const useAuthStore = defineStore('auth', () => {
    const token = ref<string | null>(getToken());
    const user = ref<User | null>(null);
    // True once the initial "who am I" request has resolved.
    const ready = ref(false);

    const isAuthenticated = computed(() => token.value !== null);

    function applyToken(value: string | null): void {
        token.value = value;
        setToken(value);
    }

    async function register(payload: RegisterPayload): Promise<void> {
        const { data } = await api.post('/register', payload);
        applyToken(data.token);
        user.value = data.data;
    }

    async function login(payload: LoginPayload): Promise<void> {
        const { data } = await api.post('/login', payload);
        applyToken(data.token);
        user.value = data.data;
    }

    async function fetchMe(): Promise<void> {
        if (token.value === null) {
            ready.value = true;
            return;
        }

        try {
            const { data } = await api.get('/me');
            user.value = data.data;
        } catch {
            applyToken(null);
            user.value = null;
        } finally {
            ready.value = true;
        }
    }

    async function logout(): Promise<void> {
        try {
            await api.post('/logout');
        } catch {
            // Ignore — we clear the local session regardless.
        }
        applyToken(null);
        user.value = null;
    }

    async function verifyEmail(code: string): Promise<void> {
        const { data } = await api.post('/email/verify', { code });
        user.value = data.data;
    }

    async function resendCode(): Promise<void> {
        await api.post('/email/resend');
    }

    async function updateWallet(payload: WalletPayload): Promise<void> {
        const { data } = await api.put('/wallet', payload);
        user.value = data.data;
    }

    async function fetchNetworks(): Promise<NetworkOption[]> {
        const { data } = await api.get('/networks');
        return data.data;
    }

    async function fetchTickets(): Promise<Ticket[]> {
        const { data } = await api.get('/tickets');
        return data.data;
    }

    async function createTicket(message: string): Promise<Ticket> {
        const { data } = await api.post('/tickets', { message });
        return data.data;
    }

    return {
        token,
        user,
        ready,
        isAuthenticated,
        register,
        login,
        fetchMe,
        logout,
        verifyEmail,
        resendCode,
        updateWallet,
        fetchNetworks,
        fetchTickets,
        createTicket,
    };
});
