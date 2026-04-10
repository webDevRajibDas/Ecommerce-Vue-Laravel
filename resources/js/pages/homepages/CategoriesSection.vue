<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import Badge from 'primevue/badge';

interface CategoryItem {
    id: number;
    name: string;
    slug: string;
    icon: string;
    count: number;
    badge: string;
}

defineProps<{
    categories: CategoryItem[];
}>();

const navigateToCategory = (category: CategoryItem) => {
    router.get(`/categories/${category.slug || category.name.toLowerCase().replace(/\s+/g, '-')}`);
};
</script>

<template>
    <section class="py-16 container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-surface-900 dark:text-surface-100 mb-4">
                Shop by Category
            </h2>
            <p class="text-surface-600 dark:text-surface-400 max-w-2xl mx-auto">
                Browse through our carefully curated categories to find exactly what you're looking for
            </p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div
                v-for="category in categories"
                :key="category.id"
                class="group cursor-pointer"
                @click="navigateToCategory(category)"
            >
                <div class="relative overflow-hidden rounded-xl bg-surface-0 dark:bg-surface-800 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="aspect-square p-6 flex items-center justify-center bg-gradient-to-br from-surface-100 to-surface-200 dark:from-surface-700 dark:to-surface-800">
                        <i :class="['pi', category.icon, 'text-[4rem] text-surface-700 dark:text-surface-300 group-hover:scale-110 transition-transform duration-300']"></i>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-surface-900 dark:text-surface-100 group-hover:text-primary transition-colors">
                            {{ category.name }}
                        </h3>
                        <p class="text-sm text-surface-500 dark:text-surface-400 mt-1">
                            {{ category.count }} Items
                        </p>
                    </div>
                    <div v-if="category.badge" class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <Badge :value="category.badge" severity="info" class="animate-bounce" />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
