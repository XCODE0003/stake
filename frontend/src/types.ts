export interface User {
    id: number;
    name: string;
    email: string;
    referral_code: string | null;
    is_admin: boolean;
    email_verified: boolean;
    approved: boolean;
    needs_approval: boolean;
    fixed_payment_amount: string;
    fixed_payment_currency: string;
    streams_count: number;
    referrals_count: number;
    casino_profit: string;
    your_profit: string;
    wallet_address: string | null;
    wallet_network: string | null;
}

export interface NetworkOption {
    value: string;
    label: string;
}

export interface Ticket {
    id: number;
    message: string;
    reply: string | null;
    status: 'open' | 'answered' | 'closed';
    replied_at: string | null;
    created_at: string | null;
}

export interface RegisterPayload {
    name: string;
    email: string;
    password: string;
    terms: boolean;
}

export interface LoginPayload {
    email: string;
    password: string;
}

export interface WalletPayload {
    wallet_network: string;
    wallet_address: string;
}

/** Laravel validation error bag: field -> messages. */
export type ValidationErrors = Record<string, string[]>;
