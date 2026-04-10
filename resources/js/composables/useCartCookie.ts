import { ref, computed } from 'vue';

export interface CartItem {
    id: string | number;
    name: string;
    price: number;
    quantity: number;
    image?: string;
    category?: string;
}

const CART_COOKIE_NAME = 'ecommerce_cart';
const COOKIE_MAX_AGE = 7 * 24 * 60 * 60; // 7 days in seconds

/**
 * Parse cart data from cookie
 */
function parseCartCookie(): CartItem[] {
    if (typeof document === 'undefined') return [];
    
    const cookies = document.cookie.split(';');
    const cartCookie = cookies.find(c => c.trim().startsWith(CART_COOKIE_NAME));
    
    if (!cartCookie) return [];
    
    try {
        const encoded = cartCookie.split('=')[1];
        if (!encoded) return [];
        
        const decoded = decodeURIComponent(encoded);
        return JSON.parse(decoded);
    } catch (error) {
        console.error('Error parsing cart cookie:', error);
        return [];
    }
}

/**
 * Set cart cookie
 */
function setCartCookie(items: CartItem[]): void {
    if (typeof document === 'undefined') return;
    
    const expires = new Date();
    expires.setTime(expires.getTime() + COOKIE_MAX_AGE * 1000);
    
    const encoded = encodeURIComponent(JSON.stringify(items));
    document.cookie = `${CART_COOKIE_NAME}=${encoded}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;
}

/**
 * Composable for managing cart with cookie storage
 */
export function useCartCookie() {
    const cartItems = ref<CartItem[]>(parseCartCookie());
    
    const cartCount = computed(() => {
        return cartItems.value.reduce((sum, item) => sum + item.quantity, 0);
    });
    
    const totalAmount = computed(() => {
        return cartItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    });
    
    /**
     * Add product to cart or update quantity if already exists
     */
    const addToCart = (product: Omit<CartItem, 'quantity'>, quantity: number = 1) => {
        const existingItem = cartItems.value.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cartItems.value.push({
                ...product,
                quantity
            });
        }
        
        setCartCookie(cartItems.value);
    };
    
    /**
     * Remove item from cart
     */
    const removeFromCart = (productId: string | number) => {
        cartItems.value = cartItems.value.filter(item => item.id !== productId);
        setCartCookie(cartItems.value);
    };
    
    /**
     * Update item quantity
     */
    const updateQuantity = (productId: string | number, quantity: number) => {
        const item = cartItems.value.find(item => item.id === productId);
        if (item) {
            item.quantity = Math.max(0, quantity);
            if (item.quantity === 0) {
                removeFromCart(productId);
            } else {
                setCartCookie(cartItems.value);
            }
        }
    };
    
    /**
     * Clear entire cart
     */
    const clearCart = () => {
        cartItems.value = [];
        setCartCookie(cartItems.value);
    };
    
    /**
     * Get specific cart item
     */
    const getCartItem = (productId: string | number) => {
        return cartItems.value.find(item => item.id === productId);
    };
    
    /**
     * Check if product is in cart
     */
    const isInCart = (productId: string | number) => {
        return cartItems.value.some(item => item.id === productId);
    };
    
    return {
        cartItems,
        cartCount,
        totalAmount,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        getCartItem,
        isInCart
    };
}
