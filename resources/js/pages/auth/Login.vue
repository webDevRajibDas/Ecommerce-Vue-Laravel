<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Welcome Back"
        description="Sign in to your account to continue"
        class="login-container"
    >
       
        <!-- Animated Background -->
        <div class="animated-background">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="login-content">
            <!-- Header with Animation -->
            <div class="login-header">
                <div class="logo-container">
                    <div class="logo-animation">
                        <i class="pi pi-lock text-primary text-2xl"></i>
                    </div>
                </div>
                
            </div>

            <!-- Status Message -->
            <Transition name="slide-down">
                <div
                    v-if="status"
                    class="status-message mb-6"
                >
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-green-50 border border-green-200">
                        <i class="pi pi-check-circle text-green-600 text-lg"></i>
                        <span class="text-green-800 font-medium">{{ status }}</span>
                    </div>
                </div>
            </Transition>

            <!-- Login Form -->
            <Form
                v-bind="store.form()"
                :reset-on-success="['password']"
                v-slot="{ errors, processing }"
                class="login-form"
            >
                <div class="form-content space-y-6">
                    <!-- Email Field -->
                    <div class="form-group">
                        <div class="input-container" :class="{ 'input-error': errors.email }">
                            <div class="input-icon">
                                <i class="pi pi-envelope text-muted-foreground"></i>
                            </div>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                required
                                autofocus
                                :tabindex="1"
                                autocomplete="email"
                                placeholder="Enter your email"
                                class="input-field"
                            />
                        </div>
                        <Transition name="fade">
                            <InputError v-if="errors.email" :message="errors.email" class="error-message" />
                        </Transition>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <div class="input-container" :class="{ 'input-error': errors.password }">
                            <div class="input-icon">
                                <i class="pi pi-lock text-muted-foreground"></i>
                            </div>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                required
                                :tabindex="2"
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="input-field"
                            />
                        </div>
                        <Transition name="fade">
                            <InputError v-if="errors.password" :message="errors.password" class="error-message" />
                        </Transition>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <Label for="remember" class="remember-label">
                            <Checkbox 
                                id="remember" 
                                name="remember" 
                                :tabindex="3"
                                class="remember-checkbox"
                            />
                            <span class="remember-text">Keep me signed in</span>
                        </Label>
                        
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="forgot-password-link"
                            :tabindex="5"
                        >
                            Forgot password?
                        </TextLink>
                    </div>

                    <!-- Submit Button -->
                    <Button
                        type="submit"
                        class="login-button"
                        :tabindex="4"
                        :disabled="processing"
                        data-test="login-button"
                    >
                        <div class="button-content">
                            <LoaderCircle
                                v-if="processing"
                                class="button-loader"
                            />
                            <span class="button-text">
                                {{ processing ? 'Signing in...' : 'Sign In' }}
                            </span>
                        </div>
                        <i class="pi pi-arrow-right button-arrow"></i>
                    </Button>
                </div>
            </Form>

            <!-- Divider -->
            <div class="divider">
                <span class="divider-text">or continue with</span>
            </div>

            <!-- Social Login -->
            <div class="social-login">
                <Button variant="outlined" class="social-button">
                    <i class="pi pi-google text-red-600"></i>
                    <span>Google</span>
                </Button>
                <Button variant="outlined" class="social-button">
                    <i class="pi pi-github text-gray-800"></i>
                    <span>GitHub</span>
                </Button>
            </div>

            <!-- Sign Up Link -->
            <Transition name="fade">
                <div
                    v-if="canRegister"
                    class="signup-section"
                >
                    <span class="signup-text">Don't have an account?</span>
                    <TextLink :href="register()" :tabindex="5" class="signup-link">
                        Create account
                    </TextLink>
                </div>
            </Transition>
        </div>
    </AuthBase>
</template>

<style scoped>
.login-container {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.animated-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.floating-shapes {
    position: relative;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    left: 80%;
    animation-delay: 2s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    top: 80%;
    left: 20%;
    animation-delay: 4s;
}

.shape-4 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 70%;
    animation-delay: 1s;
}

.login-content {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 440px;
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.logo-container {
    margin-bottom: 1.5rem;
}

.logo-animation {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite alternate;
}

.login-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.login-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.form-content {
    margin-top: 2rem;
}

.form-group {
    position: relative;
}

.input-container {
    position: relative;
    display: flex;
    align-items: center;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.input-container:focus-within {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.input-container.input-error {
    border-color: #ef4444;
}

.input-icon {
    padding: 0 1rem;
    display: flex;
    align-items: center;
}

.input-field {
    border: none;
    background: transparent;
    padding: 1rem 0;
    width: 100%;
    font-size: 1rem;
}

.input-field:focus {
    outline: none;
    box-shadow: none;
}

.error-message {
    margin-top: 0.5rem;
}

.remember-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: color 0.3s ease;
}

.remember-label:hover {
    color: #374151;
}

.remember-checkbox {
    transform: scale(1.1);
}

.forgot-password-link {
    color: #667eea;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-password-link:hover {
    color: #5a67d8;
}

.login-button {
    width: 100%;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.login-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.login-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.button-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.button-loader {
    width: 1.25rem;
    height: 1.25rem;
}

.button-arrow {
    transition: transform 0.3s ease;
}

.login-button:hover .button-arrow {
    transform: translateX(4px);
}

.divider {
    position: relative;
    text-align: center;
    margin: 2rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e5e7eb;
}

.divider-text {
    background: white;
    padding: 0 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.social-login {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}

.social-button {
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    color: #374151;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    justify-content: center;
}

.social-button:hover {
    border-color: #667eea;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.signup-section {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.signup-text {
    color: #6b7280;
    margin-right: 0.5rem;
}

.signup-link {
    color: #667eea;
    font-weight: 600;
    transition: color 0.3s ease;
}

.signup-link:hover {
    color: #5a67d8;
}

/* Animations */
@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
    }
}

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

/* Responsive */
@media (max-width: 640px) {
    .login-content {
        margin: 1rem;
        padding: 2rem 1.5rem;
    }
    
    .social-login {
        grid-template-columns: 1fr;
    }
    
    .login-title {
        font-size: 1.75rem;
    }
}
</style>