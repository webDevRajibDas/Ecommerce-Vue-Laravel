<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

// PrimeVue Components

import {
    Badge,
    Button,
    Column,
    ConfirmDialog,
    DataTable,
    Dialog,
    IconField,
    InputIcon,
    InputText,
    Select,
    Tag,
    Textarea,
    Toolbar,
} from 'primevue';

import { useConfirm } from "primevue/useconfirm";

interface Brand {
    id: number;
    name: string;
    slug?: string;
    logo?: string | null;
    description: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    products_count: number;
}

interface Props {
    brands: {
        data: Brand[];
        links: any[];
        meta: any;
    };
    filters: {
        search?: string;
        sortField?: string;
        sortOrder?: string;
    };
    totalBrands: number;
}

const brandDialog = ref(false);
const submitted = ref(false);
const dt = ref();
const loading = ref(false);
const toast = useToast();
const selectedBrands = ref<Brand[]>([]);

const props = defineProps<Props>();
const confirm = useConfirm();
const isVisible = ref(false);

// Brand form
const brandForm = useForm({
    id: null as number | null,
    name: '',
    slug: '',
    logo: '',
    description: '',
    is_active: true,
});

// Search form
const searchForm = useForm({
    search: props.filters.search || '',
});

// Hide dialog
const hideDialog = () => {
    brandDialog.value = false;
    submitted.value = false;
    brandForm.clearErrors();
};

// Handle search
const handleSearch = () => {
    searchForm.get(route('brandsList'), {
        preserveState: true,
        replace: true,
    });
};

// Clear search
const clearSearch = () => {
    searchForm.search = '';
    handleSearch();
};

// Export to CSV
const exportCSV = () => {
    dt.value.exportCSV();
};

// Get status label
const getStatusLabel = (status: boolean) => {
    return status ? 'ACTIVE' : 'INACTIVE';
};

// Get status severity
const getStatusSeverity = (status: boolean) => {
    return status ? 'success' : 'danger';
};

// Confirm delete selected brands
const confirmDeleteSelected = () => {
    if (!selectedBrands.value.length) return;

    confirm.require({
        message: `Are you sure you want to delete ${selectedBrands.value.length} selected brands?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        accept: deleteSelectedBrands,
    });
};

// Save brand
const saveBrand = () => {
    submitted.value = true;

    if (!brandForm.name.trim()) {
        return;
    }

    if (brandForm.id) {
        // Update existing brand
        brandForm.put(route('brandsUpdate', brandForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Successful',
                    detail: 'Brand updated successfully',
                    life: 3000,
                });
                hideDialog();
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to update brand',
                    life: 3000,
                });
            },
        });
    } else {
        // Create new brand
        brandForm.post(route('brandsStore'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Successful',
                    detail: 'Brand created successfully',
                    life: 3000,
                });
                hideDialog();
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to create brand',
                    life: 3000,
                });
            },
        });
    }
};



// Delete brand
const deleteBrand = (brand: Brand) => {
    router.delete(route('brandDestroy', brand.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Successful',
                detail: 'Brand deleted successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete brand',
                life: 3000,
            });
        },
    });
};


// Confirm delete brand
const confirmDeleteBrand = (brand: Brand) => {
    confirm.require({
        message: `Are you sure you want to delete "${brand.name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        accept: () => deleteBrand(brand),
        onShow: () => {
            isVisible.value = true;
        },
        onHide: () => {
            isVisible.value = false;
        }
    });

};

const openPopup = (event) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        onShow: () => {
            isVisible.value = true;
        },
        onHide: () => {
            isVisible.value = false;
        }
    });
}




// Delete selected brands
const deleteSelectedBrands = () => {
    const ids = selectedBrands.value.map((brand) => brand.id);

    router.delete(route('brands.destroy.multiple'), {
        data: { ids },
        preserveScroll: true,
        onSuccess: () => {
            selectedBrands.value = [];
            toast.add({
                severity: 'success',
                summary: 'Successful',
                detail: 'Selected brands deleted successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete selected brands',
                life: 3000,
            });
        },
    });
};

// Open new brand dialog
const openNew = () => {
    brandForm.reset();
    brandForm.is_active = true;
    submitted.value = false;
    brandDialog.value = true;
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString();
};

