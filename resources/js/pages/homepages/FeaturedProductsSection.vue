<script setup lang="ts">
import { ref } from 'vue';
import Button from 'primevue/button';
import Carousel from 'primevue/carousel';
import Rating from 'primevue/rating';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { useCartCookie } from '@/composables/useCartCookie';

interface FeaturedProduct {
    id: string;
    name: string;
    image: string;
    price: number;
    originalPrice?: number | null;
    category: string;
    inventoryStatus: string;
    rating: number;
}

defineProps<{
    products: FeaturedProduct[];
    responsiveOptions: Array<Record<string, string | number>>;
}>();

const toast = useToast();
const { addToCart, cartCount } = useCartCookie();
const addingToCart = ref<string | null>(null);

const getSeverity = (status: string) => {
    const severityMap = {
        INSTOCK: 'success',
        LOWSTOCK: 'warn',
        OUTOFSTOCK: 'danger',
    } as const;

    return severityMap[status as keyof typeof severityMap] || null;
};

const handleAddToCart = (product: FeaturedProduct) => {
    addingToCart.value = product.id;
    
    // Add to cart
    addToCart({
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image,
        category: product.category
    }, 1);
    
    // Show success toast
    toast.add({
        severity: 'success',
        summary: 'Added to Cart',
        detail: `${product.name} has been added to your cart`,
        life: 2000
    });
    
    setTimeout(() => {
        addingToCart.value = null;
    }, 300);
};
</script>

<template>
    <section class="py-16 container mx-auto px-4">
        <!-- Toast notifications -->
        <Toast position="top-right" />
        
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-surface-900 dark:text-surface-100 mb-4">
                Featured Products
            </h2>
            <p class="text-surface-600 dark:text-surface-400 max-w-2xl mx-auto">
                Handpicked selection of our best-selling and most popular items
            </p>
        </div>
        <Carousel
            :value="products"
            :numVisible="5"
            :numScroll="1"
            :responsiveOptions="responsiveOptions"
            circular
            :autoplayInterval="5000"
        >
            <template #item="slotProps">
                <div class="group relative bg-white dark:bg-surface-800 rounded-xl shadow-md overflow-hidden m-2 hover:shadow-xl transition-shadow duration-300">
                    <div class="relative h-48 overflow-hidden bg-surface-100 dark:bg-surface-700">
                        <img
                            :src="slotProps.data.image"
                            :alt="slotProps.data.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                        <Tag
                            :value="slotProps.data.inventoryStatus"
                            :severity="getSeverity(slotProps.data.inventoryStatus)"
                            class="absolute top-3 left-3"
                        />
                        <!-- Cart Count Badge -->
                        <div v-if="cartCount > 0" class="absolute top-3 right-3 bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold shadow-md">
                            {{ cartCount }}
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-surface-500 dark:text-surface-400 mb-1">
                            {{ slotProps.data.category }}
                        </p>
                        <h3 class="font-semibold text-surface-900 dark:text-surface-100 mb-2 truncate">
                            {{ slotProps.data.name }}
                        </h3>
                        <div class="flex items-center gap-1 mb-3">
                            <Rating :modelValue="slotProps.data.rating" :stars="5" readonly class="text-sm" />
                            <span class="text-sm text-surface-500 dark:text-surface-400">
                                ({{ slotProps.data.rating }})
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                <span class="text-xl font-bold text-surface-900 dark:text-surface-100">
                                    TK{{ slotProps.data.price }}
                                </span>
                                <span
                                    v-if="slotProps.data.originalPrice"
                                    class="text-sm text-surface-400 line-through"
                                >
                                    ${{ slotProps.data.originalPrice }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <Button
                                    icon="pi pi-heart"
                                    rounded
                                    severity="secondary"
                                    variant="outlined"
                                    size="small"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <Button
                            label="Add to Cart"
                            icon="pi pi-shopping-cart"
                            :loading="addingToCart === slotProps.data.id"
                            :disabled="slotProps.data.inventoryStatus === 'OUTOFSTOCK'"
                            class="w-full rounded-none py-3"
                            @click="handleAddToCart(slotProps.data)"
                        />
                    </div>
                </div>
            </template>
        </Carousel>
    </section>
</template>
