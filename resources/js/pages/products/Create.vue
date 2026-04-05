<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import slugify from 'slugify';

// Components
import ProductVariations from './ProductVariations.vue';
import InputText from 'primevue/inputtext';
import Badge from 'primevue/badge';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import SelectButton from 'primevue/selectbutton';
import TreeSelect from 'primevue/treeselect';
import FileUpload from 'primevue/fileupload';
import Editor from 'primevue/editor';
import Select from 'primevue/select';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import Checkbox from 'primevue/checkbox';

const toast = useToast();
const variationsRef = ref();

// Types
interface ProductForm {
    // Basic Info
    name: string;
    slug: string;
    description: string;
    body: string;

    // Pricing
    price: number;
    sale_price: number | null;
    cost_price: number | null;

    // Inventory
    sku: string;
    quantity: number;
    track_quantity: boolean;

    // Relations
    category_id: number | null;
    brand_id: number | null;

    // Status & Visibility
    is_visible: boolean;
    is_featured: boolean;
    type: 'simple' | 'variable' | 'digital' | 'booking';

    // Additional
    attributes: Record<string, any>; // JSON field

    // SEO
    seo_title: string;
    seo_description: string;

    // Image
    image: File | null; // Changed from main_image to match DB
}

// Inertia Form - Updated to match schema
const form = useForm<ProductForm>({
    // Basic Info
    name: '',
    slug: '',
    description: '',
    body: '',

    // Pricing
    price: 0,
    sale_price: null,
    cost_price: null,

    // Inventory
    sku: '',
    quantity: 0,
    track_quantity: true,

    // Relations
    category_id: null,
    brand_id: null,

    // Status & Visibility
    is_visible: true,
    is_featured: false,
    type: 'simple',

    // Additional
    attributes: {},

    // SEO
    seo_title: '',
    seo_description: '',

    // Image
    image: null
});

// Image Preview
const imagePreview = ref('');

// Gallery images will need a separate component/table
// For now, remove gallery functionality or create a separate media manager

// Data Sources
const categories = ref([
    {
        key: '1',
        label: 'Electronics',
        data: { id: 1 },
        children: [
            { key: '2', label: 'Phones & Accessories', data: { id: 2 } },
            { key: '3', label: 'Computers & Laptops', data: { id: 3 } },
            { key: '4', label: 'Audio & Headphones', data: { id: 4 } }
        ],
        key: '2',
        label: 'Saree',
        data: { id: 1 },
        children: [
            { key: '2', label: 'Phones & Accessories', data: { id: 2 } },
            { key: '3', label: 'Computers & Laptops', data: { id: 3 } },
            { key: '4', label: 'Audio & Headphones', data: { id: 4 } }
        ]

    }
]);

const brands = ref([
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

const visibilityOptions = ref([
    { label: 'Published', value: true },
    { label: 'Draft', value: false }
]);

// Methods
const generateSlug = () => {
    if (!form.name) return;
    form.slug = slugify(form.name, {
        lower: true,
        strict: true,
        trim: true
    });
};

const onImageSelect = (event: any) => {
    const file = event.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = '';
};

const handleVariationUpdate = (data: any) => {
    // Update attributes JSON when variations change
    form.attributes = {
        ...form.attributes,
        variations: data
    };
    form.type = data.hasVariations ? 'variable' : 'simple';
};

const submitForm = () => {
    // Get latest variation data
    if (variationsRef.value) {
        const variationData = variationsRef.value.getVariationData();
        form.attributes = {
            ...form.attributes,
            variations: variationData
        };
        form.type = variationData.hasVariations ? 'variable' : 'simple';
    }

    // Convert form data to FormData for file upload
    const formData = new FormData();

    // Append all form fields
    Object.keys(form.data()).forEach(key => {
        const value = form[key as keyof ProductForm];

        if (value !== null && value !== undefined) {
            if (key === 'image') {
                // Handle separately
                return;
            } else if (key === 'attributes') {
                // Convert attributes object to JSON string
                formData.append(key, JSON.stringify(value));
            } else {
                formData.append(key, String(value));
            }
        }
    });

    // Append image if exists
    if (form.image) {
        formData.append('image', form.image);
    }

    // Add _method for Laravel if needed
    formData.append('_method', 'POST');

    // Use Inertia post with FormData
    form.post(route('products.store'), {
        data: formData,
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product created successfully!',
                life: 5000
            });
            form.reset();
            imagePreview.value = '';
            localStorage.removeItem('product_draft');
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Please fix the validation errors',
                life: 5000
            });
        }
    });
};

