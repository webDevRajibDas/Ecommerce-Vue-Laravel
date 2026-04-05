<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Register - E-Shop" />

    <div class="min-h-screen w-full flex">
        <!-- Left Side - Branding/Promotional -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary via-primary-600 to-primary-800 relative overflow-hidden items-center justify-center">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-20 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-white/5 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>

            <!-- Floating Shapes -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-1/4 left-1/4 w-16 h-16 border-2 border-white/20 rounded-full animate-bounce" style="animation-duration: 3s;"></div>
                <div class="absolute bottom-1/3 right-1/4 w-12 h-12 border-2 border-white/20 rounded-full animate-bounce" style="animation-duration: 4s;"></div>
                <div class="absolute top-1/3 right-1/3 w-8 h-8 bg-white/10 rounded-full animate-bounce" style="animation-duration: 5s;"></div>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 text-center text-white px-12">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6 backdrop-blur-sm">
                        <i class="pi pi-shopping-bag text-4xl"></i>
                    </div>
                    <h1 class="text-5xl font-bold mb-4">E-Shop</h1>
                    <p class="text-xl opacity-90 mb-8">Your Premium Shopping Destination</p>
                </div>

                <div class="space-y-6 max-w-md mx-auto">
                    <div class="flex items-center gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-tag text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="font-semibold text-lg">Exclusive Deals</h3>
                            <p class="text-sm opacity-80">Up to 50% off on selected items</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-truck text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="font-semibold text-lg">Free Shipping</h3>
                            <p class="text-sm opacity-80">On orders over $50</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-shield text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="font-semibold text-lg">Secure Shopping</h3>
                            <p class="text-sm opacity-80">100% protected transactions</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial -->
                <div class="mt-12 p-6 bg-white/10 rounded-2xl backdrop-blur-sm max-w-lg mx-auto">
                    <p class="text-lg italic opacity-90 mb-4">"The best online shopping experience I've ever had. Fast delivery and amazing quality!"</p>
                    <div class="flex items-center justify-center gap-2">
                        <div class="flex">
                            <i class="pi pi-star text-yellow-400"></i>
                            <i class="pi pi-star text-yellow-400"></i>
                            <i class="pi pi-star text-yellow-400"></i>
                            <i class="pi pi-star text-yellow-400"></i>
                            <i class="pi pi-star text-yellow-400"></i>
                        </div>
                        <span class="text-sm opacity-80">4.9/5 from 10k+ reviews</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-surface-50 dark:bg-surface-950 p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-xl mb-4">
                        <i class="pi pi-shopping-bag text-3xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-surface-900 dark:text-surface-100">E-Shop</h1>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-surface-900 dark:text-surface-100 mb-2">Create Account</h2>
                    <p class="text-surface-600 dark:text-surface-400">Join us today and start shopping</p>
                </div>

                <!-- Register Form -->
                <Form
                    v-bind="store.form()"
                    :reset-on-success="['password', 'password_confirmation']"
                    v-slot="{ errors, processing }"
                >
                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div class="form-group">
                            <Label for="name" class="text-surface-700 dark:text-surface-300 font-medium mb-2 block">
                                Full Name
                            </Label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">
                                    <i class="pi pi-user"></i>
                                </div>
                                <Input
                                    id="name"
                                    type="text"
                                    name="name"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="name"
                                    placeholder="Enter your full name"
                                    class="w-full pl-12 h-12"
                                />
                            </div>
                            <Transition name="fade">
                                <InputError v-if="errors.name" :message="errors.name" class="mt-2" />
                            </Transition>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <Label for="email" class="text-surface-700 dark:text-surface-300 font-medium mb-2 block">
                                Email Address
                            </Label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">
                                    <i class="pi pi-envelope"></i>
                                </div>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    :tabindex="2"
                                    autocomplete="email"
                                    placeholder="Enter your email"
                                    class="w-full pl-12 h-12"
                                />
                            </div>
                            <Transition name="fade">
                                <InputError v-if="errors.email" :message="errors.email" class="mt-2" />
                            </Transition>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <Label for="password" class="text-surface-700 dark:text-surface-300 font-medium mb-2 block">
                                Password
                            </Label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">
                                    <i class="pi pi-lock"></i>
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    :tabindex="3"
                                    autocomplete="new-password"
                                    placeholder="Create a password"
                                    class="w-full pl-12 h-12"
                                />
                            </div>
                            <Transition name="fade">
                                <InputError v-if="errors.password" :message="errors.password" class="mt-2" />
                            </Transition>
                        </div>

                        <!-- Password Confirmation Field -->
                        <div class="form-group">
                            <Label for="password_confirmation" class="text-surface-700 dark:text-surface-300 font-medium mb-2 block">
                                Confirm Password
                            </Label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-surface-400">
                                    <i class="pi pi-lock"></i>
                                </div>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    :tabindex="4"
                                    autocomplete="new-password"
                                    placeholder="Confirm your password"
                                    class="w-full pl-12 h-12"
                                />
                            </div>
                            <Transition name="fade">
                                <InputError v-if="errors.password_confirmation" :message="errors.password_confirmation" class="mt-2" />
                            </Transition>
                        </div>

                        <!-- Submit Button -->
                        <Button
                            type="submit"
                            class="w-full h-12 text-lg font-semibold"
                            :tabindex="5"
                            :disabled="processing"
                            data-test="register-button"
                        >
                            <div class="flex items-center justify-center gap-2">
                                <LoaderCircle v-if="processing" class="animate-spin" />
                                <span>{{ processing ? 'Creating Account...' : 'Create Account' }}</span>
                            </div>
                        </Button>
                    </div>
                </Form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-surface-200 dark:border-surface-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-surface-50 dark:bg-surface-950 text-surface-500 dark:text-surface-400">or continue with</span>
                    </div>
                </div>

                <!-- Social Login Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <Button
                        type="button"
                        variant="outline"
                        class="h-12"
                        :tabindex="6"
                    >
                        <i class="pi pi-google mr-2"></i>
                        Google
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        class="h-12"
                        :tabindex="7"
                    >
                        <i class="pi pi-facebook mr-2"></i>
                        Facebook
                    </Button>
                </div>

                <!-- Login Link -->
                <div class="mt-8 text-center">
                    <p class="text-surface-600 dark:text-surface-400">
                        Already have an account?
                        <TextLink
                            :href="login()"
                            class="text-primary hover:text-primary-600 font-medium ml-1"
                            :tabindex="8"
                        >
                            Sign in
                        </TextLink>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transitions */
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.3s ease;
}

.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Form group spacing */
.form-group {
    margin-bottom: 0;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .min-h-screen {
        min-height: auto;
    }
}

@media (max-width: 640px) {
    .w-full {
        padding: 1rem;
    }
    
    .max-w-md {
        max-width: 100%;
    }
    
    .text-3xl {
        font-size: 1.75rem;
    }
    
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>