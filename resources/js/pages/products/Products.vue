<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { FilterMatchMode } from '@primevue/core/api';
import { ref, onMounted } from 'vue';
import products from '@/routes/products';
import { useToast } from 'primevue/usetoast';
import { router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import type { TreeNode } from 'primevue/treenode';
import ProductVariations from './ProductVariations.vue';

import Button from 'primevue/button';
import Chip from 'primevue/chip';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Divider from 'primevue/divider';
import Editor from 'primevue/editor';
import FileUpload from 'primevue/fileupload';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Tag from 'primevue/tag';
import Toolbar from 'primevue/toolbar';
import Textarea from 'primevue/textarea';
import Select from 'primevue/select';
import Checkbox from 'primevue/checkbox';
import Toast from 'primevue/toast';
import TreeSelect from 'primevue/treeselect';

interface ProductProp {
    totalProducts?: number;
    productsData: {
        data: Array<Record<string, any>>;
        links: Record<string, any>;
        meta: Record<string, any>;
    };
    filters?: {
        search: string | null;
    };
    categories?: TreeNode[];
    brands?: Array<{ id: number | string; name: string }>;
}

const props = defineProps<ProductProp>();

// Local reactive copy
const allProducts = ref(props.productsData.data);
const selectedProducts = ref<any[]>([]);
const toast = useToast();
const dt = ref();
const deleteProductDialog = ref(false);
const deleteProductsDialog = ref(false);
const productDialog = ref(false);
const editMode = ref(false);
const viewMode = ref(false);
const productLoading = ref(false);
const tagInput = ref('');
const seoCollapsed = ref(true);
const variationsKey = ref(0);

type TreeSelectValue = number | string | Record<string, boolean> | null;

// Product form for modal editing
const productForm = useForm({
    name: '',
    slug: '',
    description: '',
    body: '',
    price: 0,
    sale_price: null as number | null,
    cost_price: null as number | null,
    sku: '',
    quantity: 0,
    track_quantity: true,
    low_stock_threshold: null as number | null,
    barcode: '',
    weight: null as number | null,
    length: null as number | null,
    width: null as number | null,
    height: null as number | null,
    category_id: null as number | string | null,
    brand_id: null as number | string | null,
    is_visible: true,
    is_featured: false,
    type: 'simple' as 'simple' | 'variable' | 'digital' | 'booking',
    tags: [] as string[],
    attributes: {} as Record<string, any>,
    warranty: '',
    video_url: '',
    seo_title: '',
    seo_description: '',
    seo_keywords: [] as string[],
    published_at: '' as string | null,
    image: null as File | null,
    gallery_images: [] as File[],
    remove_images: [] as number[],
});

// Data sources - use props if available, otherwise use defaults
const categories = ref<TreeNode[]>(props.categories || [
    { key: '1', label: 'Electronics', data: { id: 1 }, children: [
        { key: '2', label: 'Phones & Accessories', data: { id: 2 } },
        { key: '3', label: 'Computers & Laptops', data: { id: 3 } },
        { key: '4', label: 'Audio & Headphones', data: { id: 4 } }
    ]},
    { key: '5', label: 'Fashion', data: { id: 5 }, children: [
        { key: '6', label: 'Men\'s Clothing', data: { id: 6 } },
        { key: '7', label: 'Women\'s Clothing', data: { id: 7 } },
        { key: '8', label: 'Accessories', data: { id: 8 } }
    ]}
]);

const brands = ref<Array<{ id: number | string; name: string }>>(props.brands || [
    { id: 1, name: 'Apple' },
    { id: 2, name: 'Samsung' },
    { id: 3, name: 'Sony' },
    { id: 4, name: 'Nike' },
    { id: 5, name: 'Adidas' }
]);

const productTypes = ref([
    { label: 'Simple', value: 'simple' },
    { label: 'Variable', value: 'variable' },
    { label: 'Digital', value: 'digital' },
    { label: 'Booking', value: 'booking' }
]);

const currentEditingProduct = ref<any>(null);
const imagePreview = ref('');
const galleryPreviews = ref<string[]>([]);
const existingImages = ref<any[]>([]);
const tableFilters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

// Methods
const formatCurrency = (value: number) => {
    if (value)
        return value.toLocaleString('en-US', {
            style: 'currency',
            currency: 'BDT',
        });
    return '';
};

const normalizeTreeSelectValue = (value: TreeSelectValue): number | null => {
    if (value === null || value === undefined || value === '') {
        return null;
    }

    if (typeof value === 'number') {
        return value;
    }

    if (typeof value === 'string') {
        const numericValue = Number(value);
        return Number.isFinite(numericValue) ? numericValue : null;
    }

    const selectedKey = Object.keys(value).find((key) => value[key]);
    if (!selectedKey) {
        return null;
    }

    const numericValue = Number(selectedKey);
    return Number.isFinite(numericValue) ? numericValue : null;
};

const toTreeSelectValue = (value: number | string | null) => {
    if (value === null || value === undefined || value === '') {
        return null;
    }

    return { [String(value)]: true };
};

const buildVariationPayload = (productData: any) => {
    const variation = productData?.variations?.[0];

    if (!variation) {
        return {
            hasVariations: false,
            selectedTypes: [],
            options: {},
            totalOptions: 0,
            totalCombinations: 0,
        };
    }

    const selectedTypes = variation.variation_types || [];
    const options: Record<string, string[]> = {
        size: variation.size_options || [],
        color: variation.color_options || [],
        material: variation.material_options || [],
        pattern: variation.pattern_options || [],
        style: variation.style_options || [],
    };

    const totalOptions = selectedTypes.reduce((sum: number, type: string) => sum + (options[type]?.length || 0), 0);
    const totalCombinations = selectedTypes.reduce((productCount: number, type: string) => {
        const count = options[type]?.length || 0;
        return count > 0 ? productCount * count : productCount;
    }, selectedTypes.length ? 1 : 0);

    return {
        hasVariations: selectedTypes.length > 0,
        selectedTypes,
        options,
        totalOptions,
        totalCombinations,
    };
};

const resetProductDialogState = () => {
    productForm.reset();
    productForm.clearErrors();
    currentEditingProduct.value = null;
    imagePreview.value = '';
    galleryPreviews.value = [];
    existingImages.value = [];
    tagInput.value = '';
    seoCollapsed.value = true;
    variationsKey.value += 1;
};

const assignProductData = (productData: any) => {
    productForm.name = productData.name || '';
    productForm.slug = productData.slug || '';
    productForm.description = productData.description || '';
    productForm.body = productData.body || '';
    productForm.price = parseFloat(productData.price) || 0;
    productForm.sale_price = productData.sale_price ? parseFloat(productData.sale_price) : null;
    productForm.cost_price = productData.cost_price ? parseFloat(productData.cost_price) : null;
    productForm.sku = productData.sku || '';
    productForm.quantity = productData.quantity || 0;
    productForm.track_quantity = Boolean(productData.track_quantity);
    productForm.low_stock_threshold = productData.low_stock_threshold || null;
    productForm.barcode = productData.barcode || '';
    productForm.weight = productData.weight ? parseFloat(productData.weight) : null;
    productForm.length = productData.length ? parseFloat(productData.length) : null;
    productForm.width = productData.width ? parseFloat(productData.width) : null;
    productForm.height = productData.height ? parseFloat(productData.height) : null;
    productForm.category_id = toTreeSelectValue(productData.category_id);
    productForm.brand_id = productData.brand_id || null;
    productForm.is_visible = Boolean(productData.is_visible);
    productForm.is_featured = Boolean(productData.is_featured);
    productForm.type = productData.type || 'simple';
    productForm.tags = Array.isArray(productData.tags) ? productData.tags : [];
    productForm.attributes = {
        ...(productData.attributes || {}),
        variations: buildVariationPayload(productData),
    };
    productForm.warranty = productData.warranty || '';
    productForm.video_url = productData.video_url || '';
    productForm.seo_title = productData.seo_title || '';
    productForm.seo_description = productData.seo_description || '';
    productForm.seo_keywords = Array.isArray(productData.seo_keywords) ? productData.seo_keywords : [];
    productForm.published_at = productData.published_at ? new Date(productData.published_at).toISOString().slice(0, 16) : null;
    productForm.image = null;
    productForm.gallery_images = [];
    productForm.remove_images = [];
    existingImages.value = Array.isArray(productData.images) ? productData.images : [];
    imagePreview.value = '';
    galleryPreviews.value = [];
    variationsKey.value += 1;
};

const openProductDialog = async (prod: any, mode: 'view' | 'edit') => {
    editMode.value = mode === 'edit';
    viewMode.value = mode === 'view';
    productLoading.value = true;
    productDialog.value = true;
    currentEditingProduct.value = prod;
    resetProductDialogState();
    currentEditingProduct.value = prod;

    try {
        const response = await fetch(`/dashboard/products/${prod.id}/data`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error('Failed to load product');
        }

        const payload = await response.json();
        if (payload.categories) {
            categories.value = payload.categories;
        }
        if (payload.brands) {
            brands.value = payload.brands;
        }

        assignProductData(payload.product);
    } catch {
        productDialog.value = false;
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load product data',
            life: 3000,
        });
    } finally {
        productLoading.value = false;
    }
};