const saveDraft = () => {
    const draftData = {
        ...form.data(),
        image_preview: imagePreview.value
    };
    localStorage.setItem('product_draft', JSON.stringify(draftData));

    toast.add({
        severity: 'info',
        summary: 'Draft Saved',
        detail: 'Product draft has been saved locally',
        life: 3000
    });
};

const loadDraft = () => {
    const draft = localStorage.getItem('product_draft');
    if (draft) {
        const draftData = JSON.parse(draft);

        Object.keys(draftData).forEach(key => {
            if (key !== 'image_preview' && key in form) {
                form[key as keyof ProductForm] = draftData[key];
            }
        });

        if (draftData.image_preview) {
            imagePreview.value = draftData.image_preview;
        }

        localStorage.removeItem('product_draft');

        toast.add({
            severity: 'info',
            summary: 'Draft Loaded',
            detail: 'Previous draft has been loaded',
            life: 3000
        });
    }
};

onMounted(() => {
    loadDraft();
});
</script>

<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-surface-900">Create New Product</h1>
                <p class="text-surface-600">Add a new product to your store</p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <div class="field mb-4">
                                <label for="name" class="text-surface-700 mb-2 block font-medium">
                                    Product Name *
                                </label>
                                <InputText id="name" v-model="form.name"
                                           placeholder="Enter product name"
                                           class="w-full"
                                           :class="{ 'p-invalid': form.errors.name }"
                                           @input="generateSlug" />
                                <small v-if="form.errors.name" class="p-error">{{ form.errors.name }}</small>
                            </div>

                            <div class="field mb-4">
                                <label for="slug" class="text-surface-700 mb-2 block font-medium">
                                    Slug
                                </label>
                                <InputText id="slug" v-model="form.slug"
                                           placeholder="auto-generated-from-name"
                                           class="w-full"
                                           :class="{ 'p-invalid': form.errors.slug }" />
                                <small v-if="form.errors.slug" class="p-error">{{ form.errors.slug }}</small>
                                <small class="text-surface-500 block mt-1">URL-friendly version of the name</small>
                            </div>

                            <div class="field mb-4">
                                <label for="category_id" class="text-surface-700 mb-2 block font-medium">
                                    Category
                                </label>
                                <TreeSelect
                                    v-model="form.category_id"
                                    :options="categories"
                                    placeholder="Select a category"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.category_id }"
                                />
                                <small v-if="form.errors.category_id" class="p-error">{{ form.errors.category_id }}</small>
                            </div>

                            <div class="field mb-4">
                                <label for="brand_id" class="text-surface-700 mb-2 block font-medium">
                                    Brand
                                </label>
                                <Select
                                    v-model="form.brand_id"
                                    :options="brands"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select a brand"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.brand_id }"
                                    :showClear="true"
                                />
                                <small v-if="form.errors.brand_id" class="p-error">{{ form.errors.brand_id }}</small>
                            </div>

                            <!-- Short Description -->
                            <div class="field mb-6">
                                <label for="description" class="text-surface-800 mb-3 block font-medium flex align-items-center">
                                    Short Description
                                    <Badge value="SEO Important" severity="warning" class="ml-2" size="small" />
                                </label>
                                <div class="relative">
                                    <Textarea id="description" v-model="form.description" rows="3"
                                              placeholder="Brief description that appears in product listings and search results..."
                                              class="w-full p-3"
                                              :class="{ 'p-invalid': form.errors.description }"
                                              :autoResize="true" />
                                    <div class="flex justify-content-between mt-2">
                                        <small v-if="form.errors.description" class="p-error">{{ form.errors.description }}</small>
                                        <small class="text-surface-500"
                                               :class="{ 'text-red-500': form.description?.length > 160 }">
                                            {{ form.description?.length || 0 }}/160 characters
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Long Description -->
                            <div class="field mb-6">
                                <label for="body" class="text-surface-800 mb-3 block font-medium">
                                    Long Description
                                </label>
                                <Editor
                                    v-model="form.body"
                                    editorStyle="height: 320px"
                                    :class="{ 'p-invalid': form.errors.body }"
                                />
                                <small v-if="form.errors.body" class="p-error">{{ form.errors.body }}</small>
                            </div>
                        </div>

                        <!-- Product Variations Component -->
                        <ProductVariations
                            ref="variationsRef"
                            :initial-data="form.attributes.variations || { hasVariations: false, options: {}, selectedTypes: [] }"
                            @update="handleVariationUpdate"
                        />

                        <!-- Pricing Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <div class="field mb-4">
                                <label for="price" class="text-surface-700 mb-2 block font-medium">
                                    Price *
                                </label>
                                <InputNumber id="price" v-model="form.price"
                                             mode="currency" currency="USD" :min="0"
                                             class="w-full"
                                             :class="{ 'p-invalid': form.errors.price }" />
                                <small v-if="form.errors.price" class="p-error">{{ form.errors.price }}</small>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="field mb-4">
                                    <label for="sale_price" class="text-surface-700 mb-2 block font-medium">
                                        Sale Price
                                    </label>
                                    <InputNumber id="sale_price" v-model="form.sale_price"
                                                 mode="currency" currency="USD" :min="0"
                                                 class="w-full"
                                                 :class="{ 'p-invalid': form.errors.sale_price }" />
                                    <small v-if="form.errors.sale_price" class="p-error">{{ form.errors.sale_price }}</small>
                                </div>

                                <div class="field mb-4">
                                    <label for="cost_price" class="text-surface-700 mb-2 block font-medium">
                                        Cost Price
                                    </label>
                                    <InputNumber id="cost_price" v-model="form.cost_price"
                                                 mode="currency" currency="USD" :min="0"
                                                 class="w-full"
                                                 :class="{ 'p-invalid': form.errors.cost_price }" />
                                    <small v-if="form.errors.cost_price" class="p-error">{{ form.errors.cost_price }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <h3 class="text-lg font-medium mb-4">Inventory</h3>

                            <div class="field mb-4">
                                <label for="sku" class="text-surface-700 mb-2 block font-medium">
                                    SKU
                                </label>
                                <InputText id="sku" v-model="form.sku"
                                           placeholder="Stock Keeping Unit"
                                           class="w-full"
                                           :class="{ 'p-invalid': form.errors.sku }" />
                                <small v-if="form.errors.sku" class="p-error">{{ form.errors.sku }}</small>
                            </div>

                            <div class="field mb-4">
                                <div class="flex align-items-center">
                                    <Checkbox id="track_quantity" v-model="form.track_quantity" :binary="true" />
                                    <label for="track_quantity" class="ml-2">Track quantity</label>
                                </div>
                            </div>

                            <div class="field mb-4" v-if="form.track_quantity">
                                <label for="quantity" class="text-surface-700 mb-2 block font-medium">
                                    Quantity
                                </label>
                                <InputNumber id="quantity" v-model="form.quantity"
                                             :min="0"
                                             class="w-full"
                                             :class="{ 'p-invalid': form.errors.quantity }" />
                                <small v-if="form.errors.quantity" class="p-error">{{ form.errors.quantity }}</small>
                            </div>
                        </div>

                        <!-- SEO Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <h3 class="text-lg font-medium mb-4">SEO</h3>

                            <div class="field mb-4">
                                <label for="seo_title" class="text-surface-700 mb-2 block font-medium">
                                    SEO Title
                                </label>
                                <InputText id="seo_title" v-model="form.seo_title"
                                           placeholder="Meta title"
                                           class="w-full"
                                           :class="{ 'p-invalid': form.errors.seo_title }" />
                                <small v-if="form.errors.seo_title" class="p-error">{{ form.errors.seo_title }}</small>
                            </div>

                            <div class="field mb-4">
                                <label for="seo_description" class="text-surface-700 mb-2 block font-medium">
                                    SEO Description
                                </label>
                                <Textarea id="seo_description" v-model="form.seo_description"
                                          rows="3"
                                          placeholder="Meta description"
                                          class="w-full"
                                          :class="{ 'p-invalid': form.errors.seo_description }" />
                                <small v-if="form.errors.seo_description" class="p-error">{{ form.errors.seo_description }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Type Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <label class="text-surface-700 mb-2 block font-medium">
                                Product Type
                            </label>
                            <Select v-model="form.type" :options="productTypes"
                                    optionLabel="label" optionValue="value"
                                    class="w-full" />
                        </div>

                        <!-- Status Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <div class="field mb-4">
                                <label class="text-surface-700 mb-2 block font-medium">
                                    Visibility
                                </label>
                                <SelectButton v-model="form.is_visible" :options="visibilityOptions"
                                              optionLabel="label" optionValue="value"
                                              class="w-full" />
                            </div>

                            <div class="field mb-4">
                                <div class="flex align-items-center">
                                    <Checkbox id="is_featured" v-model="form.is_featured" :binary="true" />
                                    <label for="is_featured" class="ml-2">Featured Product</label>
                                </div>
                            </div>
                        </div>

                        <!-- Image Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <div class="field mb-4">
                                <label class="text-surface-700 mb-2 block font-medium">
                                    Product Image
                                </label>
                                <FileUpload mode="basic" name="image" :customUpload="true"
                                            accept="image/*" :maxFileSize="2000000"
                                            @select="onImageSelect" chooseLabel="Upload Image" />

                                <div v-if="imagePreview" class="mt-3">
                                    <img :src="imagePreview" class="w-full border-round"
                                         style="height: 200px; object-fit: cover;" />
                                    <Button label="Remove" icon="pi pi-times"
                                            class="p-button-text p-button-danger w-full mt-2"
                                            @click="removeImage" />
                                </div>
                                <small v-if="form.errors.image" class="p-error">{{ form.errors.image }}</small>
                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <div class="flex flex-column gap-3">
                                <Button label="Create Product" icon="pi pi-check"
                                        class="p-button w-full" type="submit"
                                        :loading="form.processing" />

                                <Button label="Save Draft" icon="pi pi-save"
                                        class="p-button-outlined w-full"
                                        @click="saveDraft" :disabled="form.processing" />

                                <Button label="Cancel" icon="pi pi-times"
                                        class="p-button-text w-full"
                                        @click="$inertia.visit(route('products.index'))"
                                        :disabled="form.processing" />
                            </div>

                            <!-- Validation Errors -->
                            <div v-if="Object.keys(form.errors).length > 0" class="mt-4 p-3 bg-red-50 border-round">
                                <div class="flex align-items-center mb-2">
                                    <i class="pi pi-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="text-red-700 font-medium">Please fix the following errors:</span>
                                </div>
                                <ul class="list-none p-0 m-0 text-sm">
                                    <li v-for="(error, field) in form.errors" :key="field"
                                        class="text-red-600 mb-1">
                                        • {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <Toast />
    </AppLayout>
</template>
