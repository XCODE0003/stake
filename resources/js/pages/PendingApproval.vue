<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Clock } from '@lucide/vue';
import { ref } from 'vue';
import StakeLogo from '@/components/StakeLogo.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';

defineProps<{
    email?: string;
}>();

const refreshing = ref(false);

function refresh(): void {
    refreshing.value = true;
    router.reload({ onFinish: () => (refreshing.value = false) });
}
</script>

<template>
    <Head title="Awaiting approval" />

    <div
        class="flex min-h-svh flex-col items-center justify-center bg-background p-4 sm:p-6"
    >
        <div class="w-full max-w-md">
            <div class="mb-8 flex justify-center">
                <StakeLogo class="h-9 w-auto" />
            </div>

            <div class="rounded-lg bg-card p-8 text-center shadow-xl">
                <div
                    class="mx-auto mb-5 flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary"
                >
                    <Clock class="size-7" />
                </div>

                <h1 class="text-xl font-bold tracking-tight">
                    Please wait for your account to be approved
                </h1>

                <p class="mt-3 text-sm text-muted-foreground">
                    Your email is verified. A manager will review your
                    application and activate the account. Once approved, you'll
                    see your terms and be able to link a payout wallet.
                </p>

                <p
                    v-if="email"
                    class="mt-4 rounded-md bg-muted px-3 py-2 text-sm"
                >
                    {{ email }}
                </p>

                <div class="mt-6 flex flex-col gap-3">
                    <Button :disabled="refreshing" @click="refresh">
                        <Spinner v-if="refreshing" />
                        Check status
                    </Button>

                    <Link
                        :href="logout()"
                        as="button"
                        class="text-sm text-muted-foreground hover:underline"
                    >
                        Log out
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
