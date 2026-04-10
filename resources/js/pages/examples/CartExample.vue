<!-- Example Component: Using Cart Cookie System -->
<template>
    <div class="cart-example">
        <h1>Shopping Cart Example</h1>
        
        <!-- Cart Count Display -->
        <div class="cart-stats">
            <p>Items in cart: <strong>{{ cartCount }}</strong></p>
            <p>Total value: <strong>TK{{ totalAmount }}</strong></p>
        </div>

        <!-- Example: Add Product -->
        <div class="add-product-section">
            <h2>Add Product Example</h2>
            <button @click="addSampleProduct">
                Add Sample Product to Cart
            </button>
        </div>

        <!-- Cart Items List -->
        <div v-if="cartItems.length > 0" class="cart-items">
            <h2>Cart Items ({{ cartItems.length }})</h2>
            <div v-for="item in cartItems" :key="item.id" class="cart-item">
                <div>
                    <strong>{{ item.name }}</strong>
                    <p>Price: TK{{ item.price }}</p>
                    <p>Quantity: {{ item.quantity }}</p>
                    <p>Subtotal: TK{{ item.price * item.quantity }}</p>
                </div>
                <div class="item-actions">
                    <button @click="updateQuantity(item.id, item.quantity + 1)">
                        Increase
                    </button>
                    <button @click="updateQuantity(item.id, item.quantity - 1)" v-if="item.quantity > 1">
                        Decrease
                    </button>
                    <button @click="removeFromCart(item.id)" class="btn-danger">
                        Remove
                    </button>
                </div>
            </div>

            <div class="cart-actions">
                <button @click="clearCart" class="btn-danger">
                    Clear Cart
                </button>
            </div>
        </div>

        <!-- Empty Cart -->
        <div v-else class="empty-cart">
            <p>Your cart is empty</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useCartCookie } from '@/composables/useCartCookie';

// Use the cart composable
const { 
    cartItems, 
    cartCount, 
    totalAmount,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart
} = useCartCookie();

// Example function to add a sample product
const addSampleProduct = () => {
    const sampleProducts = [
        {
            id: `prod-${Date.now()}`,
            name: 'Laptop',
            price: 45000,
            image: '/images/laptop.jpg',
            category: 'Electronics'
        },
        {
            id: `prod-${Date.now()}-2`,
            name: 'Mouse',
            price: 500,
            image: '/images/mouse.jpg',
            category: 'Accessories'
        },
        {
            id: `prod-${Date.now()}-3`,
            name: 'Keyboard',
            price: 1200,
            image: '/images/keyboard.jpg',
            category: 'Accessories'
        }
    ];

    // Pick random product
    const randomProduct = sampleProducts[Math.floor(Math.random() * sampleProducts.length)];
    addToCart(randomProduct, 1);
};
</script>

<style scoped>
.cart-example {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.cart-stats {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 8px;
    margin: 20px 0;
}

.add-product-section {
    margin: 20px 0;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

button {
    padding: 10px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 5px;
}

button:hover {
    background: #0056b3;
}

.btn-danger {
    background: #dc3545;
}

.btn-danger:hover {
    background: #c82333;
}

.cart-items {
    margin-top: 30px;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 10px;
}

.item-actions {
    display: flex;
    gap: 5px;
}

.cart-actions {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
}

.empty-cart {
    text-align: center;
    padding: 40px;
    color: #666;
}
</style>
