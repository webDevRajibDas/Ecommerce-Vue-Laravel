<template>
<div class="min-h-screen bg-surface-50 dark:bg-surface-950">

    <CartBasket/>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-r from-primary to-primary-600 dark:from-primary-700 dark:to-primary-800">
        <div class="container mx-auto px-4 py-16 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="text-white text-center md:text-left">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">
                        Discover Your Style
                    </h1>
                    <p class="text-lg md:text-xl mb-8 opacity-90">
                        Explore our exclusive collection of premium products at unbeatable prices.
                        New arrivals every week!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <Button label="Shop Now" icon="pi pi-shopping-bag" severity="secondary" size="large" rounded class="font-semibold" />
                        <Button label="View Collections" icon="pi pi-arrow-right" iconPos="right" outlined severity="secondary" size="large" rounded class="text-white border-white hover:bg-white/10" />
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/20 rounded-full blur-3xl animate-pulse"></div>
                        <img src="https://primefaces.org/cdn/primevue/images/demo/hero/hero-mechanics.png" alt="Hero Banner" class="relative z-10 w-80 h-80 object-contain drop-shadow-2xl" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="currentColor" class="text-surface-50 dark:text-surface-950" />
            </svg>
        </div>
    </section>

    <!-- Features Banner -->
    <section class="py-8 bg-surface-0 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-800">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div v-for="(feature, index) in features" :key="index" class="flex items-center gap-4 p-4 rounded-lg hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors">
                    <div class="p-3 rounded-full bg-primary/10 dark:bg-primary/20">
                        <i :class="['pi', feature.icon, 'text-xl text-primary']"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-surface-900 dark:text-surface-100">{{ feature.title }}</h3>
                        <p class="text-sm text-surface-600 dark:text-surface-400">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <CategoriesSection :categories="props.categories" />

    <FlashDealsSection :deal-products="dealProducts" :deal-timer="dealTimer" :responsive-options="responsiveOptions" />

    <FeaturedProductsSection :products="products" :responsive-options="responsiveOptions" />

    <!-- Promotional Banner -->
    <section class="py-16 container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600 to-blue-600 p-8 md:p-12 text-white">
                <div class="relative z-10">
                    <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-sm mb-4">Limited Offer</span>
                    <h3 class="text-3xl md:text-4xl font-bold mb-4">New Season Arrivals</h3>
                    <p class="text-lg opacity-90 mb-6">Get up to 40% off on all new arrivals. Don't miss out on the latest trends!</p>
                    <Button label="Explore Collection" icon="pi pi-arrow-right" iconPos="right" outlined severity="secondary" rounded />
                </div>
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -right-5 -top-5 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-pink-500 p-8 md:p-12 text-white">
                <div class="relative z-10">
                    <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-sm mb-4">Members Only</span>
                    <h3 class="text-3xl md:text-4xl font-bold mb-4">Join Our Community</h3>
                    <p class="text-lg opacity-90 mb-6">Sign up today and get exclusive access to member-only deals and early access to sales.</p>
                    <Button label="Sign Up Now" icon="pi pi-user-plus" iconPos="right" outlined severity="secondary" rounded />
                </div>
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -left-5 -top-5 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>
    </section>

    <!-- Electronics & Accessories Section -->
    <section class="py-16 bg-surface-0 dark:bg-surface-900">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-surface-900 dark:text-surface-100 mb-2">
                        Electronics & Accessories
                    </h2>
                    <p class="text-surface-600 dark:text-surface-400">Tech gadgets and accessories for everyday use</p>
                </div>
                <Button label="View All" icon="pi pi-arrow-right" iconPos="right" severity="secondary" variant="outlined" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="product in electronicsProducts" :key="product.id" class="bg-white dark:bg-surface-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative h-40 overflow-hidden bg-surface-100 dark:bg-surface-700">
                        <img :src="'https://primefaces.org/cdn/primevue/images/product/' + product.image" :alt="product.name" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-surface-900 dark:text-surface-100 mb-2 truncate">
                            {{ product.name }}
                        </h3>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-primary">${{ product.price }}</span>
                            <Button icon="pi pi-plus" rounded size="small" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-surface-900 to-surface-800 dark:from-surface-800 dark:to-surface-900">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <i class="pi pi-envelope text-5xl text-primary mb-6"></i>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Subscribe to Our Newsletter
                </h2>
                <p class="text-surface-300 mb-8">
                    Stay updated with our latest products, exclusive deals, and special offers.
                    Get 10% off your first order!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <InputText v-model="email" placeholder="Enter your email address" class="flex-1 h-12" />
                    <Button label="Subscribe" icon="pi pi-send" severity="primary" size="large" />
                </div>
                <p class="text-sm text-surface-400 mt-4">
                    By subscribing, you agree to our Privacy Policy and consent to receive updates.
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-surface-950 dark:bg-surface-950 text-surface-300 py-16 footer">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                            <i class="pi pi-shopping-bag text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-white">E-Shop</span>
                    </div>
                    <p class="text-surface-400 mb-6">
                        Your one-stop destination for quality products at amazing prices.
                        We deliver happiness to your doorstep.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-surface-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <i class="pi pi-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-surface-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <i class="pi pi-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-surface-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <i class="pi pi-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-surface-800 rounded-full flex items-center justify-center hover:bg-primary transition-colors">
                            <i class="pi pi-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-6">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> About Us</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Contact Us</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> FAQ's</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Shipping Info</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Returns</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-6">Categories</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Electronics</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Clothing</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Accessories</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Fitness</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors flex items-center gap-2"><i class="pi pi-chevron-right text-xs"></i> Home & Garden</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-6">Contact Us</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="pi pi-map-marker mt-1 text-primary"></i>
                            <span>123 Commerce Street,<br>Business District, City 12345</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="pi pi-phone text-primary"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="pi pi-envelope text-primary"></i>
                            <span>support@eshop.com</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="pi pi-clock text-primary"></i>
                            <span>Mon - Fri: 9AM - 6PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-surface-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-surface-500">
                        &copy; {{ new Date().getFullYear() }} E-Shop. All rights reserved.
                    </p>
                    <div class="flex gap-6">
                        <a href="#" class="text-sm text-surface-500 hover:text-primary transition-colors">Privacy Policy</a>
                        <a href="#" class="text-sm text-surface-500 hover:text-primary transition-colors">Terms of Service</a>
                        <a href="#" class="text-sm text-surface-500 hover:text-primary transition-colors">Cookie Policy</a>
                    </div>
                    <div class="flex gap-3">
                        <img src="https://primefaces.org/cdn/primevue/images/demo/payment/visa.png" alt="Visa" class="h-6 opacity-70">
                        <img src="https://primefaces.org/cdn/primevue/images/demo/payment/mastercard.png" alt="Mastercard" class="h-6 opacity-70">
                        <img src="https://primefaces.org/cdn/primevue/images/demo/payment/paypal.png" alt="PayPal" class="h-6 opacity-70">
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
</template>

