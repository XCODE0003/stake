<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { BadgeDollarSign, Check, Tv, Users, Wallet } from '@lucide/vue';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';

interface NetworkOption {
    value: string;
    label: string;
}

const props = defineProps<{
    stats: {
        fixedPaymentAmount: string;
        fixedPaymentCurrency: string;
        streamsCount: number;
        referralsCount: number;
    };
    wallet: {
        address: string | null;
        network: string | null;
    };
    networkOptions: NetworkOption[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
    },
});

const page = usePage();
const userName = computed(() => page.props.auth.user?.name ?? '');

const formattedPayment = computed(() => {
    const amount = Number(props.stats.fixedPaymentAmount);

    return Number.isNaN(amount)
        ? props.stats.fixedPaymentAmount
        : new Intl.NumberFormat('en-US', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 2,
          }).format(amount);
});

const networkLabel = computed(
    () =>
        props.networkOptions.find((o) => o.value === props.wallet.network)
            ?.label ?? props.wallet.network,
);

const maskedAddress = computed(() => {
    const address = props.wallet.address;

    if (!address) {
        return '';
    }

    return address.length > 14
        ? `${address.slice(0, 8)}…${address.slice(-6)}`
        : address;
});

const dialogOpen = ref(false);

const walletForm = useForm({
    wallet_network: props.wallet.network ?? '',
    wallet_address: props.wallet.address ?? '',
});

function submitWallet(): void {
    walletForm.put('/wallet', {
        preserveScroll: true,
        onSuccess: () => (dialogOpen.value = false),
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">
                Welcome back, {{ userName }}
            </h1>
            <p class="text-sm text-muted-foreground">
                Here are your current affiliate terms.
            </p>
        </div>

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Fixed payment -->
            <Card class="transition-colors hover:border-primary/40">
                <CardContent class="flex items-start justify-between gap-4 p-6">
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground">
                            Fixed payment
                        </p>
                        <p class="text-3xl font-bold text-stake-green">
                            {{ formattedPayment }}
                            <span class="text-lg text-muted-foreground">
                                {{ stats.fixedPaymentCurrency }}
                            </span>
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-stake-green/10 text-stake-green"
                    >
                        <BadgeDollarSign class="size-5" />
                    </span>
                </CardContent>
            </Card>

            <!-- Streams -->
            <Card class="transition-colors hover:border-primary/40">
                <CardContent class="flex items-start justify-between gap-4 p-6">
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground">Streams</p>
                        <p class="text-3xl font-bold">
                            {{ stats.streamsCount }}
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <Tv class="size-5" />
                    </span>
                </CardContent>
            </Card>

            <!-- Referrals -->
            <Card class="transition-colors hover:border-primary/40">
                <CardContent class="flex items-start justify-between gap-4 p-6">
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground">Referrals</p>
                        <p class="text-3xl font-bold">
                            {{ stats.referralsCount }}
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-[#40c8d0]/10 text-[#40c8d0]"
                    >
                        <Users class="size-5" />
                    </span>
                </CardContent>
            </Card>
        </div>

        <!-- Payout wallet -->
        <Card>
            <CardContent class="p-6">
                <div class="flex items-center gap-3">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <Wallet class="size-5" />
                    </span>
                    <div>
                        <h2 class="text-base font-semibold">Payout wallet</h2>
                        <p class="text-sm text-muted-foreground">
                            Link a wallet address to receive payouts.
                        </p>
                    </div>
                </div>

                <div class="mt-5 border-t border-border pt-5">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div
                            v-if="wallet.address"
                            class="flex items-center gap-3"
                        >
                            <span
                                class="flex size-8 items-center justify-center rounded-full bg-stake-green/15 text-stake-green"
                            >
                                <Check class="size-4" />
                            </span>
                            <div>
                                <p class="text-xs text-muted-foreground">
                                    {{ networkLabel }}
                                </p>
                                <p class="font-mono text-sm">
                                    {{ maskedAddress }}
                                </p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">
                            No wallet linked yet.
                        </p>

                        <Dialog v-model:open="dialogOpen">
                            <DialogTrigger as-child>
                                <Button class="font-semibold">
                                    {{
                                        wallet.address
                                            ? 'Edit wallet'
                                            : 'Link payout wallet'
                                    }}
                                </Button>
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle
                                        >Link payout wallet</DialogTitle
                                    >
                                    <DialogDescription>
                                        Select a network and enter your wallet
                                        address.
                                    </DialogDescription>
                                </DialogHeader>

                                <form
                                    class="grid gap-4"
                                    @submit.prevent="submitWallet"
                                >
                                    <div class="grid gap-2">
                                        <Label for="wallet_network">
                                            Network
                                        </Label>
                                        <Select
                                            v-model="walletForm.wallet_network"
                                        >
                                            <SelectTrigger
                                                id="wallet_network"
                                                class="w-full"
                                            >
                                                <SelectValue
                                                    placeholder="Select a network"
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="option in networkOptions"
                                                    :key="option.value"
                                                    :value="option.value"
                                                >
                                                    {{ option.label }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError
                                            :message="
                                                walletForm.errors.wallet_network
                                            "
                                        />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="wallet_address">
                                            Wallet address
                                        </Label>
                                        <Input
                                            id="wallet_address"
                                            v-model="walletForm.wallet_address"
                                            placeholder="e.g. TXXXXXXXXXXXXXXXXXXX"
                                            autocomplete="off"
                                        />
                                        <InputError
                                            :message="
                                                walletForm.errors.wallet_address
                                            "
                                        />
                                    </div>

                                    <DialogFooter>
                                        <Button
                                            type="submit"
                                            :disabled="walletForm.processing"
                                        >
                                            <Spinner
                                                v-if="walletForm.processing"
                                            />
                                            Save
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
