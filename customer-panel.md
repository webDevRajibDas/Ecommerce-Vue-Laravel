Successfully implemented login redirect for normal users to `/customer-panel`. Here's what was configured:

## Changes Made:

### 1. Created Customer Panel Page

- **File:** `resources/js/pages/customer-panel/Index.vue`
- A dashboard page for normal users with quick access to:
    - My Orders
    - Wishlist
    - Profile
    - Addresses
    - Payment Methods
    - Support

### 2. Added Customer Panel Route

- **File:** `routes/web.php`
- Route: `GET /customer-panel` (requires authentication)
- Uses Inertia to render the customer panel page

### 3. Configured Role-Based Login Redirect

- **File:** `app/Providers/FortifyServiceProvider.php`
- Created `CustomLoginResponse` class that redirects users based on their role:
    - **Normal users** (is_admin = false) → `/customer-panel`
    - **Admin users** (is_admin = true) → `/dashboard`

## Test Credentials:

- Normal user: `user1@example.com` / `password123`
- After login, normal users will be redirected to `/customer-panel`

## Configuration:

- Fortify config (`config/fortify.php`) already has `home` set to `/customer-panel` as fallback
