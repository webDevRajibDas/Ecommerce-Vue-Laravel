# POS Software Development — Modules & Workflow

> **Point of Sale (POS) System** — A complete guide covering all development modules, data flows, and implementation workflows for building a modern POS platform.

---

## Table of Contents

1. [System Overview](#system-overview)
2. [Core Modules](#core-modules)
3. [Module Details](#module-details)
4. [Development Workflow](#development-workflow)
5. [Database Schema](#database-schema)
6. [API Architecture](#api-architecture)
7. [Security & Compliance](#security--compliance)
8. [Testing Strategy](#testing-strategy)
9. [Deployment Pipeline](#deployment-pipeline)

---

## System Overview

A POS system consists of interconnected modules that handle sales transactions, inventory, customer management, reporting, and hardware integration. The architecture is designed for reliability, speed, and offline capability.

```
┌─────────────────────────────────────────────────────┐
│                   POS Application                    │
│  ┌──────────┐  ┌──────────┐  ┌────────────────────┐ │
│  │ Frontend │  │  API GW  │  │   Background Jobs  │ │
│  │ (Vue/React)│  │ (REST/WS)│  │   (Queue Workers)  │ │
│  └────┬─────┘  └────┬─────┘  └────────────────────┘ │
│       └─────────────┼────────────────────────────────┤
│              ┌──────▼──────┐                         │
│              │  Core Engine│                         │
│              └──────┬──────┘                         │
│        ┌────────────┼────────────┐                   │
│   ┌────▼────┐  ┌────▼────┐  ┌───▼──────┐            │
│   │  MySQL  │  │  Redis  │  │  Storage │            │
│   └─────────┘  └─────────┘  └──────────┘            │
└─────────────────────────────────────────────────────┘
```

---

## Core Modules

| # | Module | Description | Priority |
|---|--------|-------------|----------|
| 1 | **Sales & Checkout** | Transaction processing, cart, discounts | P0 — Critical |
| 2 | **Inventory Management** | Stock tracking, variants, low-stock alerts | P0 — Critical |
| 3 | **Product Catalog** | Products, categories, pricing, variations | P0 — Critical |
| 4 | **Customer Management** | Profiles, loyalty, purchase history | P1 — High |
| 5 | **Payment Processing** | Cash, card, mobile, split payments | P0 — Critical |
| 6 | **Employee & Auth** | Roles, shifts, permissions, PIN login | P1 — High |
| 7 | **Reporting & Analytics** | Sales, inventory, tax, profit reports | P1 — High |
| 8 | **Kitchen Display (KDS)** | Order routing for food/restaurant POS | P2 — Medium |
| 9 | **Hardware Integration** | Printer, barcode scanner, cash drawer | P1 — High |
| 10 | **Offline Mode** | Local sync, queue transactions offline | P1 — High |

---

## Module Details

### 1. Sales & Checkout Module

The core transaction engine. Handles all sales operations from cart creation to receipt generation.

**Key Features:**
- Add/remove/update cart items with real-time price calculation
- Apply item-level and order-level discounts (percentage or fixed)
- Tax calculation (inclusive/exclusive, multi-rate, tax groups)
- Hold & recall transactions
- Refunds, returns, and exchanges
- Receipt generation (print / email / SMS)
- Multi-currency support

**Workflow:**
```
Open Register → Add Items to Cart → Apply Discounts/Promo
     → Select Payment Method → Process Payment
     → Print/Send Receipt → Close Transaction → Update Inventory
```

**Data Entities:**
```
Transaction
  ├── id, reference_no, cashier_id, register_id
  ├── status: draft | completed | voided | refunded
  ├── subtotal, discount_total, tax_total, total
  ├── created_at, completed_at
  └── TransactionItems[]
        ├── product_id, variant_id, quantity
        ├── unit_price, discount, tax, line_total
        └── notes
```

---

### 2. Inventory Management Module

Real-time stock tracking across locations with automated reorder workflows.

**Key Features:**
- Real-time stock level tracking per location/warehouse
- Stock adjustments (receive, transfer, write-off, count)
- Multi-location inventory with transfer orders
- Low stock alerts and automatic purchase orders
- Batch/lot and expiry date tracking
- Inventory valuation (FIFO, LIFO, Average Cost)
- Stock take / cycle count workflows

**Inventory Workflow:**
```
Purchase Order Created → Supplier Ships → Goods Receipt
     → Quality Check → Stock Updated → Available for Sale
     → Sale Occurs → Stock Decremented → Low-Stock Alert?
          → Yes: Auto Reorder / Manual PO → Loop
```

**Adjustment Types:**

| Type | Effect | Use Case |
|------|--------|----------|
| `receive` | +Stock | Incoming purchase order |
| `transfer_in` | +Stock | Transfer from another location |
| `transfer_out` | −Stock | Transfer to another location |
| `sale` | −Stock | Auto-triggered on sale |
| `return` | +Stock | Customer return |
| `write_off` | −Stock | Damaged / expired goods |
| `count_adjust` | ±Stock | Physical count correction |

---

### 3. Product Catalog Module

Central repository for all product and pricing data.

**Key Features:**
- Product creation with rich descriptions, images, barcodes
- Category hierarchy (unlimited depth)
- Product variations (size, color, material, pattern)
- Multiple pricing tiers (retail, wholesale, member)
- Composite / bundle products
- Service items (non-inventory)
- Barcode and SKU management
- Bulk import/export via CSV

**Variation Matrix Example:**

```
Product: T-Shirt "Classic Crew"
├── Size: S / M / L / XL / XXL
├── Color: White / Black / Navy / Red
└── Generated Variants: 5 × 4 = 20 SKUs
      Each variant has: own SKU, barcode, stock, optional price override
```

---

### 4. Customer Management Module

360° customer view with loyalty and CRM features.

**Key Features:**
- Customer profile (contact, address, preferences)
- Purchase history and lifetime value tracking
- Loyalty points — earn on purchase, redeem on checkout
- Customer groups and tiered pricing
- Store credit / gift cards
- Customer-facing display integration
- GDPR-compliant data export and deletion

**Loyalty Workflow:**
```
Customer Identified at POS → Points Calculated on Total
     → Points Added to Balance → Threshold Reached?
          → Yes: Reward Issued (voucher / discount / tier upgrade)
     → Transaction Saved with Customer Reference
```

---

### 5. Payment Processing Module

Secure, multi-method payment handling.

**Supported Payment Methods:**
- Cash (with change calculation)
- Credit / Debit Card (via payment terminal integration)
- QR Code / Mobile Wallets (bKash, Nagad, Rocket, etc.)
- Store Credit
- Gift Cards
- Loyalty Points Redemption
- Split Payments (multiple methods in one transaction)
- Layaway / Partial Payment

**Payment Flow:**
```
Total Calculated → Customer Selects Method
     ├── Cash: Enter tendered → Calculate change → Complete
     ├── Card: Send to terminal → Await approval → Complete / Decline
     └── Mobile: Generate QR / request → Await confirmation → Complete

     All paths → Payment Record Saved → Receipt Generated
```

**Integration Targets:**
- Payment terminals: Ingenico, PAX, Verifone
- Mobile money APIs: bKash API, Nagad API
- Online gateways: Stripe, SSLCommerz, AamarPay

---

### 6. Employee & Auth Module

Access control, shift management, and activity logging.

**Role Hierarchy:**

| Role | Access Level |
|------|-------------|
| `super_admin` | Full system access |
| `manager` | Reports, voids, discounts override |
| `cashier` | Sales, basic refunds |
| `stock_clerk` | Inventory only |
| `viewer` | Read-only reports |

**Shift Workflow:**
```
Employee Clocks In (PIN / RFID) → Opening Cash Count Recorded
     → Transactions Attributed to Shift → Mid-Day Cash Drops (optional)
     → Employee Clocks Out → Closing Count → Variance Report
     → Shift Report Generated & Stored
```

**Security Features:**
- PIN / password login per employee
- Session timeout and auto-lock
- Per-action permission checks
- Full audit trail of all transactions and voids
- Manager override with separate PIN

---

### 7. Reporting & Analytics Module

Business intelligence built into the POS.

**Report Categories:**

**Sales Reports**
- Daily / weekly / monthly / custom range sales summary
- Sales by product, category, cashier, location
- Hourly sales heatmap
- Discount and void analysis

**Inventory Reports**
- Current stock levels (all locations)
- Stock movement history
- Low-stock and out-of-stock report
- Inventory valuation report

**Financial Reports**
- Revenue, COGS, gross profit
- Tax summary (by rate / by period)
- Cash register reconciliation
- Payment method breakdown

**Customer Reports**
- New vs returning customers
- Top customers by spend
- Loyalty points liability report

---

### 8. Hardware Integration Module

Unified abstraction layer for all POS peripherals.

**Supported Hardware:**

| Device | Protocol | Notes |
|--------|----------|-------|
| Receipt Printer | ESC/POS (USB/Network) | 58mm / 80mm thermal |
| Barcode Scanner | HID USB / Bluetooth | 1D / 2D / QR |
| Cash Drawer | RJ-11 via printer | Kick command |
| Customer Display | Serial / USB | VFD / LCD |
| Kitchen Printer | ESC/POS (Network) | KDS alternative |
| Weight Scale | RS-232 / USB | For deli/fresh items |
| Payment Terminal | SDK / TCP | Per-provider |

**Hardware Communication Flow:**
```
POS App → Hardware Abstraction Layer (HAL)
     ├── Print Job → Printer Driver → Receipt Printer
     ├── Cash Open → Drawer Controller → Cash Drawer
     └── Scan Event → Scanner Listener → Cart Item Added
```

---

### 9. Offline Mode Module

Ensures business continuity during network outages.

**Offline Strategy:**
- IndexedDB / SQLite local database caches products, prices, customers
- Transactions queued in local store during offline
- Sync queue processes when connectivity restored
- Conflict resolution: server-authoritative for inventory, merge for sales
- Visual indicator for sync status

**Sync Priority Queue:**
```
1. Payment confirmations (highest priority)
2. Inventory decrements
3. New customer records
4. Loyalty point updates
5. Report data (lowest priority)
```

---

## Development Workflow

### Phase 1 — Setup & Foundation (Week 1–2)

```
[ ] Initialize monorepo (pnpm workspaces)
[ ] Setup backend: Laravel / Node.js + Express
[ ] Setup frontend: Vue 3 + Pinia + PrimeVue
[ ] Database: MySQL 8 + Redis
[ ] Docker dev environment (docker-compose)
[ ] CI/CD pipeline: GitHub Actions
[ ] Code standards: ESLint, Prettier, PHP-CS-Fixer
[ ] Environment config (.env structure)
```

### Phase 2 — Core Engine (Week 3–6)

```
[ ] Auth system (JWT + refresh tokens)
[ ] Role & permission system (RBAC)
[ ] Product & category CRUD
[ ] Inventory tracking (basic)
[ ] Product variations & SKU generation
[ ] Cart engine with tax calculation
[ ] Cash payment flow
[ ] Basic receipt printing
```

### Phase 3 — Payments & Sales (Week 7–9)

```
[ ] Card payment terminal integration
[ ] Mobile payment (bKash/Nagad) integration
[ ] Split payment support
[ ] Discount engine (coupon, manual, bulk)
[ ] Refund & return workflow
[ ] Hold & recall transactions
[ ] End-of-day closing procedure
```

### Phase 4 — Inventory & Products (Week 10–12)

```
[ ] Multi-location inventory
[ ] Purchase orders & receiving
[ ] Stock adjustments & transfers
[ ] Barcode printing & label design
[ ] Low-stock alerts & notifications
[ ] Bulk product import (CSV)
[ ] Composite / bundle products
```

### Phase 5 — Customers & Loyalty (Week 13–14)

```
[ ] Customer profiles & search
[ ] Purchase history linking
[ ] Loyalty points engine
[ ] Customer groups & tiered pricing
[ ] Gift cards & store credit
[ ] Customer-facing display
```

### Phase 6 — Reports & Analytics (Week 15–16)

```
[ ] Sales dashboard (real-time)
[ ] Standard report set (sales, inventory, financial)
[ ] Custom date range reports
[ ] Export to PDF / Excel
[ ] Shift & register reconciliation reports
```

### Phase 7 — Hardening & Launch (Week 17–18)

```
[ ] Offline mode & sync engine
[ ] Performance optimization & load testing
[ ] Security audit & penetration test
[ ] UAT with pilot store
[ ] Staff training materials
[ ] Production deployment & monitoring
[ ] Go-live & hypercare support
```

---

## Database Schema

### Core Tables

```sql
-- Products
products (id, name, description, category_id, type, status, created_at)
product_variants (id, product_id, sku, barcode, price, cost, attributes JSON)
categories (id, name, parent_id, sort_order)

-- Inventory
inventory (id, variant_id, location_id, quantity, reserved_qty)
inventory_movements (id, variant_id, type, quantity, reference_id, created_by)
locations (id, name, type: store|warehouse, address)

-- Sales
transactions (id, reference_no, cashier_id, customer_id, status, total, tax, discount)
transaction_items (id, transaction_id, variant_id, qty, price, discount, tax, total)
payments (id, transaction_id, method, amount, reference, status)

-- Customers
customers (id, name, email, phone, loyalty_points, total_spend)
customer_groups (id, name, discount_type, discount_value)

-- Employees
employees (id, name, pin_hash, role_id, status)
shifts (id, employee_id, register_id, open_time, close_time, opening_cash, closing_cash)
```

---

## API Architecture

### REST Endpoints (Base: `/api/v1`)

```
POST   /auth/login               Employee login
POST   /auth/logout              End session

GET    /products                 List products (search, filter)
POST   /products                 Create product
PUT    /products/:id             Update product
DELETE /products/:id             Archive product

GET    /inventory                Current stock levels
POST   /inventory/adjust         Manual stock adjustment
POST   /inventory/transfer       Transfer between locations

POST   /transactions             Create transaction (sale/refund)
GET    /transactions/:id         Get transaction detail
POST   /transactions/:id/void    Void transaction

GET    /customers                Search customers
POST   /customers                Create customer
GET    /customers/:id/history    Purchase history

GET    /reports/sales            Sales report
GET    /reports/inventory        Inventory report
GET    /reports/shift/:id        Shift report
```

### WebSocket Events

```
ws://server/pos-channel

→ scan:barcode          Scanner detects barcode
→ payment:terminal      Terminal status update
→ order:kitchen         KDS order notification
← inventory:low-stock   Server pushes low stock alert
← sync:required         Server triggers client sync
```

---

## Security & Compliance

### Security Controls

- **Authentication:** JWT tokens with 8-hour expiry + refresh rotation
- **Authorization:** RBAC with resource-level permission checks
- **Data Encryption:** TLS 1.3 in transit, AES-256 at rest for sensitive fields
- **PCI-DSS:** Never store raw card data — use tokenization via payment terminal
- **Audit Logging:** Immutable log of all transactions, voids, logins, and config changes
- **Rate Limiting:** API endpoints throttled per employee/IP
- **Input Validation:** All inputs sanitized and validated server-side

### Compliance Checklist

- [ ] PCI-DSS SAQ-B (terminal-based card processing)
- [ ] VAT / tax calculation accuracy verified by accountant
- [ ] GDPR / local data privacy law compliance for customer data
- [ ] NBR (Bangladesh) fiscal receipt format compliance
- [ ] Data backup policy — daily snapshots, 90-day retention

---

## Testing Strategy

### Test Pyramid

```
          ┌──────────────┐
          │   E2E Tests  │  ← Cypress / Playwright (10%)
          │  (Critical   │     Full checkout, payment flows
          │   user flows)│
        ┌─┴──────────────┴─┐
        │  Integration Tests│  ← PHPUnit / Jest (30%)
        │  API endpoints,   │     DB operations, service layer
        │  Payment flows    │
      ┌─┴──────────────────┴─┐
      │      Unit Tests       │  ← PHPUnit / Vitest (60%)
      │  Pure functions,      │     Tax calc, discount engine,
      │  Calculation logic    │     cart logic, validators
      └───────────────────────┘
```

### Critical Test Scenarios

- Checkout with mixed payment methods
- Inventory decrement on sale and increment on return
- Tax calculation for multi-rate products
- Offline transaction sync without duplicates
- Permission denial for unauthorized actions
- Concurrent stock updates (race condition prevention)

---

## Deployment Pipeline

```
Developer Push
     │
     ▼
GitHub Actions CI
     ├── Lint & Format Check
     ├── Unit & Integration Tests
     └── Build Docker Image
          │
          ▼ (main branch)
     Staging Deploy
          ├── Smoke Tests
          └── UAT Sign-off
               │
               ▼ (release tag)
          Production Deploy
               ├── Blue/Green Deployment
               ├── Database Migration (zero-downtime)
               ├── Health Check
               └── Rollback if needed
```

### Infrastructure Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 / Node.js 20 |
| Frontend | Vue 3 + Vite |
| Database | MySQL 8.0 (primary) + Redis 7 (cache/queue) |
| Queue | Laravel Horizon / Bull |
| Server | Nginx + PHP-FPM |
| Containerization | Docker + Docker Compose |
| Hosting | VPS (DigitalOcean / Linode) or AWS EC2 |
| CDN | Cloudflare |
| Monitoring | Sentry (errors) + UptimeRobot (availability) |
| Backups | Automated daily to S3-compatible storage |

---

## Appendix — Tech Stack Summary

```
Frontend:     Vue 3, Pinia, Vue Router, PrimeVue, Axios, Socket.io-client
Backend:      Laravel 11 (PHP 8.3) or Node.js + Express
Database:     MySQL 8, Redis
Hardware:     escpos-php / node-escpos, serialport
Payments:     Stripe SDK, bKash API, Nagad API, payment terminal SDKs
Auth:         Laravel Sanctum / JWT
Queue:        Laravel Horizon / BullMQ
Testing:      PHPUnit, Vitest, Cypress
DevOps:       Docker, GitHub Actions, Nginx
```

---

*Document version: 1.0 | Last updated: April 2026*