const handleVariationUpdate = (data: any) => {
    productForm.attributes = {
        ...productForm.attributes,
        variations: data,
    };

    if (data.hasVariations) {
        productForm.type = 'variable';
    } else if (productForm.type === 'variable') {
        productForm.type = 'simple';
    }
};

const addTag = () => {
    if (viewMode.value) return;
    const value = tagInput.value.trim();
    if (value && !productForm.tags.includes(value)) {
        productForm.tags = [...productForm.tags, value];
    }
    tagInput.value = '';
};

const removeTag = (index: number) => {
    if (viewMode.value) return;
    productForm.tags = productForm.tags.filter((_, tagIndex) => tagIndex !== index);
};

const handleTagKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' || event.key === ',') {
        event.preventDefault();
        addTag();
    }
};

const confirmDeleteProduct = (prod: any) => {
    deleteProductDialog.value = true;
    currentEditingProduct.value = prod;
};

const deleteProduct = () => {
    router.delete(products.destroy(currentEditingProduct.value.id).url, {
        preserveScroll: true,
        onSuccess: () => {
            deleteProductDialog.value = false;
            currentEditingProduct.value = null;
            toast.add({
                severity: 'success',
                summary: 'Successful',
                detail: 'Product Deleted',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete product',
                life: 3000,
            });
        }
    });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteProductsDialog.value = true;
};

