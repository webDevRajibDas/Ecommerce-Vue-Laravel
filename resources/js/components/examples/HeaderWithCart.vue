<!-- Example: Header with Cart Integration -->
<template>
    <header class="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <div class="navbar-brand">
                <h1>E-Shop</h1>
            </div>

            <!-- Navigation Links -->
            <nav class="navbar-nav">
                <a href="/">Home</a>
                <a href="/products">Products</a>
                <a href="/categories">Categories</a>
                <a href="/about">About</a>
            </nav>

            <!-- Right Side Items -->
            <div class="navbar-right">
                <!-- Search (optional) -->
                <input type="search" placeholder="Search products..." class="search-input" />

                <!-- Cart Button with Badge -->
                <div class="cart-button-wrapper">
                    <button class="cart-button" @click="goToCart">
                        <i class="pi pi-shopping-cart"></i>
                        <span v-if="cartCount > 0" class="cart-badge">
                            {{ cartCount > 99 ? '99+' : cartCount }}
                        </span>
                    </button>
                    
                    <!-- Cart Tooltip on Hover -->
                    <div v-if="cartCount > 0" class="cart-tooltip">
                        <p>{{ cartCount }} item(s) - TK{{ totalAmount }}</p>
                    </div>
                </div>

                <!-- User Account (optional) -->
                <div class="user-menu">
                    <button><i class="pi pi-user"></i></button>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import { useCartCookie } from '@/composables/useCartCookie';

// Use cart composable to get reactive cart data
const { cartCount, totalAmount } = useCartCookie();

// Navigate to cart page
const goToCart = () => {
    window.location.href = '/cart';
};
</script>

<style scoped>
.navbar {
    background: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    height: 70px;
}

.navbar-brand h1 {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
    margin: 0;
}

.navbar-nav {
    display: flex;
    gap: 30px;
}

.navbar-nav a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: color 0.3s;
}

.navbar-nav a:hover {
    color: #007bff;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.search-input {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 20px;
    width: 250px;
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: #007bff;
}

.cart-button-wrapper {
    position: relative;
}

.cart-button {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    position: relative;
    color: #333;
    transition: color 0.3s;
}

.cart-button:hover {
    color: #007bff;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    animation: badge-pulse 0.5s ease-out;
}

@keyframes badge-pulse {
    0% {
        transform: scale(1.2);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.cart-tooltip {
    position: absolute;
    bottom: -45px;
    right: 0;
    background: #333;
    color: white;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
}

.cart-tooltip::before {
    content: '';
    position: absolute;
    top: -5px;
    right: 10px;
    width: 10px;
    height: 10px;
    background: #333;
    transform: rotate(45deg);
}

.user-menu button {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #333;
    transition: color 0.3s;
}

.user-menu button:hover {
    color: #007bff;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar-nav {
        display: none;
    }

    .search-input {
        width: 150px;
    }

    .navbar-container {
        height: 60px;
    }

    .navbar-brand h1 {
        font-size: 18px;
    }
}
</style>
