<script setup lang="ts">
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customer Dashboard',
        href: '/customer-panel',
    },
];
</script>

<template>
    <Head title="Customer Dashboard" />

    <CustomerLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Welcome Section -->
            <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-800 p-6">
                <h2 class="text-2xl font-bold text-surface-900 dark:text-surface-100 mb-2">Welcome to E-Shop!</h2>
                <p class="text-surface-600 dark:text-surface-400">Manage your orders, track shipments, and explore exclusive deals.</p>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- My Orders -->
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                            <i class="pi pi-shopping-cart text-primary text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">My Orders</h3>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400 text-sm">View and track your recent orders</p>
                </div>

                <!-- Wishlist -->
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center">
                            <i class="pi pi-heart text-pink-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">Wishlist</h3>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400 text-sm">Items you've saved for later</p>
                </div>

                <!-- Profile -->
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="pi pi-user text-blue-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">My Profile</h3>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400 text-sm">Update your information</p>
                </div>

                <!-- Addresses -->
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <i class="pi pi-map-marker text-green-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">Addresses</h3>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400 text-sm">Manage delivery addresses</p>
                </div>

                <!-- Payment Methods -->
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                            <i class="pi pi-credit-card text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">Payment Methods</h3>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400 text-sm">Manage your payment options</p>
                </div>

                <!-- Support -->
                <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6 hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <i class="pi pi-comments text-purple-500 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100">Support</h3>
                    </div>
                    <p class="text-surface-600 dark:text-surface-400 text-sm">Get help and support</p>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="rounded-xl border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-800 p-6">
                <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-100 mb-4">Recent Activity</h3>
                <div class="flex items-center justify-center py-8 text-surface-500 dark:text-surface-400">
                    <div class="text-center">
                        <i class="pi pi-inbox text-4xl mb-3"></i>
                        <p>No recent activity to display</p>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
