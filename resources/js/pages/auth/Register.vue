<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    organisation: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase>

        <Head title="Register" />

        <form @submit.prevent="submit"
            class="lg:mx-auto w-full sm:max-w-4xl px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Organization</Label>
                    <Input id="organisation_id" type="text" required autofocus :tabindex="1" autocomplete="organization"
                        v-model="form.organisation" placeholder="Organization name" class="h-10" />
                    <InputError :message="form.errors.organisation" />
                </div>

                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                        v-model="form.name" placeholder="Full name" class="h-10" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email"
                        placeholder="email@example.com" class="h-10"/>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" required :tabindex="3" autocomplete="new-password"
                        v-model="form.password" placeholder="Password" class="h-10" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input id="password_confirmation" type="password" required :tabindex="4" autocomplete="new-password"
                        v-model="form.password_confirmation" placeholder="Confirm password" class="h-10" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>


            </div>

            <div class="flex justify-end gap-10 mt-3">
                <div class="text-sm text-muted-foreground mt-4">
                    <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Already registered?
                    </TextLink>
                </div>
                <div>
                <Button type="submit" class="mt-2" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    register
                </Button>
                </div>
            </div>
        </form>
    </AuthBase>
</template>