// Open edit brand dialog
const editBrand = (brand: Brand) => {
    brandForm.id = brand.id;
    brandForm.name = brand.name;
    brandForm.slug = brand.slug || '';
    brandForm.logo = brand.logo || '';
    brandForm.description = brand.description || '';
    brandForm.is_active = brand.is_active;
    brandDialog.value = true;
};

const statusOptions = ref([
    { label: 'Active', value: true },
    { label: 'Inactive', value: false }
]);

const updateStatus = (brand: Brand) => {
    // Handle status update here
    console.log('Status updated:', brand.id, brand.is_active);
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'All Brands',
        href: route('brandsList'),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" title="Brands">
        <div class="card">
          
            <!-- Toolbar -->
            <Toolbar class="mb-4">
                <template #start>
                    <div class="flex gap-2">
                        <Button
                            label="New Brand"
                            icon="pi pi-plus"
                            @click="openNew"
                        />
                        <Button
                            label="Delete Selected"
                            icon="pi pi-trash"
                            severity="danger"
                            variant="outlined"
                            @click="confirmDeleteSelected"
                            :disabled="!selectedBrands?.length"
                        />
                    </div>
                </template>

                <template #end>
                    <Button
                        label="Export"
                        icon="pi pi-upload"
                        severity="secondary"
                        @click="exportCSV"
                    />
                </template>
            </Toolbar>

            <!-- DataTable -->
            <DataTable
                ref="dt"
                showGridlines
                v-model:selection="selectedBrands"
                :value="brands.data"
                dataKey="id"
                :paginator="true"
                :rows="20"
                :filters="filters"
                :loading="loading"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25, 50]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} brands"
                :globalFilterFields="['name', 'slug', 'description']"
            >
                <template #header>
                    <div
                        class="flex flex-wrap items-center justify-between gap-2"
                    >
                        <h4 class="m-0">Manage Brands</h4>
                        <div class="flex gap-2">
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText
                                    v-model="searchForm.search"
                                    placeholder="Search brands..."
                                    @keyup.enter="handleSearch"
                                />
                            </IconField>
                            <Button
                                v-if="searchForm.search"
                                icon="pi pi-times"
                                severity="secondary"
                                @click="clearSearch"
                            />
                        </div>
                    </div>
                </template>

                <Column
                    selectionMode="multiple"
                    style="width: 3rem"
                    :exportable="false"
                ></Column>

                <Column
                    field="name"
                    header="Name"
                    sortable
                    style="min-width: 14rem"
                >
                    <template #body="slotProps">
                        <div class="align-items-center flex gap-2">
                            <img
                                v-if="slotProps.data.logo"
                                :src="slotProps.data.logo"
                                alt=""
                                class="border-round h-7 w-7 object-cover"
                            />
                            <div
                                v-else
                                class="border-round h-7 w-7 flex items-center justify-center bg-primary text-white text-xs font-medium"
                            >
                                {{ slotProps.data.name?.slice(0, 2).toUpperCase() }}
                            </div>
                            <span>{{ slotProps.data.name }}</span>
                        </div>
                    </template>
                </Column>

                <Column
                    field="slug"
                    header="Slug"
                    sortable
                    style="min-width: 12rem"
                >
                    <template #body="slotProps">
                        <span class="line-clamp-2">{{
                            slotProps.data.slug || '—'
                        }}</span>
                    </template>
                </Column>

                <Column
                    field="description"
                    header="Description"
                    sortable
                    style="min-width: 20rem"
                >
                    <template #body="slotProps">
                        <span class="line-clamp-2">{{
                            slotProps.data.description || 'No description'
                        }}</span>
                    </template>
                </Column>

                <Column
                    field="is_active"
                    header="Status"
                    sortable
                    style="min-width: 8rem"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="getStatusLabel(slotProps.data.is_active)"
                            :severity="
                                getStatusSeverity(slotProps.data.is_active)
                            "
                        />
                    </template>
                </Column>

                <Column
                    field="products_count"
                    header="Products"
                    sortable
                    style="min-width: 8rem"
                >
                    <template #body="slotProps">
                        <Badge :value="slotProps.data.products_count" />
                    </template>
                </Column>

                <Column
                    field="created_at"
                    header="Created"
                    sortable
                    style="min-width: 10rem"
                >
                    <template #body="slotProps">
                        {{ formatDate(slotProps.data.created_at) }}
                    </template>
                </Column>

                <Column :exportable="false" style="min-width: 10rem">
                    <template #body="slotProps">
                        <div class="flex gap-1">
                            <Button
                                icon="pi pi-pencil"
                                variant="outlined"
                                rounded
                                severity="secondary"
                                @click="editBrand(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                variant="outlined"
                                rounded
                                severity="danger"
                                @click="confirmDeleteBrand(slotProps.data)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Brand Dialog -->
        <Dialog
            v-model:visible="brandDialog"
            :style="{ width: '650px' }"
            :header="brandForm.id ? 'Edit Brand' : 'Create New Brand'"
            :modal="true"
            class="p-fluid category-dialog"
            :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        >
            <form @submit.prevent="saveBrand" class="dialog-form">
                <!-- Header with Icon -->

                <!-- Main Form Content -->
                <div class="form-content space-y-4">
                    <!-- Brand Name -->
                    <div class="form-group">
                        <div
                            class="align-items-center mb-2 flex justify-between"
                        >
                            <label for="name" class="form-label">
                                <i class="pi pi-tag mr-2 text-primary"></i>
                                Brand Name *
                            </label>
                            <span class="text-surface-500 text-xs">
                                {{ brandForm.name.length }}/255
                            </span>
                        </div>
                        <InputText
                            id="name"
                            v-model="brandForm.name"
                            required
                            autofocus
                            placeholder="e.g., Apple, Samsung, Sony..."
                            class="p-inputtext-lg w-full"
                            :class="{
                                'p-invalid':
                                    (submitted && !brandForm.name) ||
                                    brandForm.errors.name,
                                'border-green-500':
                                    brandForm.name &&
                                    !brandForm.errors.name,
                            }"
                        />
                        <div class="form-feedback mt-2">
                            <small
                                v-if="brandForm.errors.name"
                                class="p-error align-items-center flex gap-1"
                            >
                                <i class="pi pi-exclamation-circle text-xs"></i>
                                {{ brandForm.errors.name }}
                            </small>
                            <small
                                v-else-if="submitted && !brandForm.name"
                                class="p-error align-items-center flex gap-1"
                            >
                                <i class="pi pi-exclamation-circle text-xs"></i>
                                Brand name is required
                            </small>
                            <small
                                v-else-if="
                                    brandForm.name &&
                                    !brandForm.errors.name
                                "
                                class="align-items-center flex gap-1 text-green-600"
                            >
                                <i class="pi pi-check-circle text-xs"></i>
                                Looks good!
                            </small>
                        </div>
                    </div>

                    <!-- Slug -->
                    <div class="form-group">
                        <div
                            class="align-items-center mb-2 flex justify-between"
                        >
                            <label for="slug" class="form-label">
                                <i class="pi pi-link mr-2 text-teal-500"></i>
                                Slug
                            </label>
                            <span class="text-surface-500 text-xs">
                                {{ brandForm.slug?.length || 0 }}/255
                            </span>
                        </div>
                        <InputText
                            id="slug"
                            v-model="brandForm.slug"
                            placeholder="e.g., apple, samsung, sony..."
                            class="w-full"
                            :class="{
                                'border-green-500':
                                    brandForm.slug && brandForm.slug.length > 0,
                            }"
                        />
                        <small
                            class="text-surface-500 align-items-center mt-2 flex gap-1"
                        >
                            <i class="pi pi-info-circle text-xs"></i>
                            Optional: leave blank to auto-generate
                        </small>
                    </div>

                    <!-- Logo -->
                    <div class="form-group">
                        <div
                            class="align-items-center mb-2 flex justify-between"
                        >
                            <label for="logo" class="form-label">
                                <i class="pi pi-image mr-2 text-purple-500"></i>
                                Logo URL
                            </label>
                            <span class="text-surface-500 text-xs">
                                {{ brandForm.logo?.length || 0 }}/500
                            </span>
                        </div>
                        <InputText
                            id="logo"
                            v-model="brandForm.logo"
                            placeholder="https://example.com/logo.png"
                            class="w-full"
                            :class="{
                                'border-green-500':
                                    brandForm.logo && brandForm.logo.length > 0,
                            }"
                        />
                        <small
                            class="text-surface-500 align-items-center mt-2 flex gap-1"
                        >
                            <i class="pi pi-info-circle text-xs"></i>
                            Optional: provide a public logo URL
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <div
                            class="align-items-center mb-2 flex justify-between"
                        >
                            <label for="description" class="form-label">
                                <i
                                    class="pi pi-file-edit mr-2 text-blue-500"
                                ></i>
                                Description
                            </label>
                            <span class="text-surface-500 text-xs">
                                {{ brandForm.description?.length || 0 }}/500
                            </span>
                        </div>
                        <Textarea
                            id="description"
                            v-model="brandForm.description"
                            rows="3"
                            placeholder="Describe this brand... (Optional)"
                            class="w-full"
                            :autoResize="true"
                            :class="{
                                'border-green-500':
                                    brandForm.description &&
                                    brandForm.description.length > 0,
                            }"
                        />
                        <small
                            class="text-surface-500 align-items-center mt-2 flex gap-1"
                        >
                            <i class="pi pi-info-circle text-xs"></i>
                            Optional: Add a description to help identify your
                            brand
                        </small>
                    </div>

                    <div class="field">
                        <label
                            for="status"
                            class="text-surface-700 mb-2 block font-medium"
                        >
                            Status
                        </label>
                        <Select
                            v-model="brandForm.is_active"
                            :options="statusOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Status"
                            class="w-full md:w-56"
                           
                        />
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="form-actions surface-border mt-6 flex items-center justify-between border-t-1 pt-5"
                >
                    <div class="form-help text-surface-600 text-sm">
                        <i class="pi pi-info-circle mr-2"></i>
                        Fields marked with * are required
                    </div>

                    <div class="action-buttons flex gap-2">
                        <Button
                            type="button"
                            label="Cancel"
                            icon="pi pi-times"
                            class="p-button-outlined p-button-secondary min-w-32"
                            @click="hideDialog"
                            :disabled="brandForm.processing"
                        />
                        <Button
                            type="submit"
                            :label="
                                brandForm.id
                                    ? 'Update Brand'
                                    : 'Create Brand'
                            "
                            :icon="
                                brandForm.id ? 'pi pi-save' : 'pi pi-plus'
                            "
                            class="min-w-40"
                            :severity="brandForm.id ? 'warning' : 'success'"
                            :loading="brandForm.processing"
                        />
                    </div>
                </div>
            </form>
        </Dialog>
        <!-- Confirm Dialog -->
        <ConfirmDialog />
    </AppLayout>
