<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { usersList } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import debounce from 'lodash/debounce';


interface Props {
    usersData: Record<string, any>
    filters: Record<string, any>
    status?: string,
    totalUsers: number
}

//  Must assign defineProps to a const
const props = defineProps<Props>()
const search = ref(props.filters.search || '')

watch(
    search,
    debounce((value) => {
        const query = {};
        if (value) {
            query.search = value;
        }
        router.get(route('usersList'), query, {
            preserveState: true,
            replace: true,
            preserveScroll: true
        });
    }, 400)
)


const resetSearch = () => {
    search.value = ''; // Clear search input

    // Method 1: Navigate without search parameter
    router.get(route('usersList'), {}, {
        preserveState: false, // Force refresh
        replace: true
    });

    // Method 2: Clear URL parameter
    // const url = new URL(window.location.href);
    // url.searchParams.delete('search');
    // window.history.replaceState(null, '', url.toString());
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'All Users',
        href: usersList().url,
    },
];

</script>


<style scoped>

</style>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!--  Total Users Card -->
            <div class="bg-blue-100 border border-blue-300 text-blue-800 rounded-xl p-4 shadow-sm w-fit mb-4">
                <div class="text-3xl font-bold">Total : {{ props.totalUsers }}</div>
            </div>

            <input
                v-model="search"
                type="text"
                placeholder="Search users..."
                class="border px-3 py-2 mb-4 rounded w-full"
            />

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2 border">ID</th>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in usersData.data" :key="user.id" class="hover:bg-gray-50">
                        <td class="p-2 border">{{ user.id }}</td>
                        <td class="p-2 border">{{ user.name }}</td>
                        <td class="p-2 border">{{ user.email }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-center mt-4 space-x-1">
                <button
                    v-for="link in usersData.links"
                    :key="link.label"
                    v-html="link.label"
                    :disabled="!link.url"
                    @click="link.url && router.visit(link.url)"
                    class="px-3 py-1 border rounded"
                    :class="{ 'bg-gray-200': link.active }"
                />
            </div>
        </div>
    </AppLayout>
</template>
