<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { categoriesList } from '@/routes';
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

interface Category {
    id: number;
    name: string;
    description: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    products_count: number;
}

interface Props {
    categories: {
        data: Category[];
        links: any[];
        meta: any;
    };
    filters: {
        search?: string;
        sortField?: string;
        sortOrder?: string;
    };
    totalCategories: number;
}

const categoryDialog = ref(false);
const submitted = ref(false);
const dt = ref();
const loading = ref(false);
const toast = useToast();
const selectedCategories = ref<Category[]>([]);

const props = defineProps<Props>();
const confirm = useConfirm();
const isVisible = ref(false);

// Category form
const categoryForm = useForm({
    id: null as number | null,
    name: '',
    description: '',
    is_active: true,
});

// Search form
const searchForm = useForm({
    search: props.filters.search || '',
});

// Hide dialog
const hideDialog = () => {
    categoryDialog.value = false;
    submitted.value = false;
    categoryForm.clearErrors();
};

// Handle search
const handleSearch = () => {
    searchForm.get(route('categories.index'), {
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

// Confirm delete selected categories
const confirmDeleteSelected = () => {
    if (!selectedCategories.value.length) return;

    confirm.require({
        message: `Are you sure you want to delete ${selectedCategories.value.length} selected categories?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        accept: deleteSelectedCategories,
    });
};

// Save category
const saveCategory = () => {
    submitted.value = true;

    if (!categoryForm.name.trim()) {
        return;
    }

    if (categoryForm.id) {
        // Update existing category
        categoryForm.put(route('categoriesUpdate', categoryForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Successful',
                    detail: 'Category updated successfully',
                    life: 3000,
                });
                hideDialog();
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to update category',
                    life: 3000,
                });
            },
        });
    } else {
        // Create new category
        categoryForm.post(route('categoriesStore'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Successful',
                    detail: 'Category created successfully',
                    life: 3000,
                });
                hideDialog();
            },
            onError: (errors) => {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Failed to create category',
                    life: 3000,
                });
            },
        });
    }
};



// Delete category
const deleteCategory = (cat: Category) => {
    router.delete(route('categoryDestroy', cat.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Successful',
                detail: 'Category deleted successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete category',
                life: 3000,
            });
        },
    });
};


// Confirm delete category
const confirmDeleteCategory = (cat: Category) => {
    confirm.require({
        message: `Are you sure you want to delete "${cat.name}"?`,
        header: 'Confirm Delete',
        icon: 'pi pi-exclamation-triangle',
        accept: () => deleteCategory(cat),
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




// Delete selected categories
const deleteSelectedCategories = () => {
    const ids = selectedCategories.value.map((cat) => cat.id);

    router.delete(route('categories.destroy.multiple'), {
        data: { ids },
        preserveScroll: true,
        onSuccess: () => {
            selectedCategories.value = [];
            toast.add({
                severity: 'success',
                summary: 'Successful',
                detail: 'Selected categories deleted successfully',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete selected categories',
                life: 3000,
            });
        },
    });
};

// Open new category dialog
const openNew = () => {
    categoryForm.reset();
    categoryForm.is_active = true;
    submitted.value = false;
    categoryDialog.value = true;
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString();
};

// Open edit category dialog
const editCategory = (cat: Category) => {
    categoryForm.id = cat.id;
    categoryForm.name = cat.name;
    categoryForm.description = cat.description || '';
    categoryForm.is_active = cat.is_active;
    categoryDialog.value = true;
};

const statusOptions = ref([
    { label: 'Active', value: true },
    { label: 'Inactive', value: false }
]);

const updateStatus = (cat: Category) => {
    // Handle status update here
    console.log('Status updated:', cat.id, cat.is_active);
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'All Categories',
        href: categoriesList().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" title="Categories">
        <div class="card">
          
            <!-- Toolbar -->
            <Toolbar class="mb-4">
                <template #start>
                    <div class="flex gap-2">
                        <Button
                            label="New Category"
                            icon="pi pi-plus"
                            @click="openNew"
                        />
                        <Button
                            label="Delete Selected"
                            icon="pi pi-trash"
                            severity="danger"
                            variant="outlined"
                            @click="confirmDeleteSelected"
                            :disabled="!selectedCategories?.length"
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
                v-model:selection="selectedCategories"
                :value="categories.data"
                dataKey="id"
                :paginator="true"
                :rows="20"
                :filters="filters"
                :loading="loading"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25, 50]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} categories"
                :globalFilterFields="['name', 'description']"
            >
                <template #header>
                    <div
                        class="flex flex-wrap items-center justify-between gap-2"
                    >
                        <h4 class="m-0">Manage Categories</h4>
                        <div class="flex gap-2">
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText
                                    v-model="searchForm.search"
                                    placeholder="Search categories..."
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
                            <div
                                class="border-round h-3 w-3"
                                :style="{
                                    backgroundColor: slotProps.data.color,
                                }"
                            ></div>
                            <span>{{ slotProps.data.name }}</span>
                        </div>
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
                                @click="editCategory(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                variant="outlined"
                                rounded
                                severity="danger"
                                @click="confirmDeleteCategory(slotProps.data)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Category Dialog -->
        <Dialog
            v-model:visible="categoryDialog"
            :style="{ width: '650px' }"
            :header="categoryForm.id ? 'Edit Category' : 'Create New Category'"
            :modal="true"
            class="p-fluid category-dialog"
            :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        >
            <form @submit.prevent="saveCategory" class="dialog-form">
                <!-- Header with Icon -->

                <!-- Main Form Content -->
                <div class="form-content space-y-4">
                    <!-- Category Name -->
                    <div class="form-group">
                        <div
                            class="align-items-center mb-2 flex justify-between"
                        >
                            <label for="name" class="form-label">
                                <i class="pi pi-tag mr-2 text-primary"></i>
                                Category Name *
                            </label>
                            <span class="text-surface-500 text-xs">
                                {{ categoryForm.name.length }}/255
                            </span>
                        </div>
                        <InputText
                            id="name"
                            v-model="categoryForm.name"
                            required
                            autofocus
                            placeholder="e.g., Electronics, Clothing, Books..."
                            class="p-inputtext-lg w-full"
                            :class="{
                                'p-invalid':
                                    (submitted && !categoryForm.name) ||
                                    categoryForm.errors.name,
                                'border-green-500':
                                    categoryForm.name &&
                                    !categoryForm.errors.name,
                            }"
                        />
                        <div class="form-feedback mt-2">
                            <small
                                v-if="categoryForm.errors.name"
                                class="p-error align-items-center flex gap-1"
                            >
                                <i class="pi pi-exclamation-circle text-xs"></i>
                                {{ categoryForm.errors.name }}
                            </small>
                            <small
                                v-else-if="submitted && !categoryForm.name"
                                class="p-error align-items-center flex gap-1"
                            >
                                <i class="pi pi-exclamation-circle text-xs"></i>
                                Category name is required
                            </small>
                            <small
                                v-else-if="
                                    categoryForm.name &&
                                    !categoryForm.errors.name
                                "
                                class="align-items-center flex gap-1 text-green-600"
                            >
                                <i class="pi pi-check-circle text-xs"></i>
                                Looks good!
                            </small>
                        </div>
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
                                {{ categoryForm.description?.length || 0 }}/500
                            </span>
                        </div>
                        <Textarea
                            id="description"
                            v-model="categoryForm.description"
                            rows="3"
                            placeholder="Describe this category... (Optional)"
                            class="w-full"
                            :autoResize="true"
                            :class="{
                                'border-green-500':
                                    categoryForm.description &&
                                    categoryForm.description.length > 0,
                            }"
                        />
                        <small
                            class="text-surface-500 align-items-center mt-2 flex gap-1"
                        >
                            <i class="pi pi-info-circle text-xs"></i>
                            Optional: Add a description to help organize your
                            products
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
                            v-model="categoryForm.is_active"
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
                            :disabled="categoryForm.processing"
                        />
                        <Button
                            type="submit"
                            :label="
                                categoryForm.id
                                    ? 'Update Category'
                                    : 'Create Category'
                            "
                            :icon="
                                categoryForm.id ? 'pi pi-save' : 'pi pi-plus'
                            "
                            class="min-w-40"
                            :severity="categoryForm.id ? 'warning' : 'success'"
                            :loading="categoryForm.processing"
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