<script setup lang="ts">
import { defineAsyncComponent, ref, onMounted, onUnmounted } from 'vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { ProductService } from '@/service/ProductService';

const CartBasket = defineAsyncComponent(() => import('@/pages/homepages/CartBusket.vue'));
const CategoriesSection = defineAsyncComponent(() => import('@/pages/homepages/CategoriesSection.vue'));
const FlashDealsSection = defineAsyncComponent(() => import('@/pages/homepages/FlashDealsSection.vue'));
const FeaturedProductsSection = defineAsyncComponent(() => import('@/pages/homepages/FeaturedProductsSection.vue'));

// Props
interface Props {
    featuredProducts: Array<{
        id: string;
        name: string;
        category: string;
        price: number;
        originalPrice: number | null;
        image: string | null;
        inventoryStatus: string;
        rating: number;
    }>;
    categories: Array<{
        id: number;
        name: string;
        slug: string;
        icon: string;
        count: number;
        badge: string;
    }>;
}

const props = defineProps<Props>();

// Product type definition
interface Product {
    id: string;
    code: string;
    name: string;
    description: string;
    image: string;
    price: number;
    originalPrice?: number | null;
    category: string;
    quantity: number;
    inventoryStatus: string;
    rating: number;
    discount?: number;
    stockPercentage?: number;
}

