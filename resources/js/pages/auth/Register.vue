<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Create an Account',
        description: 'Register to access the affiliate dashboard',
    },
});

const agreed = ref(false);
</script>

<template>
    <Head title="Register" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5"
    >
        <div class="grid gap-2">
            <Label for="email"
                >Email <span class="text-destructive">*</span></Label
            >
            <Input
                id="email"
                type="email"
                name="email"
                required
                autofocus
                :tabindex="1"
                autocomplete="email"
                placeholder="name@example.com"
            />
            <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
            <Label for="name"
                >Username <span class="text-destructive">*</span></Label
            >
            <Input
                id="name"
                type="text"
                name="name"
                required
                :tabindex="2"
                autocomplete="username"
                placeholder="Username"
            />
            <InputError :message="errors.name" />
        </div>

        <div class="grid gap-2">
            <Label for="password"
                >Password <span class="text-destructive">*</span></Label
            >
            <PasswordInput
                id="password"
                name="password"
                required
                :tabindex="3"
                autocomplete="new-password"
                placeholder="Password"
                :passwordrules="passwordRules"
            />
            <InputError :message="errors.password" />
        </div>

        <div class="grid gap-2">
            <Label for="referral_code">Referral Code (Optional)</Label>
            <Input
                id="referral_code"
                type="text"
                name="referral_code"
                :tabindex="4"
                autocomplete="off"
                placeholder="Referral Code"
            />
            <InputError :message="errors.referral_code" />
        </div>

        <div class="grid gap-2">
            <Label for="terms" class="flex items-start gap-3 text-sm">
                <Checkbox
                    id="terms"
                    v-model="agreed"
                    name="terms"
                    :tabindex="5"
                    class="mt-0.5"
                />
                <span class="text-muted-foreground">
                    I am 18 or older and agree to the
                    <a
                        href="#"
                        class="text-primary underline-offset-4 hover:underline"
                        >Terms and Conditions</a
                    >
                    <span class="text-destructive">*</span>
                </span>
            </Label>
            <InputError :message="errors.terms" />
        </div>

        <Button
            type="submit"
            class="mt-2 h-11 w-full text-base font-semibold"
            :tabindex="6"
            :disabled="processing || !agreed"
            data-test="register-user-button"
        >
            <Spinner v-if="processing" />
            Register
        </Button>

        <div class="text-center text-sm text-muted-foreground">
            Already have an account?
            <TextLink :href="login()" :tabindex="7">Log in</TextLink>
        </div>
    </Form>
</template>
