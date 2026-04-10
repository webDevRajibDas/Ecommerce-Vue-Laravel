<script setup lang="ts">
import Button from 'primevue/button';
import Badge from 'primevue/badge';
import Carousel from 'primevue/carousel';
import ProgressBar from 'primevue/progressbar';

interface DealTimer {
    hours: number;
    minutes: number;
    seconds: number;
}

interface DealProduct {
    id: string;
    name: string;
    image: string;
    price: number;
    discount?: number;
    stockPercentage?: number;
}

defineProps<{
    dealProducts: DealProduct[];
    dealTimer: DealTimer;
    responsiveOptions: Array<Record<string, string | number>>;
}>();
</script>

<template>
    <section class="py-16 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/30 dark:to-orange-950/30">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <i class="pi pi-bolt text-red-500 text-2xl animate-pulse"></i>
                        <h2 class="text-3xl md:text-4xl font-bold text-surface-900 dark:text-surface-100">
                            Flash Deals
                        </h2>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400">Limited time offers - Grab them before they're gone!</p>
                </div>
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <div class="text-center">
                        <p class="text-sm text-surface-500 dark:text-surface-400">Ends in</p>
                        <div class="flex gap-2 mt-1">
                            <div class="bg-surface-900 dark:bg-surface-100 text-white dark:text-surface-900 px-3 py-2 rounded-lg font-bold text-xl">
                                {{ dealTimer.hours.toString().padStart(2, '0') }}
                            </div>
                            <span class="text-2xl font-bold text-surface-700 dark:text-surface-300 self-center">:</span>
                            <div class="bg-surface-900 dark:bg-surface-100 text-white dark:text-surface-900 px-3 py-2 rounded-lg font-bold text-xl">
                                {{ dealTimer.minutes.toString().padStart(2, '0') }}
                            </div>
                            <span class="text-2xl font-bold text-surface-700 dark:text-surface-300 self-center">:</span>
                            <div class="bg-surface-900 dark:bg-surface-100 text-white dark:text-surface-900 px-3 py-2 rounded-lg font-bold text-xl">
                                {{ dealTimer.seconds.toString().padStart(2, '0') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Carousel
                :value="dealProducts"
                :numVisible="4"
                :numScroll="1"
                :responsiveOptions="responsiveOptions"
                circular
                :autoplayInterval="4000"
                class="deals-carousel"
            >
                <template #item="slotProps">
                    <div class="relative bg-white dark:bg-surface-800 rounded-xl shadow-lg overflow-hidden m-2 group">
                        <div class="absolute top-3 left-3 z-10">
                            <Badge
                                :value="'-' + (slotProps.data.discount ?? 0) + '%'"
                                severity="danger"
                                class="text-lg font-bold"
                            />
                        </div>
                        <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                            <Button
                                icon="pi pi-heart"
                                rounded
                                severity="danger"
                                variant="filled"
                                size="small"
                            />
                        </div>
                        <div class="relative h-48 overflow-hidden bg-surface-100 dark:bg-surface-700">
                            <img
                                :src="'https://primefaces.org/cdn/primevue/images/product/' + slotProps.data.image"
                                :alt="slotProps.data.name"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            />
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-surface-900 dark:text-surface-100 truncate mb-2">
                                {{ slotProps.data.name }}
                            </h3>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xl font-bold text-red-600 dark:text-red-400">
                                    ${{ (slotProps.data.price * (1 - (slotProps.data.discount ?? 0) / 100)).toFixed(2) }}
                                </span>
                                <span class="text-sm text-surface-400 line-through">
                                    ${{ slotProps.data.price }}
                                </span>
                            </div>
                            <ProgressBar
                                :value="slotProps.data.stockPercentage ?? 0"
                                :showValue="false"
                                class="h-2 mb-2"
                                color="danger"
                            />
                            <p class="text-xs text-red-500">
                                Only {{ slotProps.data.stockPercentage ?? 0 }}% left!
                            </p>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <Button
                                label="Add to Cart"
                                icon="pi pi-shopping-cart"
                                class="w-full rounded-none py-3"
                                severity="danger"
                            />
                        </div>
                    </div>
                </template>
            </Carousel>
        </div>
    </section>
</template>

<style scoped>
.deals-carousel :deep(.p-carousel-content) {
    padding-bottom: 2rem;
}

.deals-carousel :deep(.p-carousel-prev),
.deals-carousel :deep(.p-carousel-next) {
    background: transparent;
    border: none;
    width: 2.5rem;
    height: 2.5rem;
}

.deals-carousel :deep(.p-carousel-prev):hover,
.deals-carousel :deep(.p-carousel-next):hover {
    background: var(--p-primary-color);
    color: white;
    border-radius: 50%;
}
</style>
