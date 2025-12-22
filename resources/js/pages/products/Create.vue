<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, reactive } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import slugify from 'slugify';

// Components
import ProductVariations from './ProductVariations.vue';
import InputText from 'primevue/inputtext';
import Badge from 'primevue/badge';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import SelectButton from 'primevue/selectbutton';
import AutoComplete from 'primevue/autocomplete';
import TreeSelect from 'primevue/treeselect';
import FileUpload from 'primevue/fileupload';
import Calendar from 'primevue/calendar';
import Editor from 'primevue/editor';
import Chips from 'primevue/chips';
import Select from 'primevue/select';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const variationsRef = ref();

// Inertia Form
const form = useForm({
    // Basic Info
    name: '',
    slug: '',
    description: '',
    body: '',
    
    // Pricing
    price: 0,
    sale_price: null as number | null,
    cost_price: 0,
    compare_at_price: null as number | null,
    
    // Inventory
    sku: '',
    quantity: 0,
    track_quantity: true,
    low_stock_threshold: 5,
    stock_alert_email: '',
    barcode: '',
    weight: 0,
    length: 0,
    width: 0,
    height: 0,
    
    // Relations
    category_id: null as number | null,
    brand: '',
    
    // Status & Visibility
    is_visible: true,
    is_featured: false,
    type: 'simple',
    
    // Additional
    tags: [] as string[],
    attributes: [] as Array<{key: string, value: string}>,
    warranty: '',
    video_url: '',
    
    // SEO
    seo_title: '',
    seo_description: '',
    seo_keywords: [] as string[],
    
    // Timestamps
    published_at: new Date().toISOString().split('T')[0],
    
    // Images
    main_image: null as File | null,
    gallery_images: [] as File[],
    
    // Variations
    variations: {
        selectedTypes: [] as string[],
        options: {
            size: [] as string[],
            color: [] as string[],
            material: [] as string[],
            pattern: [] as string[]
        },
        hasVariations: false
    }
});

// Image Previews
const mainImagePreview = ref('');
const galleryPreviews = ref<Array<{preview: string, file: File}>>([]);

// Data Sources
const categories = ref([
    {
        key: 'electronics',
        label: 'Electronics',
        children: [
            { key: 'phones', label: 'Phones & Accessories' },
            { key: 'computers', label: 'Computers & Laptops' },
            { key: 'audio', label: 'Audio & Headphones' }
        ]
    }
    // ... more categories
]);

const brands = ref(['Apple', 'Samsung', 'Sony', 'Nike', 'Adidas']);

const productTypes = ref([
    { label: 'Simple', value: 'simple' },
    { label: 'Variable', value: 'variable' }
]);

const visibilityOptions = ref([
    { label: 'Published', value: true },
    { label: 'Draft', value: false }
]);

// Methods
const generateSlug = () => {
    if (!form.name) return
    form.slug = slugify(form.name, {
        lower: true,
        strict: true,
        trim: true
    })
}

const onMainImageSelect = (event: any) => {
    const file = event.files[0]
    if (file) {
        form.main_image = file
        mainImagePreview.value = URL.createObjectURL(file)
    }
}

const removeMainImage = () => {
    form.main_image = null
    mainImagePreview.value = ''
}

const onGallerySelect = (event: any) => {
    event.files.forEach((file: File) => {
        form.gallery_images.push(file)
        galleryPreviews.value.push({
            preview: URL.createObjectURL(file),
            file: file
        })
    })
}

const removeGalleryImage = (index: number) => {
    form.gallery_images.splice(index, 1)
    galleryPreviews.value.splice(index, 1)
}

const handleVariationUpdate = (data: any) => {
    form.variations = data
    form.type = data.hasVariations ? 'variable' : 'simple'
}

const addAttribute = () => {
    form.attributes.push({ key: '', value: '' })
}

const removeAttribute = (index: number) => {
    form.attributes.splice(index, 1)
}