// State
const products = ref<Product[]>([]);
const dealProducts = ref<Product[]>([]);
const electronicsProducts = ref<Product[]>([]);
const email = ref('');

// Deal Timer
const dealTimer = ref({
    hours: 12,
    minutes: 45,
    seconds: 30
});

let timerInterval: ReturnType<typeof setInterval>;

// Features data
const features = ref([
    { icon: 'pi-truck', title: 'Free Shipping', description: 'On orders over $50' },
    { icon: 'pi-shield', title: 'Secure Payment', description: '100% protected' },
    { icon: 'pi-refresh', title: 'Easy Returns', description: '30-day return policy' },
    { icon: 'pi-headphones', title: '24/7 Support', description: 'Dedicated support' }
]);

// Responsive options for carousels
const responsiveOptions = ref([
    { breakpoint: '1920px', numVisible: 4, numScroll: 1 },
    { breakpoint: '1400px', numVisible: 4, numScroll: 1 },
    { breakpoint: '1199px', numVisible: 3, numScroll: 1 },
    { breakpoint: '767px', numVisible: 2, numScroll: 1 },
    { breakpoint: '575px', numVisible: 1, numScroll: 1 }
]);

const startDealTimer = () => {
    timerInterval = setInterval(() => {
        if (dealTimer.value.seconds > 0) {
            dealTimer.value.seconds--;
        } else if (dealTimer.value.minutes > 0) {
            dealTimer.value.minutes--;
            dealTimer.value.seconds = 59;
        } else if (dealTimer.value.hours > 0) {
            dealTimer.value.hours--;
            dealTimer.value.minutes = 59;
            dealTimer.value.seconds = 59;
        }
    }, 1000);
};

// Lifecycle
onMounted(async () => {
    try {
        products.value = props.featuredProducts.map((product) => ({
            id: product.id,
            code: product.id,
            name: product.name,
            description: '',
            image: product.image ?? 'https://placehold.co/600x400?text=Product',
            price: product.price,
            originalPrice: product.originalPrice,
            category: product.category,
            quantity: 0,
            inventoryStatus: product.inventoryStatus,
            rating: product.rating
        }));

        // Load demo data for the remaining homepage sections
        const data = await ProductService.getProductsSmall();

        // Create deal products with discount info
        dealProducts.value = data.slice(0, 8).map(product => ({
            ...product,
            discount: Math.floor(Math.random() * 30) + 10,
            stockPercentage: Math.floor(Math.random() * 60) + 10
        }));

        // Filter electronics products
        electronicsProducts.value = data
            .filter(p => p.category === 'Electronics' || p.category === 'Accessories')
            .slice(0, 4);

        // Start deal timer
        startDealTimer();
    } catch (err) {
        console.error('Error loading products:', err);
    }
});

onUnmounted(() => {
    if (timerInterval) {
        clearInterval(timerInterval);
    }
});


const cartItems = ref([
    { id: 1, name: 'Product 1', price: 299, quantity: 2, image: '/images/product1.jpg' },
    { id: 2, name: 'Product 2', price: 499, quantity: 1, image: '/images/product2.jpg' },
]);


const handleCartClick = () => {
    console.log('Cart clicked!');
};



</script>

<style scoped>
.footer {

    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: linear-gradient(135deg, #1e3a8a, #9333ea);
    color: #ffffff;
}
</style>