const deleteSelectedProducts = () => {
    const ids = selectedProducts.value.map((p: any) => p.id);
    
    router.post('/dashboard/products/bulk-delete', {
        ids: ids
    }, {
        preserveScroll: true,
        onSuccess: () => {
            deleteProductsDialog.value = false;
            selectedProducts.value = [];
            toast.add({
                severity: 'success',
                summary: 'Successful',
                detail: 'Products Deleted',
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to delete products',
                life: 3000,
            });
        }
    });
};

const getStatusSeverity = (isVisible: boolean): 'success' | 'info' | 'warn' | 'danger' | 'secondary' | 'contrast' => {
    return isVisible ? 'success' : 'info';
};

const goToCreateProduct = (): void => {
    window.location.href = products.create().url;
};

// Modal Edit Functions
const editProduct = (prod: any) => openProductDialog(prod, 'edit');
const viewProduct = (prod: any) => openProductDialog(prod, 'view');

const closeProductDialog = () => {
    productDialog.value = false;
    editMode.value = false;
    viewMode.value = false;
    productLoading.value = false;
    resetProductDialogState();
};

const saveProduct = () => {
    if (editMode.value && currentEditingProduct.value) {
        productForm.category_id = normalizeTreeSelectValue(productForm.category_id as TreeSelectValue) as any;
        productForm.transform((data: Record<string, any>) => {
            const fd = new FormData();
            fd.append('_method', 'put');

            for (const [key, value] of Object.entries(data)) {
                if (value === null || value === undefined || value === '') {
                    continue;
                }

                if (key === 'attributes' || key === 'tags' || key === 'seo_keywords' || key === 'remove_images') {
                    fd.append(key, JSON.stringify(value));
                } else if (key === 'gallery_images' && Array.isArray(value)) {
                    value.forEach((file: File) => fd.append('gallery_images[]', file));
                } else if (value instanceof File) {
                    fd.append(key, value);
                } else if (typeof value === 'boolean') {
                    fd.append(key, value ? '1' : '0');
                } else {
                    fd.append(key, String(value));
                }
            }

            return fd;
        }).post(products.update(currentEditingProduct.value.id).url, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                productDialog.value = false;
                toast.add({
                    severity: 'success',
                    summary: 'Successful',
                    detail: 'Product updated successfully',
                    life: 3000,
                });
                closeProductDialog();
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Please fix the validation errors',
                    life: 5000,
                });
            }
        });
    }
};