const submitForm = () => {
    // Get latest variation data
    // if (variationsRef.value) {
    //     const variationData = variationsRef.value.getVariationData()
    //     form.variations = variationData
    //     form.type = variationData.hasVariations ? 'variable' : 'simple'
    // }
    
    // Convert form data to FormData for file upload
    const formData = new FormData()
    
    // Append all form fields
    Object.keys(form.data()).forEach(key => {
        const value = form[key as keyof typeof form]
        
        if (value !== null && value !== undefined) {
            if (key === 'main_image' || key === 'gallery_images') {
                // Files will be handled separately
            } else if (Array.isArray(value)) {
                formData.append(key, JSON.stringify(value))
            } else if (typeof value === 'object') {
                formData.append(key, JSON.stringify(value))
            } else {
                formData.append(key, String(value))
            }
        }
    })
    
    // Append main image
    if (form.main_image) {
        formData.append('main_image', form.main_image)
    }
    
    // Append gallery images
    form.gallery_images.forEach((image, index) => {
        formData.append(`gallery_images[${index}]`, image)
    })
    
    // Use Inertia post with FormData
    form.post(route('productStore'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Product created successfully!',
                life: 5000
            })
        },
        onError: (errors) => {
            console.error('Validation errors:', errors)
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Please fix the validation errors',
                life: 5000
            })
        }
    })
}

const saveDraft = () => {
    // Save to localStorage for draft
    const draftData = {
        ...form.data(),
        main_image_preview: mainImagePreview.value,
        gallery_previews: galleryPreviews.value.map(img => img.preview)
    }
    
    localStorage.setItem('product_draft', JSON.stringify(draftData))
    
    toast.add({
        severity: 'info',
        summary: 'Draft Saved',
        detail: 'Product draft has been saved locally',
        life: 3000
    })
}

const loadDraft = () => {
    const draft = localStorage.getItem('product_draft')
    if (draft) {
        const draftData = JSON.parse(draft)
        
        // Load form data
        Object.keys(draftData).forEach(key => {
            if (key !== 'main_image_preview' && key !== 'gallery_previews') {
                form[key as keyof typeof form] = draftData[key]
            }
        })
        
        // Load image previews
        if (draftData.main_image_preview) {
            mainImagePreview.value = draftData.main_image_preview
        }
        
        if (draftData.gallery_previews) {
            galleryPreviews.value = draftData.gallery_previews.map((preview: string) => ({
                preview: preview,
                file: null as unknown as File
            }))
        }
        
        localStorage.removeItem('product_draft')
        
        toast.add({
            severity: 'info',
            summary: 'Draft Loaded',
            detail: 'Previous draft has been loaded',
            life: 3000
        })
    }
}

// Load draft on mount
import { onMounted } from 'vue'
onMounted(() => {
    loadDraft()
})
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
                            <!-- ... form fields for name, slug, category, etc. ... -->
                            
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
                                <small class="text-surface-500 mt-2 block">
                                    <i class="pi pi-info-circle mr-1"></i>
                                    Keep it under 160 characters for best SEO results
                                </small>
                            </div>
                            
                            <!-- More fields... -->
                             
                        </div>

                        <!-- Product Variations Component -->
                        <ProductVariations 
                            ref="variationsRef"
                            :initial-data="form.variations"
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
                            <!-- More pricing fields... -->
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="lg:col-span-1 space-y-6">
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
                        </div>

                        <!-- Images Card -->
                        <div class="card surface-card border-round-xl p-5">
                            <div class="field mb-4">
                                <label class="text-surface-700 mb-2 block font-medium">
                                    Main Image *
                                </label>
                                <FileUpload mode="basic" name="main_image" :customUpload="true" 
                                    accept="image/*" :maxFileSize="2000000" 
                                    @select="onMainImageSelect" chooseLabel="Upload Main Image" />
                                
                                <div v-if="mainImagePreview" class="mt-3">
                                    <img :src="mainImagePreview" class="w-full border-round" 
                                        style="height: 200px; object-fit: cover;" />
                                    <Button label="Remove" icon="pi pi-times" 
                                        class="p-button-text p-button-danger w-full mt-2" 
                                        @click="removeMainImage" />
                                </div>
                                <small v-if="form.errors.main_image" class="p-error">{{ form.errors.main_image }}</small>
                            </div>

                            <div class="field">
                                <label class="text-surface-700 mb-2 block font-medium">
                                    Gallery Images
                                </label>
                                <FileUpload name="gallery" :multiple="true" :customUpload="true" 
                                    accept="image/*" :maxFileSize="2000000" 
                                    @select="onGallerySelect" chooseLabel="Add to Gallery" />
                                
                                <div v-if="galleryPreviews.length > 0" class="mt-3">
                                    <div class="grid">
                                        <div v-for="(image, index) in galleryPreviews" :key="index" 
                                            class="col-4">
                                            <div class="relative">
                                                <img :src="image.preview" class="w-full border-round" 
                                                    style="height: 100px; object-fit: cover;" />
                                                <Button icon="pi pi-times" 
                                                    class="absolute top-0 right-0 p-button-danger p-button-sm"
                                                    @click="removeGalleryImage(index)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
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