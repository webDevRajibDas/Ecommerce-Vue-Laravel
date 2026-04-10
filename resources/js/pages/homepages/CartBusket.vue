<!-- CartBasket.vue -->
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import { useCartCookie } from '@/composables/useCartCookie';

const emit = defineEmits<{
    (e: 'click'): void;
    (e: 'view-cart'): void;
    (e: 'checkout'): void;
}>();

// Use the cart composable
const { cartItems, cartCount, totalAmount } = useCartCookie();

const isHovered = ref(false);
const isAnimating = ref(false);

const itemTotal = computed(() => cartCount.value);
const totalPrice = computed(() => totalAmount.value);

const handleClick = () => {
    isAnimating.value = true;
    emit('click');
    setTimeout(() => {
        isAnimating.value = false;
    }, 300);
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
};
</script>

<template>
    <div class="cart-basket-wrapper">
        <!-- Main Cart Button -->
        <div 
            class="cart-basket"
            :class="{ 
                'cart-basket--hover': isHovered,
                'cart-basket--animate': isAnimating 
            }"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false"
            @click="handleClick"
        >
            <!-- Glass background layer -->
            <div class="cart-basket__glass"></div>
            
            <!-- Cart icon with animation -->
            <div class="cart-basket__icon-wrapper">
                <svg 
                    class="cart-basket__icon" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path 
                        d="M2 3H4L5 13M5 13H19L21 5H5M5 13L6 18H19M7 21C7 21.5523 6.55228 22 6 22C5.44772 22 5 21.5523 5 21C5 20.4477 5.44772 20 6 20C6.55228 20 7 20.4477 7 21ZM19 21C19 21.5523 18.5523 22 18 22C17.4477 22 17 21.5523 17 21C17 20.4477 17.4477 20 18 20C18.5523 20 19 20.4477 19 21Z" 
                        stroke="currentColor" 
                        stroke-width="1.5" 
                        stroke-linecap="round" 
                        stroke-linejoin="round"
                        fill="none"
                    />
                    <path 
                        v-if="itemTotal > 0"
                        class="cart-basket__cart-line"
                        d="M8 13L12 17L18 9" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round"
                        fill="none"
                    />
                </svg>
                
                <!-- Animated badge -->
                <Badge 
                    v-if="itemTotal > 0"
                    :value="itemTotal > 99 ? '99+' : itemTotal"
                    class="cart-basket__badge"
                    :class="{ 'cart-basket__badge--pulse': isHovered }"
                />
            </div>
            
            <!-- Price display on hover -->
            <Transition name="slide-fade">
                <div v-if="isHovered && totalPrice > 0" class="cart-basket__price">
                    {{ formatPrice(totalPrice) }}
                </div>
            </Transition>
            
            <!-- Ripple effect on click -->
            <div v-if="isAnimating" class="cart-basket__ripple"></div>
        </div>
        
        <!-- Expanded Cart Preview (optional on hover) -->
        <Transition name="expand">
            <div v-if="isHovered && cartItems.length > 0" class="cart-preview">
                <div class="cart-preview__glass"></div>
                <div class="cart-preview__content">
                    <div class="cart-preview__header">
                        <h4>Shopping Cart</h4>
                        <span class="cart-preview__count">{{ itemTotal }} items</span>
                    </div>
                    
                    <div class="cart-preview__items">
                        <div 
                            v-for="(item, index) in cartItems.slice(0, 3)" 
                            :key="item.id"
                            class="cart-preview__item"
                        >
                            <div class="cart-preview__item-image" v-if="item.image">
                                <img :src="item.image" :alt="item.name" />
                            </div>
                            <div class="cart-preview__item-info">
                                <div class="cart-preview__item-name">{{ item.name }}</div>
                                <div class="cart-preview__item-meta">
                                    <span>{{ item.quantity }} × {{ formatPrice(item.price) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="cartItems.length > 3" class="cart-preview__more">
                            +{{ cartItems.length - 3 }} more items
                        </div>
                    </div>
                    
                    <div class="cart-preview__footer">
                        <div class="cart-preview__total">
                            <span>Total:</span>
                            <strong>{{ formatPrice(totalPrice) }}</strong>
                        </div>
                        <div class="cart-preview__actions">
                            <Button 
                                label="View Cart" 
                                size="small" 
                                severity="secondary" 
                                outlined
                                @click.stop="$emit('view-cart')"
                            />
                            <Button 
                                label="Checkout" 
                                size="small" 
                                @click.stop="$emit('checkout')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.cart-basket-wrapper {
    position: fixed;
    left: 30px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1000;
}

/* Main Cart Button */
.cart-basket {
    position: relative;
    width: 70px;
    height: 70px;
    border-radius: 35px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.cart-basket--hover {
    transform: scale(1.05);
}

.cart-basket--animate {
    animation: bounce 0.3s ease;
}

/* Glass morphism effect */
.cart-basket__glass {
    position: absolute;
    inset: 0;
    border-radius: 35px;
    background: rgb(1 239 223 / 20%);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.cart-basket--hover .cart-basket__glass {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(16px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

/* Icon styling */
.cart-basket__icon-wrapper {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-basket__icon {
    width: 32px;
    height: 32px;
    color: #ffffff;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    transition: transform 0.3s ease;
}

.cart-basket--hover .cart-basket__icon {
    transform: scale(1.1);
}

.cart-basket__cart-line {
    stroke-dasharray: 50;
    stroke-dashoffset: 50;
    animation: drawLine 0.5s ease forwards;
}

@keyframes drawLine {
    to {
        stroke-dashoffset: 0;
    }
}

/* Badge styling */
.cart-basket__badge {
    position: absolute;
    top: -8px;
    right: -12px;
    background: linear-gradient(135deg, #ff6b6b, #ff4757);
    color: white;
    font-weight: bold;
    font-size: 12px;
    min-width: 22px;
    height: 22px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
    transition: transform 0.3s ease;
}

.cart-basket__badge--pulse {
    animation: pulse 0.6s ease infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.15);
    }
}

/* Price display on hover */
.cart-basket__price {
    position: absolute;
    left: 80px;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    color: white;
    white-space: nowrap;
    z-index: 10;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Ripple effect */
.cart-basket__ripple {
    position: absolute;
    inset: 0;
    border-radius: 35px;
    animation: ripple 0.5s ease-out;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
    pointer-events: none;
}

@keyframes ripple {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Cart Preview Panel */
.cart-preview {
    position: absolute;
    left: 90px;
    top: 50%;
    transform: translateY(-50%);
    width: 320px;
    border-radius: 20px;
    overflow: hidden;
    z-index: 1001;
}

.cart-preview__glass {
    position: absolute;
    inset: 0;
    background: rgba(30, 30, 35, 0.9);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.cart-preview__content {
    position: relative;
    z-index: 2;
    padding: 16px;
}

.cart-preview__header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.cart-preview__header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: white;
}

.cart-preview__count {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
}

.cart-preview__items {
    max-height: 280px;
    overflow-y: auto;
    margin-bottom: 16px;
}

.cart-preview__items::-webkit-scrollbar {
    width: 4px;
}

.cart-preview__items::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
}

.cart-preview__items::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.cart-preview__item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.cart-preview__item-image {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.cart-preview__item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-preview__item-info {
    flex: 1;
    min-width: 0;
}

.cart-preview__item-name {
    font-size: 14px;
    font-weight: 500;
    color: white;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cart-preview__item-meta {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
}

.cart-preview__more {
    text-align: center;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.5);
    padding: 8px;
}

.cart-preview__footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 12px;
}

.cart-preview__total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 14px;
    color: white;
}

.cart-preview__total strong {
    font-size: 18px;
    color: #ffd700;
}

.cart-preview__actions {
    display: flex;
    gap: 8px;
}

.cart-preview__actions :deep(.p-button) {
    flex: 1;
    font-size: 12px;
    padding: 6px 12px;
}

/* Animations */
.slide-fade-enter-active {
    transition: all 0.3s ease;
}

.slide-fade-leave-active {
    transition: all 0.2s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(-10px);
    opacity: 0;
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.25s ease;
}

.expand-enter-from,
.expand-leave-to {
    transform: translateX(-20px) translateY(-50%);
    opacity: 0;
}

/* Bounce animation */
@keyframes bounce {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(0.9);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .cart-basket-wrapper {
        left: 16px;
    }
    
    .cart-basket {
        width: 56px;
        height: 56px;
    }
    
    .cart-basket__icon {
        width: 24px;
        height: 24px;
    }
    
    .cart-preview {
        width: 280px;
        left: 70px;
    }
}

/* Dark theme support */
@media (prefers-color-scheme: dark) {
    .cart-basket__glass {
        background: rgba(0, 0, 0, 0.3);
    }
}
</style>