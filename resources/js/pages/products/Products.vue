<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { FilterMatchMode } from '@primevue/core/api';
import { ref, reactive } from 'vue';
import { products ,productsCreate } from '@/routes';
import { useToast } from 'primevue/usetoast';
import { router, usePage } from '@inertiajs/vue3'

import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import FileUpload from 'primevue/fileupload';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Rating from 'primevue/rating';
import Tag from 'primevue/tag';
import Toolbar from 'primevue/toolbar';



const props = defineProps<{
    totalProducts?: number;
    productsData: {
        data: Array<Record<string, any>>;
        links: Record<string, any>;
        meta: Record<string, any>;
    };
}>();

// Local reactive copy if needed
const allProducts = ref(props.productsData.data);
//console.log(allProducts);
const selectedProducts = ref([]);
const toast = useToast();
const dt = ref();
const deleteProductDialog = ref(false);
const deleteProductsDialog = ref(false);

// Methods
const product = ref({
    sku: '',
    name: '',
    price: 0,
    category: '',
    description: '',
    quantity: 0,
    inventoryStatus: 'INSTOCK',

});
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});


const formatCurrency = (value) => {
    if (value)
        return value.toLocaleString('en-US', {
            style: 'currency',
            currency: 'BDT',
        });
    return;
};


const confirmDeleteProduct = (prod) => {
    product.value = prod;
    deleteProductDialog.value = true;
};

const deleteProduct = () => {
    allProducts.value = allProducts.value.filter(
        (val) => val.id !== product.value.id,
    );
    deleteProductDialog.value = false;
    product.value = {};
    toast.add({
        severity: 'success',
        summary: 'Successful',
        detail: 'Product Deleted',
        life: 3000,
    });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteProductsDialog.value = true;
};

const deleteSelectedProducts = () => {
    allProducts.value = allProducts.value.filter(
        (val) => !selectedProducts.value.includes(val),
    );
    deleteProductsDialog.value = false;
    selectedProducts.value = null;
    toast.add({
        severity: 'success',
        summary: 'Successful',
        detail: 'Products Deleted',
        life: 3000,
    });
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'INSTOCK':
            return 'success';
        case 'LOWSTOCK':
            return 'warn';
        case 'OUTOFSTOCK':
            return 'danger';
        default:
            return null;
    }
};


const goToCreateProduct = (): void => {
    window.location.href = productsCreate().url;
};

const breadcrumbs: BreadcrumbItem[] = [
   {
        title: 'All Products',
        href: products().url,
    },
    {
        title: 'Create Product',
        href: productsCreate().url,
    }
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="card">
            <Toolbar class="mb-6">
                <template #start>
                    <Button label="New Product" icon="pi pi-plus" class="mr-2"  @click="goToCreateProduct" />
                    <Button label="Delete Selected" icon="pi pi-trash" severity="danger" variant="outlined"
                        @click="confirmDeleteSelected" :disabled="!selectedProducts || !selectedProducts.length
                            " />
                </template>

                <template #end>
                    <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" customUpload
                        chooseLabel="Import" class="mr-2" auto :chooseButtonProps="{ severity: 'secondary' }" />
                    <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
                </template>
            </Toolbar>

            <DataTable ref="dt" v-model:selection="selectedProducts" :value="allProducts" dataKey="id" :paginator="true"
                :rows="20" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25, 50]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products">
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h4 class="m-0">Manage Products</h4>
                        <span class="font-semibold">Total: {{ totalProducts }}</span>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Search products..." />
                        </IconField>
                    </div>
                </template>

                <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
                <Column field="sku" header="SKU" sortable style="min-width: 12rem"></Column>
                <Column field="name" header="Name" sortable style="min-width: 16rem"></Column>
                <Column header="Image">
                    <template #body="slotProps">
                        <img :src="`/storage/${slotProps.data.image}`" :alt="slotProps.data.name" class="rounded border"
                            style="width: 64px; height: 64px; object-fit: cover" />
                    </template>
                </Column>
                <Column field="price" header="Price" sortable style="min-width: 8rem">
                    <template #body="slotProps">
                        {{ formatCurrency(slotProps.data.price) }}
                    </template>
                </Column>
                <Column field="category" header="Category" sortable style="min-width: 10rem"></Column>
                <Column field="rating" header="Reviews" sortable style="min-width: 12rem">
                    <template #body="slotProps">
                        <Rating :modelValue="slotProps.data.rating" :readonly="true" :cancel="false" />
                    </template>
                </Column>
                <Column field="quantity" header="Quantity" sortable style="min-width: 8rem"></Column>
                <Column field="inventoryStatus" header="Status" sortable style="min-width: 10rem">
                    <template #body="slotProps">
                        <Tag :value="slotProps.data.inventoryStatus" :severity="getStatusLabel(slotProps.data.inventoryStatus)
                            " />
                    </template>
                </Column>
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" variant="outlined" rounded class="mr-2"
                            @click="editProduct(slotProps.data)" />
                        <Button icon="pi pi-trash" variant="outlined" rounded severity="danger"
                            @click="confirmDeleteProduct(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>

        
        </div>
    </AppLayout>
</template>

<style scoped>
.field {
    margin-bottom: 1rem;
}

.field label {
    display: block;
    margin-bottom: 0.5rem;
}

.confirmation-content {
    display: flex;
    align-items: center;
}

.card {
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.field label {
    transition: color 0.3s ease;
}

.field:focus-within label {
    color: var(--primary-color);
}

.min-w-12 {
    min-width: 3rem;
}

.min-w-40 {
    min-width: 10rem;
}

/* Custom styling for input groups */
.p-inputgroup-addon {
    transition: all 0.3s ease;
}

.p-inputgroup:focus-within .p-inputgroup-addon {
    background-color: var(--primary-600) !important;
}

/* Rating stars customization */
.p-rating .p-rating-icon {
    color: var(--surface-400);
    transition: all 0.3s ease;
}

.p-rating .p-rating-icon.pi-star-fill {
    color: #fbbf24 !important;
    /* yellow-500 */
}

.p-rating:not(.p-disabled):hover .p-rating-icon:hover {
    transform: scale(1.2);
}
</style>
