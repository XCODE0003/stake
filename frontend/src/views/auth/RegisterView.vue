<script setup lang="ts">
import { reactive, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import BaseButton from '@/components/BaseButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import AuthCard from '@/layouts/AuthCard.vue';
import { firstError, validationErrors } from '@/lib/api';
import { useAuthStore } from '@/stores/auth';
import type { ValidationErrors } from '@/types';

const auth = useAuthStore();
const router = useRouter();

const form = reactive({
    email: '',
    name: '',
    password: '',
    terms: false,
});
const errors = ref<ValidationErrors>({});
const processing = ref(false);

async function submit(): Promise<void> {
    processing.value = true;
    errors.value = {};

    try {
        await auth.register({ ...form });
        router.push({ name: 'verify-email' });
    } catch (error) {
        errors.value = validationErrors(error);
    } finally {
        processing.value = false;
    }
}
</script>

<template>
    <AuthCard
        title="Create an Account"
        description="Register to access the affiliate dashboard"
    >
        <form class="flex flex-col gap-5" @submit.prevent="submit">
            <div class="grid gap-2">
                <label for="email" class="text-sm font-medium">
                    Email <span class="text-destructive">*</span>
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
                <label for="name" class="text-sm font-medium">
                    Username <span class="text-destructive">*</span>
                </label>
                <BaseInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="username"
                    placeholder="Username"
                    required
                />
                <p
                    v-if="firstError(errors, 'name')"
                    class="text-sm text-destructive"
                >
                    {{ firstError(errors, 'name') }}
                </p>
            </div>

            <div class="grid gap-2">
                <label for="password" class="text-sm font-medium">
                    Password <span class="text-destructive">*</span>
                </label>
                <PasswordInput
                    id="password"
                    v-model="form.password"
                    autocomplete="new-password"
                    placeholder="Password"
                    required
                />
                <p
                    v-if="firstError(errors, 'password')"
                    class="text-sm text-destructive"
                >
                    {{ firstError(errors, 'password') }}
                </p>
            </div>

            <label class="flex items-start gap-3 text-sm">
                <input
                    v-model="form.terms"
                    type="checkbox"
                    class="mt-0.5 size-4 rounded border-input-border bg-sunken accent-primary"
                />
                <span class="text-muted">
                    I am 18 or older and agree to the
                    <a href="#" class="text-primary hover:underline">
                        Terms and Conditions
                    </a>
                    <span class="text-destructive">*</span>
                </span>
            </label>
            <p
                v-if="firstError(errors, 'terms')"
                class="-mt-3 text-sm text-destructive"
            >
                {{ firstError(errors, 'terms') }}
            </p>

            <BaseButton
                type="submit"
                class="w-full"
                :disabled="processing || !form.terms"
            >
                <BaseSpinner v-if="processing" />
                Register
            </BaseButton>
        </form>

        <p class="mt-6 text-center text-sm text-muted">
            Already have an account?
            <RouterLink
                :to="{ name: 'login' }"
                class="text-primary hover:underline"
            >
                Log in
            </RouterLink>
        </p>
    </AuthCard>
</template>
