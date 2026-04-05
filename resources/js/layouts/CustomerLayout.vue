<script setup lang="ts">
import { type BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="min-h-screen bg-surface-50 dark:bg-surface-950">
        <!-- Customer Header -->
        <header class="bg-white dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo and Title -->
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                            <i class="pi pi-shopping-bag text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-surface-900 dark:text-surface-100">E-Shop</h1>
                            <p class="text-sm text-surface-500 dark:text-surface-400">Customer Dashboard</p>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-surface-900 dark:text-surface-100">{{ $page.props.auth.user?.name }}</p>
                            <p class="text-xs text-surface-500 dark:text-surface-400">{{ $page.props.auth.user?.email }}</p>
                        </div>
                        <div class="w-10 h-10 bg-surface-200 dark:bg-surface-700 rounded-full flex items-center justify-center">
                            <i class="pi pi-user text-surface-600 dark:text-surface-300"></i>
                        </div>
                    </div>
                </div>

                <!-- Breadcrumbs -->
                <div v-if="breadcrumbs.length > 0" class="py-4 border-t border-surface-100 dark:border-surface-800">
                    <nav class="flex items-center gap-2 text-sm">
                        <span v-for="(breadcrumb, index) in breadcrumbs" :key="index" class="flex items-center gap-2">
                            <span v-if="index > 0" class="text-surface-400 dark:text-surface-500">/</span>
                            <span class="text-surface-600 dark:text-surface-400 hover:text-surface-900 dark:hover:text-surface-100 cursor-pointer">
                                {{ breadcrumb.title }}
                            </span>
                        </span>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>
    </div>
</template>