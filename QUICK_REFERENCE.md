# 🛒 Cookie-Based Cart System - Quick Reference

## Installation & Import

```typescript
// In any Vue component
import { useCartCookie } from '@/composables/useCartCookie';
```

## Quick API

### Destructure the composable

```typescript
const {
    cartItems, // ref<CartItem[]> - Array of products
    cartCount, // ref<number> - Total quantity
    totalAmount, // ref<number> - Total price
    addToCart, // function - Add/update product
    removeFromCart, // function - Remove product
    updateQuantity, // function - Change quantity
    clearCart, // function - Empty cart
    getCartItem, // function - Get specific item
    isInCart, // function - Check if exists
} = useCartCookie();
```

## Common Operations

### Add Product

```typescript
addToCart(
    {
        id: 'product-123',
        name: 'Laptop',
        price: 45000,
        image: '/laptop.jpg',
        category: 'Electronics',
    },
    1,
); // Quantity
```

### Remove Product

```typescript
removeFromCart('product-123');
```

### Update Quantity

```typescript
updateQuantity('product-123', 5);
```

### Display Cart Count

```vue
<span>Cart: {{ cartCount }}</span>
```

### Display Total

```vue
<span>Total: TK{{ totalAmount }}</span>
```

### Loop Through Items

```vue
<div v-for="item in cartItems" :key="item.id">
    {{ item.name }} - {{ item.quantity }} x TK{{ item.price }}
</div>
```

### Clear Cart

```typescript
clearCart();
```

### Check if Product in Cart

```typescript
if (isInCart('product-123')) {
    console.log('Already in cart!');
}
```

## Cookie Details

- **Cookie Name**: `ecommerce_cart`
- **Duration**: 7 days
- **File**: `resources/js/composables/useCartCookie.ts`

## Data Structure

```json
[
    {
        "id": "prod-1",
        "name": "Product Name",
        "price": 299,
        "quantity": 2,
        "image": "/img.jpg",
        "category": "Electronics"
    }
]
```

## View in DevTools

1. Open DevTools (F12)
2. Application → Cookies
3. Look for `ecommerce_cart`
4. Decode the cookie value to see stored data

## Examples

### Navbar Integration

```vue
<template>
    <div class="cart-icon">
        <i class="pi pi-shopping-cart"></i>
        <span v-if="cartCount > 0" class="badge">{{ cartCount }}</span>
    </div>
</template>

<script setup>
import { useCartCookie } from '@/composables/useCartCookie';
const { cartCount } = useCartCookie();
</script>
```

### Product Card

```vue
<button @click="addToCart(product)">
    Add to Cart
</button>
```

### Cart Preview

```vue
<template>
    <div v-for="item in cartItems" class="cart-item">
        <span>{{ item.name }}</span>
        <input
            type="number"
            :value="item.quantity"
            @input="updateQuantity(item.id, $event.target.value)"
        />
        <button @click="removeFromCart(item.id)">Remove</button>
    </div>
</template>

<script setup>
import { useCartCookie } from '@/composables/useCartCookie';
const { cartItems, updateQuantity, removeFromCart } = useCartCookie();
</script>
```

## TypeScript Type

```typescript
interface CartItem {
    id: string | number;
    name: string;
    price: number;
    quantity: number;
    image?: string;
    category?: string;
}
```

## Notifications (PrimeVue)

```typescript
import { useToast } from 'primevue/usetoast';

const toast = useToast();

// After adding to cart
toast.add({
    severity: 'success',
    summary: 'Added',
    detail: 'Product added to cart',
    life: 2000,
});
```

## Performance Tips

1. **Memoize computations** - `cartCount` and `totalAmount` are computed automatically
2. **Batch updates** - Multiple quantity changes update cookie once
3. **Lazy load** - Cart loads from cookie on component mount
4. **No polling** - Uses reactive Vue refs

## Common Patterns

### Add with Toast

```typescript
addToCart(product, 1);
toast.add({ severity: 'success', summary: 'Added to Cart' });
```

### Add with Confirmation

```typescript
if (confirm('Add to cart?')) {
    addToCart(product, quantity);
}
```

### Add with Loading State

```typescript
const loading = ref(false);
loading.value = true;
addToCart(product, 1);
setTimeout(() => {
    loading.value = false;
}, 300);
```

### Conditional Add (Check Stock)

```typescript
if (product.quantity > 0) {
    addToCart(product, 1);
} else {
    toast.add({ severity: 'error', summary: 'Out of Stock' });
}
```

## Troubleshooting

### Cart not showing

```typescript
// Check if cookie exists
console.log(cartItems.value);
console.log(cartCount.value);
```

### Cookie not persisting

```typescript
// Check browser settings
// Cookies must be enabled
// Check cookie expiry
```

### Data loss after refresh

```typescript
// Normal if 7 days passed
// Can extend by editing COOKIE_MAX_AGE
```

### Type errors

```typescript
// Ensure proper interface
const product: CartItem = {
    id: '1',
    name: 'Product',
    price: 100,
    quantity: 1,
};
```

## Links to Full Docs

- 📘 [Full Implementation Guide](CART_COOKIE_IMPLEMENTATION.md)
- 📋 [Implementation Summary](IMPLEMENTATION_SUMMARY.md)
- ✅ [Verification Report](VERIFICATION_REPORT.md)
- 💡 [Example Component](resources/js/pages/examples/CartExample.vue)
- 🎨 [Header Example](resources/js/components/examples/HeaderWithCart.vue)

## Support

For issues or questions:

1. Check the documentation files
2. Review example components
3. Check browser DevTools for cookie data
4. Verify TypeScript types match

---

**Status**: ✅ Ready for Production  
**Last Updated**: April 11, 2026  
**Version**: 1.0