</template>

<style scoped>
.category-dialog {
    border-radius: 12px;
}

.dialog-form {
    padding: 0.5rem;
}

.dialog-header {
    border-bottom: 1px solid var(--surface-100);
    padding-bottom: 1.5rem;
}

.icon-container {
    transition: all 0.3s ease;
}

.icon-container:hover {
    transform: scale(1.05);
    background-color: var(--primary-100) !important;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--surface-900);
    display: flex;
    align-items: center;
}

.form-feedback {
    min-height: 1.25rem;
}

.color-picker-container :deep(.p-dropdown) {
    border-radius: 8px;
}

.color-preview,
.color-option {
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.color-preview:hover,
.color-option:hover {
    transform: scale(1.05);
}

.color-palette {
    max-width: 100%;
}

.color-chip {
    transition: all 0.2s ease;
    position: relative;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.color-chip:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.status-toggle-container {
    background: linear-gradient(
        135deg,
        var(--surface-50) 0%,
        var(--surface-0) 100%
    );
    transition: all 0.3s ease;
}

.status-toggle-container:hover {
    background: linear-gradient(
        135deg,
        var(--surface-100) 0%,
        var(--surface-50) 100%
    );
}

.form-actions {
    background: linear-gradient(to top, var(--surface-50) 0%, transparent 100%);
}

.min-w-32 {
    min-width: 8rem;
}

.min-w-40 {
    min-width: 10rem;
}

/* Custom input focus effects */
:deep(.p-inputtext:focus) {
    box-shadow: 0 0 0 2px var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

:deep(.p-inputtext.p-invalid) {
    border-color: var(--red-500) !important;
    box-shadow: 0 0 0 2px var(--red-200) !important;
}

:deep(.border-green-500) {
    border-color: var(--green-500) !important;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .color-palette {
        grid-template-columns: repeat(4, 1fr);
    }

    .form-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .action-buttons {
        justify-content: space-between;
    }
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.field {
    margin-bottom: 1.5rem;
}
</style>
