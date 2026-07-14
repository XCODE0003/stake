<script setup lang="ts">
import { reactive, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import BaseButton from '@/components/BaseButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import AuthCard from '@/layouts/AuthCard.vue';
import { useI18n } from '@/i18n';
import { firstError, validationErrors } from '@/lib/api';
import { useAuthStore } from '@/stores/auth';
import type { ValidationErrors } from '@/types';

const auth = useAuthStore();
const router = useRouter();
const { t } = useI18n();

const form = reactive({ email: '', password: '' });
const errors = ref<ValidationErrors>({});
const processing = ref(false);

async function submit(): Promise<void> {
    processing.value = true;
    errors.value = {};

    try {
        await auth.login({ ...form });
        const user = auth.user!;
        router.push(
            !user.email_verified
                ? { name: 'verify-email' }
                : user.needs_approval
                  ? { name: 'pending' }
                  : { name: 'dashboard' },
        );
    } catch (error) {
        errors.value = validationErrors(error);
    } finally {
        processing.value = false;
    }
}
</script>

<template>
    <AuthCard :title="t('login.title')" :description="t('login.description')">
        <form class="flex flex-col gap-5" @submit.prevent="submit">
            <div class="grid gap-2">
                <label for="email" class="text-sm font-medium">
                    {{ t('login.email') }}
                </label>
                <BaseInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    placeholder="name@example.com"
                    required
                />
                <p
                    v-if="firstError(errors, 'email')"
                    class="text-sm text-destructive"
                >
                    {{ firstError(errors, 'email') }}
                </p>
            </div>

            <div class="grid gap-2">
                <label for="password" class="text-sm font-medium">
                    {{ t('login.password') }}
                </label>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    autocomplete="current-password"
                    :placeholder="t('login.passwordPlaceholder')"
                    required
                />
                <p
                    v-if="firstError(errors, 'password')"
                    class="text-sm text-destructive"
                >
                    {{ firstError(errors, 'password') }}
                </p>
            </div>

            <BaseButton type="submit" class="w-full" :disabled="processing">
                <BaseSpinner v-if="processing" />
                {{ t('login.submit') }}
            </BaseButton>
        </form>

        <p class="mt-6 text-center text-sm text-muted">
            {{ t('login.noAccount') }}
            <RouterLink
                :to="{ name: 'register' }"
                class="text-primary hover:underline"
            >
                {{ t('login.registerLink') }}
            </RouterLink>
        </p>
    </AuthCard>
</template>