const onImageSelect = (event: any) => {
    if (viewMode.value) return;
    const file = event.files[0];
    if (file) {
        productForm.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    if (viewMode.value) return;
    productForm.image = null;
    imagePreview.value = '';
};

const onGallerySelect = (event: any) => {
    if (viewMode.value) return;
    if (event.files && event.files.length > 0) {
        productForm.gallery_images = [...productForm.gallery_images, ...event.files];
        
        // Create previews
        event.files.forEach((file: File) => {
            galleryPreviews.value.push(URL.createObjectURL(file));
        });
    }
};

const removeGalleryImage = (index: number) => {
    if (viewMode.value) return;
    productForm.gallery_images.splice(index, 1);
    galleryPreviews.value.splice(index, 1);
};

const removeExistingImage = (imageId: number) => {
    if (viewMode.value) return;
    if (!productForm.remove_images.includes(imageId)) {
        productForm.remove_images.push(imageId);
    }
    existingImages.value = existingImages.value.filter(img => img.id !== imageId);
};

const generateSlug = () => {
    if (!productForm.name || viewMode.value) return;
    productForm.slug = productForm.name.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'All Products',
        href: products.index().url,
    }
];

onMounted(() => {
    // Update categories and brands from props if available
    if (props.categories) {
        categories.value = props.categories;
    }
    if (props.brands) {
        brands.value = props.brands;
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="card">
            <Toolbar class="mb-6">
                <template #start>
                    <Button label="New Product" icon="pi pi-plus" class="mr-2" @click="goToCreateProduct" />
                    <Button label="Delete Selected" icon="pi pi-trash" severity="danger" variant="outlined"
                        @click="confirmDeleteSelected" :disabled="!selectedProducts || !selectedProducts.length" />
                </template>

                <template #end>
                    <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" customUpload
                        chooseLabel="Import" class="mr-2" auto :chooseButtonProps="{ severity: 'secondary' }" />
                    <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
                </template>
            </Toolbar>

            <DataTable ref="dt" v-model:selection="selectedProducts" :value="allProducts" dataKey="id" :paginator="true"
                :rows="20"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25, 50]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
                :filters="tableFilters">
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h4 class="m-0">Manage Products</h4>
                        <span class="font-semibold">Total: {{ totalProducts }}</span>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="tableFilters['global'].value" placeholder="Search products..." />
                        </IconField>
                    </div>
                </template>

                <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
                <Column field="sku" header="SKU" sortable style="min-width: 12rem"></Column>
                <Column field="name" header="Name" sortable style="min-width: 16rem"></Column>
                <Column header="Image">
                    <template #body="slotProps">
                        <img v-if="slotProps.data.main_image" 
                             :src="`/storage/${slotProps.data.main_image.path}`" 
                             :alt="slotProps.data.name" 
                             class="rounded border"
                             style="width: 64px; height: 64px; object-fit: cover" />
                        <div v-else class="flex align-items-center justify-content-center bg-surface-100 rounded border" 
                             style="width: 64px; height: 64px">
                            <i class="pi pi-image text-surface-400 text-xl"></i>
                        </div>
                    </template>
                </Column>
                <Column field="price" header="Price" sortable style="min-width: 8rem">
                    <template #body="slotProps">
                        {{ formatCurrency(slotProps.data.price) }}
                    </template>
                </Column>
                <Column field="category.name" header="Category" sortable style="min-width: 10rem">
                    <template #body="slotProps">
                        {{ slotProps.data.category?.name || '-' }}
                    </template>
                </Column>
                <Column field="quantity" header="Quantity" sortable style="min-width: 8rem"></Column>
                <Column field="is_visible" header="Status" sortable style="min-width: 10rem">
                    <template #body="slotProps">
                        <Tag :value="slotProps.data.is_visible ? 'Published' : 'Draft'" 
                             :severity="getStatusSeverity(slotProps.data.is_visible)" />
                    </template>
                </Column>
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button icon="pi pi-eye" variant="outlined" rounded class="mr-2"
                            @click="viewProduct(slotProps.data)" />
                        <Button icon="pi pi-pencil" variant="outlined" rounded class="mr-2"
                            @click="editProduct(slotProps.data)" />
                        <Button icon="pi pi-trash" variant="outlined" rounded severity="danger"
                            @click="confirmDeleteProduct(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>

            <!-- Product Edit Modal -->
            <Dialog v-model:visible="productDialog" :modal="true" 
                    :header="viewMode ? 'View Product' : 'Edit Product'" 
                    :style="{ width: '90vw', maxWidth: '1200px', maxHeight: '90vh' }"
                    :closable="true" :dismissableMask="false"
                    @hide="closeProductDialog">
                <div v-if="productLoading" class="py-8 text-center text-surface-500">
                    <i class="pi pi-spin pi-spinner mb-3 text-3xl"></i>
                    <div>Loading product...</div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" v-else>
                    <!-- Left Column - Basic Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="field mb-4">
                            <label for="name" class="text-surface-700 mb-2 block font-medium">
                                Product Name *
                            </label>
                            <InputText id="name" v-model="productForm.name"
                                       placeholder="Enter product name"
                                       class="w-full"
                                       :class="{ 'p-invalid': productForm.errors.name }"
                                       :disabled="viewMode"
                                       @input="generateSlug" />
                            <small v-if="productForm.errors.name" class="p-error">{{ productForm.errors.name }}</small>
                        </div>

                        <div class="field mb-4">
                            <label for="slug" class="text-surface-700 mb-2 block font-medium">
                                Slug
                            </label>
                            <InputText id="slug" v-model="productForm.slug"
                                       placeholder="auto-generated-from-name"
                                       class="w-full"
                                       :class="{ 'p-invalid': productForm.errors.slug }"
                                       :disabled="viewMode" />
                            <small v-if="productForm.errors.slug" class="p-error">{{ productForm.errors.slug }}</small>
                        </div>

                        <div class="field mb-4">
                            <label for="description" class="text-surface-700 mb-2 block font-medium">
                                Short Description
                            </label>
                            <Textarea id="description" v-model="productForm.description" rows="3"
                                      class="w-full"
                                      :class="{ 'p-invalid': productForm.errors.description }"
                                      :disabled="viewMode" />
                            <small v-if="productForm.errors.description" class="p-error">{{ productForm.errors.description }}</small>
                        </div>

                        <div class="field mb-4">
                            <label for="body" class="text-surface-700 mb-2 block font-medium">
                                Long Description
                            </label>
                            <Editor id="body" v-model="productForm.body" editorStyle="height: 220px"
                                    :class="{ 'p-invalid': productForm.errors.body }" />
                            <small v-if="productForm.errors.body" class="p-error">{{ productForm.errors.body }}</small>
                        </div>
                    </div>

                    <!-- Right Column - Pricing, Inventory, etc. -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Pricing -->
                        <div class="surface-card border-round-xl p-4 surface-border border-1">
                            <h4 class="text-lg font-medium mb-4 text-surface-800">Pricing</h4>
                            
                            <div class="field mb-3">
                                <label for="price" class="text-surface-700 mb-2 block font-medium">
                                    Price *
                                </label>
                                <InputNumber id="price" v-model="productForm.price"
                                             mode="currency" currency="USD" :min="0"
                                             class="w-full"
                                             :class="{ 'p-invalid': productForm.errors.price }"
                                             :disabled="viewMode" />
                                <small v-if="productForm.errors.price" class="p-error">{{ productForm.errors.price }}</small>
                            </div>

                            <div class="field mb-3">
                                <label for="sale_price" class="text-surface-700 mb-2 block font-medium">
                                    Sale Price
                                </label>
                                <InputNumber id="sale_price" v-model="productForm.sale_price"
                                             mode="currency" currency="USD" :min="0"
                                             class="w-full"
                                             :class="{ 'p-invalid': productForm.errors.sale_price }"
                                             :disabled="viewMode" />
                                <small v-if="productForm.errors.sale_price" class="p-error">{{ productForm.errors.sale_price }}</small>
                            </div>

                            <div class="field mb-3">
                                <label for="cost_price" class="text-surface-700 mb-2 block font-medium">
                                    Cost Price
                                </label>
                                <InputNumber id="cost_price" v-model="productForm.cost_price"
                                             mode="currency" currency="USD" :min="0"
                                             class="w-full"
                                             :class="{ 'p-invalid': productForm.errors.cost_price }"
                                             :disabled="viewMode" />
                                <small v-if="productForm.errors.cost_price" class="p-error">{{ productForm.errors.cost_price }}</small>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="surface-card border-round-xl p-4 surface-border border-1">
                            <h4 class="text-lg font-medium mb-4 text-surface-800">Inventory</h4>
                            
                            <div class="field mb-3">
                                <label for="sku" class="text-surface-700 mb-2 block font-medium">
                                    SKU
                                </label>
                                <InputText id="sku" v-model="productForm.sku"
                                           placeholder="Stock Keeping Unit"
                                           class="w-full"
                                           :class="{ 'p-invalid': productForm.errors.sku }"
                                           :disabled="viewMode" />
                                <small v-if="productForm.errors.sku" class="p-error">{{ productForm.errors.sku }}</small>
                            </div>

                            <div class="field mb-3">
                                <div class="flex align-items-center">
                                    <Checkbox id="track_quantity" v-model="productForm.track_quantity" :binary="true" :disabled="viewMode" />
                                    <label for="track_quantity" class="ml-2 text-surface-700">Track quantity</label>
                                </div>
                            </div>

                            <div class="field mb-3" v-if="productForm.track_quantity">
                                <label for="quantity" class="text-surface-700 mb-2 block font-medium">
                                    Quantity
                                </label>
                                <InputNumber id="quantity" v-model="productForm.quantity"
                                             :min="0"
                                             class="w-full"
                                             :class="{ 'p-invalid': productForm.errors.quantity }"
                                             :disabled="viewMode" />
                                <small v-if="productForm.errors.quantity" class="p-error">{{ productForm.errors.quantity }}</small>
                            </div>
                        </div>

                        <!-- Category & Brand -->
                        <div class="surface-card border-round-xl p-4 surface-border border-1">
                            <h4 class="text-lg font-medium mb-4 text-surface-800">Classification</h4>
                            
                            <div class="field mb-3">
                                <label for="category_id" class="text-surface-700 mb-2 block font-medium">
                                    Category
                                </label>
                                <TreeSelect
                                    v-model="productForm.category_id"
                                    :options="categories"
                                    placeholder="Select a category"
                                    class="w-full"
                                    :class="{ 'p-invalid': productForm.errors.category_id }"
                                    :disabled="viewMode"
                                    showClear
                                />
                                <small v-if="productForm.errors.category_id" class="p-error">{{ productForm.errors.category_id }}</small>
                            </div>

                            <div class="field mb-3">
                                <label for="brand_id" class="text-surface-700 mb-2 block font-medium">
                                    Brand
                                </label>
                                <Select
                                    v-model="productForm.brand_id"
                                    :options="brands"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select a brand"
                                    class="w-full"
                                    :class="{ 'p-invalid': productForm.errors.brand_id }"
                                    :disabled="viewMode"
                                    :showClear="true"
                                />
                                <small v-if="productForm.errors.brand_id" class="p-error">{{ productForm.errors.brand_id }}</small>
                            </div>

                            <div class="field mb-3">
                                <label for="type" class="text-surface-700 mb-2 block font-medium">
                                    Product Type
                                </label>
                                <Select
                                    v-model="productForm.type"
                                    :options="productTypes"
                                    optionLabel="label"
                                    optionValue="value"
                                    class="w-full"
                                    :disabled="viewMode"
                                />
                            </div>
                        </div>

                        <!-- Visibility -->
                        <div class="surface-card border-round-xl p-4 surface-border border-1">
                            <h4 class="text-lg font-medium mb-4 text-surface-800">Visibility</h4>
                            
                            <div class="field mb-3">
                                <div class="flex align-items-center">
                                    <Checkbox id="is_visible" v-model="productForm.is_visible" :binary="true" :disabled="viewMode" />
                                    <label for="is_visible" class="ml-2 text-surface-700">Published / Visible</label>
                                </div>
                            </div>

                            <div class="field mb-3">
                                <div class="flex align-items-center">
                                    <Checkbox id="is_featured" v-model="productForm.is_featured" :binary="true" :disabled="viewMode" />
                                    <label for="is_featured" class="ml-2 text-surface-700">Featured Product</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images Section in Modal -->
                <div class="mt-6 border-top-1 surface-border pt-4">
                    <h4 class="text-lg font-medium mb-4 text-surface-800">Images</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Main Image -->
                        <div class="field">
                            <label class="text-surface-700 mb-2 block font-medium">Main Image</label>
                            <FileUpload v-if="!viewMode"
                                mode="basic" 
                                name="image" 
                                :customUpload="true"
                                accept="image/*" 
                                :maxFileSize="2000000"
                                @select="onImageSelect" 
                                chooseLabel="Upload Main Image" 
                            />
                            
                            <div v-if="imagePreview" class="mt-3">
                                <img :src="imagePreview" class="w-full border-round"
                                     style="height: 150px; object-fit: cover;" />
                                <Button v-if="!viewMode" label="Remove" icon="pi pi-times"
                                        class="p-button-text p-button-danger mt-2"
                                        @click="removeImage" />
                            </div>
                            <small v-if="productForm.errors.image" class="p-error">{{ productForm.errors.image }}</small>
                        </div>

                        <!-- Gallery Images -->
                        <div class="field">
                            <label class="text-surface-700 mb-2 block font-medium">Gallery Images</label>
                            <FileUpload v-if="!viewMode"
                                mode="basic" 
                                name="gallery_images" 
                                :customUpload="true"
                                :multiple="true"
                                accept="image/*" 
                                :maxFileSize="2000000"
                                @select="onGallerySelect" 
                                chooseLabel="Upload Gallery Images" 
                            />
                            
                            <!-- New uploads preview -->
                            <div v-if="galleryPreviews.length > 0" class="flex flex-wrap gap-2 mt-3">
                                <div v-for="(preview, index) in galleryPreviews" :key="index" class="relative">
                                    <img :src="preview" class="border-round"
                                         style="width: 80px; height: 80px; object-fit: cover;" />
                                    <Button v-if="!viewMode" icon="pi pi-times" class="p-button-sm p-button-danger absolute"
                                            style="top: -8px; right: -8px;"
                                            @click="removeGalleryImage(index)" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Images -->
                    <div v-if="existingImages.length > 0" class="mt-4">
                        <label class="text-surface-700 mb-2 block font-medium">Existing Images</label>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="image in existingImages" :key="image.id" class="relative">
                                <img :src="`/storage/${image.path}`" class="border-round"
                                     style="width: 80px; height: 80px; object-fit: cover;" />
                                <div v-if="image.is_main" class="absolute top-0 left-0">
                                    <Tag value="Main" severity="success" size="small" />
                                </div>
                                <Button v-if="!viewMode" icon="pi pi-trash" class="p-button-sm p-button-danger absolute"
                                        style="top: -8px; right: -8px;"
                                        @click="removeExistingImage(image.id)" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="surface-card border-round-xl p-4 surface-border border-1">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-surface-800 m-0">Variations</h4>
                            <Tag :value="productForm.type === 'variable' ? 'Active' : 'None'"
                                 :severity="productForm.type === 'variable' ? 'success' : 'secondary'" />
                        </div>
                        <ProductVariations
                            :key="variationsKey"
                            :initial-data="productForm.attributes.variations || { hasVariations: false, options: {}, selectedTypes: [] }"
                            @update="handleVariationUpdate"
                        />
                    </div>

                    <div class="surface-card border-round-xl p-4 surface-border border-1">
                        <h4 class="text-lg font-medium mb-4 text-surface-800">Tags & SEO</h4>
                        <div class="field mb-4">
                            <label class="text-surface-700 mb-2 block font-medium">Tags</label>
                            <div class="flex gap-2">
                                <InputText v-model="tagInput" placeholder="Add tag..." class="w-full"
                                           :disabled="viewMode" @keydown="handleTagKeydown" />
                                <Button icon="pi pi-plus" severity="secondary" outlined :disabled="viewMode" @click="addTag" />
                            </div>
                            <div v-if="productForm.tags.length > 0" class="flex flex-wrap gap-2 mt-3">
                                <Chip
                                    v-for="(tag, index) in productForm.tags"
                                    :key="`${tag}-${index}`"
                                    :label="tag"
                                    :removable="!viewMode"
                                    @remove="removeTag(index)"
                                />
                            </div>
                        </div>
                        <Divider />
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">SEO Title</label>
                            <InputText v-model="productForm.seo_title" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">SEO Description</label>
                            <Textarea v-model="productForm.seo_description" rows="4" class="w-full" :disabled="viewMode" />
                        </div>
                        <div v-if="!seoCollapsed" class="surface-100 border-round p-3">
                            <div class="font-medium text-blue-700 mb-1">{{ productForm.seo_title || productForm.name || 'Page title' }}</div>
                            <div class="text-green-700 text-sm mb-1">yourstore.com/products/{{ productForm.slug || 'product-handle' }}</div>
                            <div class="text-600 text-sm">{{ productForm.seo_description || 'Add a description to see preview' }}</div>
                        </div>
                        <Button
                            class="mt-2"
                            text
                            severity="secondary"
                            :label="seoCollapsed ? 'Show SEO preview' : 'Hide SEO preview'"
                            @click="seoCollapsed = !seoCollapsed"
                        />
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="surface-card border-round-xl p-4 surface-border border-1">
                        <h4 class="text-lg font-medium mb-4 text-surface-800">Shipping</h4>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">Weight</label>
                            <InputNumber v-model="productForm.weight" :min="0" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">Length</label>
                            <InputNumber v-model="productForm.length" :min="0" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">Width</label>
                            <InputNumber v-model="productForm.width" :min="0" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field">
                            <label class="text-surface-700 mb-2 block font-medium">Height</label>
                            <InputNumber v-model="productForm.height" :min="0" class="w-full" :disabled="viewMode" />
                        </div>
                    </div>

                    <div class="surface-card border-round-xl p-4 surface-border border-1">
                        <h4 class="text-lg font-medium mb-4 text-surface-800">Extra Details</h4>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">Low Stock Threshold</label>
                            <InputNumber v-model="productForm.low_stock_threshold" :min="0" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">Barcode</label>
                            <InputText v-model="productForm.barcode" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field">
                            <label class="text-surface-700 mb-2 block font-medium">Warranty</label>
                            <InputText v-model="productForm.warranty" class="w-full" :disabled="viewMode" />
                        </div>
                    </div>

                    <div class="surface-card border-round-xl p-4 surface-border border-1">
                        <h4 class="text-lg font-medium mb-4 text-surface-800">Publishing</h4>
                        <div class="field mb-3">
                            <label class="text-surface-700 mb-2 block font-medium">Video URL</label>
                            <InputText v-model="productForm.video_url" class="w-full" :disabled="viewMode" />
                        </div>
                        <div class="field">
                            <label class="text-surface-700 mb-2 block font-medium">Published At</label>
                            <InputText v-model="productForm.published_at" type="datetime-local" class="w-full" :disabled="viewMode" />
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <template #footer>
                    <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="closeProductDialog" />
                    <Button v-if="!viewMode" label="Save" icon="pi pi-check" class="p-button" @click="saveProduct" :loading="productForm.processing" />
                </template>
            </Dialog>

            <!-- Delete Confirmation Dialog -->
            <Dialog v-model:visible="deleteProductDialog" :modal="true" header="Confirm Delete" :style="{ width: '400px' }">
                <div class="flex align-items-center gap-4">
                    <i class="pi pi-exclamation-triangle text-3xl text-red-500" />
                    <span>Are you sure you want to delete this product? This action cannot be undone.</span>
                </div>
                <template #footer>
                    <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="deleteProductDialog = false" />
                    <Button label="Delete" icon="pi pi-trash" class="p-button p-button-danger" @click="deleteProduct" :loading="productForm.processing" />
                </template>
            </Dialog>

            <!-- Bulk Delete Confirmation Dialog -->
            <Dialog v-model:visible="deleteProductsDialog" :modal="true" header="Confirm Bulk Delete" :style="{ width: '400px' }">
                <div class="flex align-items-center gap-4">
                    <i class="pi pi-exclamation-triangle text-3xl text-red-500" />
                    <span>Are you sure you want to delete {{ selectedProducts.length }} selected product(s)? This action cannot be undone.</span>
                </div>
                <template #footer>
                    <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="deleteProductsDialog = false" />
                    <Button label="Delete All" icon="pi pi-trash" class="p-button p-button-danger" @click="deleteSelectedProducts" />
                </template>
            </Dialog>
        </div>

        <Toast />
    </AppLayout>
</template>

<style scoped>
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

:deep(.p-dialog .p-dialog-content) {
    max-height: 70vh;
    overflow-y: auto;
}
</style>
