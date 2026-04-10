# Cookie-Based Shopping Cart System

## Overview

This implementation provides a cookie-based shopping cart system that persists cart data for 7 days without requiring user authentication or database storage for temporary cart data.

## Features

- ✅ **Cookie-based persistence** - Cart data stored in browser cookies for 7 days
- ✅ **No backend required** - Cart management works entirely on the frontend
- ✅ **Type-safe** - Full TypeScript support
- ✅ **Real-time updates** - Cart count and totals update instantly
- ✅ **Toast notifications** - User feedback on cart actions
- ✅ **Responsive** - Works on all devices
- ✅ **GDPR compliant** - Users can clear cart by clearing browser cookies

## Architecture

### Files Created

1. **`resources/js/composables/useCartCookie.ts`** - Main cart composable
2. **Updated `resources/js/pages/homepages/FeaturedProductsSection.vue`** - Add to Cart button with functionality
3. **Updated `resources/js/pages/homepages/CartBusket.vue`** - Cart display component using composable

## Usage

### Using the Cart Composable

```typescript
import { useCartCookie } from '@/composables/useCartCookie';

export default {
    setup() {
        const {
            cartItems, // ref - Array of cart items
            cartCount, // ref - Total quantity count
            totalAmount, // ref - Total cart amount
            addToCart, // function - Add/update item
            removeFromCart, // function - Remove item
            updateQuantity, // function - Update quantity
            clearCart, // function - Empty entire cart
            getCartItem, // function - Get specific item
            isInCart, // function - Check if item exists
        } = useCartCookie();

        return {
            cartItems,
            cartCount,
            totalAmount,
            addToCart,
            removeFromCart,
        };
    },
};
```

### Add Product to Cart

```typescript
// Single quantity
addToCart(
    {
        id: 'prod-123',
        name: 'Product Name',
        price: 299,
        image: '/path/to/image.jpg',
        category: 'Electronics',
    },
    1,
);

// Multiple quantities
addToCart(
    {
        id: 'prod-123',
        name: 'Product Name',
        price: 299,
        image: '/path/to/image.jpg',
        category: 'Electronics',
    },
    3,
);
```

### Display Cart Count

```vue
<template>
    <div class="cart-badge">{{ cartCount }} items</div>
</template>

<script setup>
import { useCartCookie } from '@/composables/useCartCookie';
const { cartCount } = useCartCookie();
</script>
```

### Remove Item from Cart

```typescript
removeFromCart('prod-123');
```

### Update Item Quantity

```typescript
updateQuantity('prod-123', 5);
```

### Clear Cart

```typescript
clearCart();
```

## Cookie Details

### Cookie Name

- **Key**: `ecommerce_cart`
- **Max Age**: 7 days
- **Path**: `/`
- **SameSite**: `Lax` (for CSRF protection)

### Cookie Data Structure

```json
[
    {
        "id": "prod-123",
        "name": "Product Name",
        "price": 299,
        "quantity": 2,
        "image": "/path/to/image.jpg",
        "category": "Electronics"
    }
]
```

## Component Integration

### FeaturedProductsSection Component

**Features:**

- Products carousel display
- Add to Cart button with loading state
- Cart count badge on top-right
- Toast notification on success
- Prevents adding out-of-stock items
- Disabled button state for unavailable items

**Usage:**

```vue
<FeaturedProductsSection
    :products="products"
    :responsiveOptions="responsiveOptions"
/>
```

### CartBusket Component (Header)

**Features:**

- Fixed sidebar cart icon with badge
- Glass morphism design
- Hover preview showing cart items
- Total price display
- View Cart and Checkout buttons
- Animated badge

**Use in Layout:**

```vue
<CartBusket />
```

## Type Definitions

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

## Browser Compatibility

- Works on all modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript enabled
- Gracefully handles cases where cookies are disabled

## Testing the Implementation

### Test 1: Add Product

1. Navigate to Featured Products section
2. Click "Add to Cart" on any product
3. Check cart count increases
4. Verify toast notification appears

### Test 2: Check Cart Cookie

1. Open DevTools (F12)
2. Go to Application → Cookies
3. Find `ecommerce_cart` cookie
4. Verify JSON data structure

### Test 3: Cart Persistence

1. Add products to cart
2. Close and reopen browser/tab
3. Cart data should persist (within 7 days)

### Test 4: Cookie Expiry

1. Add product to cart
2. Wait 7 days (or manually set cookie expiry in DevTools)
3. Cart will be automatically cleared

## Customization

### Change Cookie Duration

Edit `useCartCookie.ts`:

```typescript
const COOKIE_MAX_AGE = 14 * 24 * 60 * 60; // 14 days
```

### Change Cookie Name

Edit `useCartCookie.ts`:

```typescript
const CART_COOKIE_NAME = 'my_custom_cart_name';
```

### Format Price Display

Edit any component:

```typescript
const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price);
};
```

## Limitations & Considerations

1. **Client-side only** - Cart data not synced with backend
2. **Storage limit** - Cookie storage is limited (~4KB)
3. **No user association** - Cart not tied to user account
4. **Browser-specific** - Cart data not shared across browsers
5. **Clear on logout** - May want to clear cart when user logs out

## Future Enhancements

- [ ] Sync with backend when user logs in
- [ ] Add cart to localStorage as backup
- [ ] Implement cart recovery after browser crash
- [ ] Add cart analytics
- [ ] Implement wishlist feature
- [ ] Add quantity selector modal

## Troubleshooting

### Cart not persisting

- Check if cookies are enabled
- Verify cookie expiry time
- Check browser's cookie privacy settings

### Cart count not updating

- Ensure `useCartCookie` is imported correctly
- Check if Toast notifications are available

### Data loss after 7 days

- This is expected behavior
- Users can be reminded to checkout before expiry

## Security Notes

- This is client-side storage - not secure for sensitive operations
- For production, consider syncing with backend user account
- Use HTTPS to prevent cookie interception
- Validate cart data on backend before processing payment
