<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import slugify from 'slugify';
import type { TreeNode } from 'primevue/treenode';
import products from '@/routes/products';

// Components
import ProductVariations from './ProductVariations.vue';
import InputText from 'primevue/inputtext';
import Badge from 'primevue/badge';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import TreeSelect from 'primevue/treeselect';
import FileUpload from 'primevue/fileupload';
import Editor from 'primevue/editor';
import Select from 'primevue/select';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import Checkbox from 'primevue/checkbox';
import Chip from 'primevue/chip';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';

const toast = useToast();
const variationsRef = ref();

// Get categories and brands from props (passed from controller)
const page = usePage();
const propsCategories = computed(() => (page.props.categories as TreeNode[]) || []);
const propsBrands = computed(() => (page.props.brands as Array<{ id: number | string; name: string }>) || []);

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
    attributes: Record<string, any>;
    tags: string[];

    // SEO
    seo_title: string;
    seo_description: string;

    // Image
    image: File | null;
}

type TreeSelectValue = number | string | Record<string, boolean> | null;

// Inertia Form
const form = useForm<ProductForm>({
    name: '',
    slug: '',
    description: '',
    body: '',
    price: 0,
    sale_price: null,
    cost_price: null,
    sku: '',
    quantity: 0,
    track_quantity: true,
    category_id: null,
    brand_id: null,
    is_visible: true,
    is_featured: false,
    type: 'simple',
    attributes: {},
    tags: [],
    seo_title: '',
    seo_description: '',
    image: null
});

// Image Preview
const imagePreview = ref('');

// Data Sources
const categories = ref<TreeNode[]>(propsCategories.value);
const brands = ref<Array<{ id: number | string; name: string }>>(propsBrands.value);

const productTypes = ref([
    { label: 'Physical', value: 'simple' as const, icon: 'pi pi-box', desc: 'Tangible products' },
    { label: 'Variable', value: 'variable' as const, icon: 'pi pi-list', desc: 'Multiple options' },
    { label: 'Digital', value: 'digital' as const, icon: 'pi pi-download', desc: 'Downloadable files' },
    { label: 'Booking', value: 'booking' as const, icon: 'pi pi-calendar', desc: 'Reservations' }
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
    form.attributes = {
        ...form.attributes,
        variations: data
    };
    form.type = data.hasVariations ? 'variable' : 'simple';
};

// Tags
const tagInput = ref('');
const tags = ref<string[]>([]);

const addTag = () => {
    const tag = tagInput.value.trim();
    if (tag && !tags.value.includes(tag)) {
        tags.value.push(tag);
        form.tags = [...tags.value];
    }
    tagInput.value = '';
};

const removeTag = (index: number) => {
    tags.value.splice(index, 1);
    form.tags = [...tags.value];
};

const handleTagKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' || event.key === ',') {
        event.preventDefault();
        addTag();
    }
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

const submitForm = () => {
    if (variationsRef.value) {
        const variationData = variationsRef.value.getVariationData();
        form.attributes = {
            ...form.attributes,
            variations: variationData
        };
        form.type = variationData.hasVariations ? 'variable' : 'simple';
    }

    form.category_id = normalizeTreeSelectValue(form.category_id as TreeSelectValue);
    form.brand_id = normalizeTreeSelectValue(form.brand_id as TreeSelectValue);

    form.transform((data: Record<string, any>) => {
        const fd = new FormData();
        for (const [key, value] of Object.entries(data)) {
            if (value !== null && value !== undefined) {
                if (key === 'attributes') {
                    fd.append(key, JSON.stringify(value));
                } else if (key === 'category_id' || key === 'brand_id') {
                    const normalizedValue = normalizeTreeSelectValue(value as TreeSelectValue);
                    if (normalizedValue !== null) {
                        fd.append(key, String(normalizedValue));
                    }
                } else if (value instanceof File) {
                    fd.append(key, value);
                } else if (Array.isArray(value)) {
                    fd.append(key, JSON.stringify(value));
                } else if (typeof value === 'string') {
                    fd.append(key, value.trim());
                } else {
                    fd.append(key, String(value));
                }
            }
        }
        return fd;
    }).post(products.store().url, {
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

const seoCollapsed = ref(true);

onMounted(() => {
    loadDraft();
});
</script>

<template>
    <AppLayout>
        <div class="product-create-page">
            <!-- Top Action Bar -->
            <div class="top-action-bar">
                <div class="action-bar-left">
                    <h1 class="page-title">Add product</h1>
                    <Tag v-if="form.type === 'variable'" severity="info" value="Variable" />
                    <Tag v-else-if="form.type === 'digital'" severity="success" value="Digital" />
                    <Tag v-else-if="form.type === 'booking'" severity="warning" value="Booking" />
                    <Tag v-else severity="secondary" value="Physical" />
                </div>
                <div class="action-bar-right">
                    <Button label="Save draft" icon="pi pi-save" severity="secondary" outlined @click="saveDraft" :disabled="form.processing" />
                    <Button label="Create product" icon="pi pi-check" @click="submitForm" :loading="form.processing" />
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Left Column - Main Content -->
                <div class="content-column">
                    <!-- Product Name -->
                    <div class="shopify-card">
                        <div class="card-section">
                            <InputText 
                                v-model="form.name" 
                                placeholder="Product name" 
                                class="product-name-input"
                                :class="{ 'p-invalid': form.errors.name }"
                                @input="generateSlug"
                            />
                            <small v-if="form.errors.name" class="p-error">{{ form.errors.name }}</small>
                        </div>
                        <div class="card-section">
                            <div class="slug-field">
                                <label class="field-label">URL</label>
                                <div class="slug-input-wrap">
                                    <span class="slug-prefix">/products/</span>
                                    <InputText 
                                        v-model="form.slug" 
                                        placeholder="product-handle" 
                                        class="slug-input"
                                        :class="{ 'p-invalid': form.errors.slug }"
                                    />
                                </div>
                            </div>
                            <small v-if="form.errors.slug" class="p-error">{{ form.errors.slug }}</small>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Media</h3>
                            <small class="text-muted">Add product images or videos</small>
                        </div>
                        <div class="card-section">
                            <div class="media-upload-area" v-if="!imagePreview">
                                <FileUpload 
                                    mode="basic" 
                                    name="image" 
                                    :customUpload="true"
                                    accept="image/*" 
                                    :maxFileSize="2000000"
                                    @select="onImageSelect" 
                                    chooseLabel=""
                                    class="full-upload-btn"
                                >
                                    <template #content>
                                        <div class="upload-placeholder">
                                            <i class="pi pi-image upload-icon"></i>
                                            <span class="upload-text">Add media</span>
                                            <span class="upload-hint">JPG, PNG, WebP up to 10MB</span>
                                        </div>
                                    </template>
                                </FileUpload>
                            </div>
                            <div v-else class="image-preview-container">
                                <img :src="imagePreview" class="preview-image" />
                                <Button 
                                    label="Remove" 
                                    icon="pi pi-times" 
                                    severity="danger" 
                                    text 
                                    size="small"
                                    @click="removeImage" 
                                />
                            </div>
                            <small v-if="form.errors.image" class="p-error">{{ form.errors.image }}</small>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Pricing</h3>
                        </div>
                        <div class="card-section">
                            <div class="pricing-grid">
                                <div class="price-field">
                                    <label class="field-label">Price</label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">TK</span>
                                        <InputNumber 
                                            v-model="form.price"
                                            mode="currency" 
                                            currency="BDT" 
                                            :min="0"
                                            useGrouping
                                            class="price-input"
                                            :class="{ 'p-invalid': form.errors.price }"
                                        />
                                    </div>
                                    <small v-if="form.errors.price" class="p-error">{{ form.errors.price }}</small>
                                </div>
                                <div class="price-field">
                                    <label class="field-label">Compare at price</label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">TK</span>
                                        <InputNumber 
                                            v-model="form.sale_price"
                                            mode="currency" 
                                            currency="BDT" 
                                            :min="0"
                                            useGrouping
                                            class="price-input"
                                            :class="{ 'p-invalid': form.errors.sale_price }"
                                        />
                                    </div>
                                    <small class="field-hint">Original price before sale</small>
                                </div>
                                <div class="price-field">
                                    <label class="field-label">Cost per item</label>
                                    <div class="input-with-prefix">
                                        <span class="input-prefix">TK</span>
                                        <InputNumber 
                                            v-model="form.cost_price"
                                            mode="currency" 
                                            currency="BDT" 
                                            :min="0"
                                            useGrouping
                                            class="price-input"
                                            :class="{ 'p-invalid': form.errors.cost_price }"
                                        />
                                    </div>
                                    <small class="field-hint">Customers won't see this</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Inventory</h3>
                        </div>
                        <div class="card-section">
                            <div class="inventory-grid">
                                <div class="field">
                                    <label class="field-label">SKU</label>
                                    <InputText 
                                        v-model="form.sku"
                                        placeholder="SKU" 
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.sku }"
                                    />
                                    <small v-if="form.errors.sku" class="p-error">{{ form.errors.sku }}</small>
                                </div>
                                <div class="field" v-if="form.track_quantity">
                                    <label class="field-label">Quantity</label>
                                    <InputNumber 
                                        v-model="form.quantity"
                                        :min="0"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.quantity }"
                                    />
                                    <small v-if="form.errors.quantity" class="p-error">{{ form.errors.quantity }}</small>
                                </div>
                            </div>
                            <div class="checkbox-field">
                                <Checkbox id="track_quantity" v-model="form.track_quantity" :binary="true" inputId="track_quantity" />
                                <label for="track_quantity" class="checkbox-label">Track quantity</label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Description</h3>
                            <small class="text-muted">Write a product description</small>
                        </div>
                        <div class="card-section">
                            <Editor
                                v-model="form.body"
                                editorStyle="height: 300px"
                                :class="{ 'p-invalid': form.errors.body }"
                            />
                            <small v-if="form.errors.body" class="p-error">{{ form.errors.body }}</small>
                        </div>
                    </div>

                    <!-- Product Variations (Compact Shopify Style) -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Variants</h3>
                            <Badge :value="form.type === 'variable' ? 'Active' : 'None'" 
                                   :severity="form.type === 'variable' ? 'success' : 'secondary'" 
                                   size="small" />
                        </div>
                        <div class="card-section">
                            <ProductVariations
                                ref="variationsRef"
                                :initial-data="form.attributes.variations || { hasVariations: false, options: {}, selectedTypes: [] }"
                                @update="handleVariationUpdate"
                            />
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Tags</h3>
                            <small class="text-muted">Organize your products</small>
                        </div>
                        <div class="card-section">
                            <div class="tags-input-area">
                                <InputText 
                                    v-model="tagInput" 
                                    placeholder="Add tag..." 
                                    @keydown="handleTagKeydown"
                                    class="tag-input"
                                />
                                <Button 
                                    icon="pi pi-plus" 
                                    @click="addTag" 
                                    size="small"
                                    severity="secondary"
                                    outlined
                                />
                            </div>
                            <div class="tags-container" v-if="tags.length > 0">
                                <Chip 
                                    v-for="(tag, index) in tags" 
                                    :key="index" 
                                    :label="tag" 
                                    :removable="true" 
                                    @remove="removeTag(index)"
                                    class="product-tag"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Category & Brand -->
                    <div class="shopify-card">
                        <div class="card-header">
                            <h3>Organization</h3>
                        </div>
                        <div class="card-section">
                            <div class="organization-grid">
                                <div class="field">
                                    <label class="field-label">Category</label>
                                    <TreeSelect
                                        v-model="form.category_id"
                                        :options="categories"
                                        placeholder="Select category"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.category_id }"
                                    />
                                    <small v-if="form.errors.category_id" class="p-error">{{ form.errors.category_id }}</small>
                                </div>
                                <div class="field">
                                    <label class="field-label">Brand</label>
                                    <Select
                                        v-model="form.brand_id"
                                        :options="brands"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select brand"
                                        class="w-full"
                                        :class="{ 'p-invalid': form.errors.brand_id }"
                                        :showClear="true"
                                    />
                                    <small v-if="form.errors.brand_id" class="p-error">{{ form.errors.brand_id }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="sidebar-column">
                    <!-- Status -->
                    <div class="shopify-card sidebar-card">
                        <div class="card-header">
                            <h3>Status</h3>
                        </div>
                        <div class="card-section">
                            <div class="status-selector">
                                <div 
                                    class="status-option" 
                                    :class="{ active: form.is_visible }"
                                    @click="form.is_visible = true"
                                >
                                    <div class="status-radio" :class="{ selected: form.is_visible }"></div>
                                    <div class="status-info">
                                        <span class="status-label">Active</span>
                                        <span class="status-desc">Visible in your store</span>
                                    </div>
                                </div>
                                <div 
                                    class="status-option" 
                                    :class="{ active: !form.is_visible }"
                                    @click="form.is_visible = false"
                                >
                                    <div class="status-radio" :class="{ selected: !form.is_visible }"></div>
                                    <div class="status-info">
                                        <span class="status-label">Draft</span>
                                        <span class="status-desc">Hidden from customers</span>
                                    </div>
                                </div>
                            </div>
                            <Divider />
                            <div class="checkbox-field">
                                <Checkbox id="is_featured" v-model="form.is_featured" :binary="true" inputId="is_featured" />
                                <label for="is_featured" class="checkbox-label">
                                    <span>Featured product</span>
                                    <small class="field-hint">Show on featured products section</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Product Type -->
                    <div class="shopify-card sidebar-card">
                        <div class="card-header">
                            <h3>Type</h3>
                        </div>
                        <div class="card-section">
                            <div class="type-selector">
                                <div 
                                    v-for="ptype in productTypes" 
                                    :key="ptype.value"
                                    class="type-option"
                                    :class="{ active: form.type === ptype.value }"
                                    @click="form.type = ptype.value"
                                >
                                    <i :class="ptype.icon"></i>
                                    <div class="type-info">
                                        <span class="type-label">{{ ptype.label }}</span>
                                        <span class="type-desc">{{ ptype.desc }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="shopify-card sidebar-card">
                        <div class="card-header collapsible" @click="seoCollapsed = !seoCollapsed">
                            <h3>Search engine listing</h3>
                            <i :class="seoCollapsed ? 'pi pi-chevron-down' : 'pi pi-chevron-up'"></i>
                        </div>
                        <div class="card-section" v-show="!seoCollapsed">
                            <div class="seo-preview">
                                <div class="seo-preview-title">{{ form.seo_title || form.name || 'Page title' }}</div>
                                <div class="seo-preview-url">yourstore.com/products/{{ form.slug || 'product-handle' }}</div>
                                <div class="seo-preview-desc">{{ form.seo_description || 'Add a description to see preview' }}</div>
                            </div>
                            <Divider />
                            <div class="field">
                                <label class="field-label">Page title</label>
                                <InputText 
                                    v-model="form.seo_title"
                                    placeholder="Auto-generated from product name"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.seo_title }"
                                />
                                <small class="field-hint">{{ form.seo_title?.length || 0 }}/60 characters</small>
                            </div>
                            <div class="field">
                                <label class="field-label">Description</label>
                                <Textarea 
                                    v-model="form.seo_description"
                                    rows="3"
                                    placeholder="Add a description"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors.seo_description }"
                                />
                                <small class="field-hint">{{ form.seo_description?.length || 0 }}/160 characters</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Errors Toast -->
            <div v-if="Object.keys(form.errors).length > 0" class="validation-toast">
                <i class="pi pi-exclamation-circle"></i>
                <span>Please fix {{ Object.keys(form.errors).length }} error(s)</span>
            </div>

            <Toast />
        </div>
    </AppLayout>
</template>

<style scoped>
/* Page Layout */
.product-create-page {
    min-height: 100vh;
    background: var(--surface-ground, #f1f5f9);
}

/* Top Action Bar */
.top-action-bar {
    background: var(--surface-card, #ffffff);
    border-bottom: 1px solid var(--surface-border, #e2e8f0);
    padding: 0.75rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
}

.action-bar-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-color, #1e293b);
    margin: 0;
}

.action-bar-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Main Content Layout */
.main-content {
    max-width: 1440px;
    margin: 0 auto;
    padding: 1.5rem;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}

.content-column {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.sidebar-column {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    position: sticky;
    top: 80px;
}

/* Shopify Card Style */
.shopify-card {
    background: var(--surface-card, #ffffff);
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.shopify-card .card-header {
    padding: 1rem 1.25rem 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--surface-border, #e2e8f0);
}

.shopify-card .card-header h3 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-color, #1e293b);
    margin: 0;
}

.shopify-card .card-header .text-muted {
    font-size: 0.75rem;
    color: var(--text-color-secondary, #64748b);
}

.shopify-card .card-header.collapsible {
    cursor: pointer;
    user-select: none;
}

.shopify-card .card-header.collapsible i {
    font-size: 0.75rem;
    color: var(--text-color-secondary, #64748b);
}

.shopify-card .card-section {
    padding: 1rem 1.25rem;
}

.shopify-card .card-section + .card-section {
    border-top: 1px solid var(--surface-border, #e2e8f0);
}

/* Product Name Input */
.product-name-input {
    font-size: 1.25rem !important;
    font-weight: 600 !important;
    border: none !important;
    padding: 1rem 1.25rem !important;
    border-radius: 0 !important;
}

.product-name-input:focus {
    outline: none !important;
    box-shadow: none !important;
}

/* Slug Field */
.slug-field {
    padding: 0 1.25rem 1rem;
}

.slug-input-wrap {
    display: flex;
    align-items: center;
    background: var(--surface-ground, #f1f5f9);
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 6px;
    overflow: hidden;
}

.slug-prefix {
    font-size: 0.8rem;
    color: var(--text-color-secondary, #64748b);
    padding: 0.5rem 0.75rem;
    background: var(--surface-ground, #f1f5f9);
    white-space: nowrap;
}

.slug-input {
    flex: 1;
    border: none !important;
    background: transparent !important;
    padding: 0.5rem 0.75rem !important;
}

/* Media Upload */
.media-upload-area {
    padding: 1rem 1.25rem;
}

.full-upload-btn {
    width: 100%;
}

.full-upload-btn :deep(.p-fileupload-choose) {
    width: 100%;
    padding: 2rem;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2.5rem;
    border: 2px dashed var(--surface-border, #e2e8f0);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.upload-placeholder:hover {
    border-color: var(--primary-color, #7c3aed);
    background: #ede9fe;
}

.upload-icon {
    font-size: 2.5rem;
    color: var(--text-color-secondary, #64748b);
    margin-bottom: 0.75rem;
}

.upload-text {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-color, #1e293b);
}

.upload-hint {
    font-size: 0.75rem;
    color: var(--text-color-secondary, #64748b);
    margin-top: 0.25rem;
}

.image-preview-container {
    padding: 1rem 1.25rem;
}

.preview-image {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 6px;
}

/* Pricing Grid */
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.price-field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.input-with-prefix {
    display: flex;
    align-items: center;
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 6px;
    overflow: hidden;
}

.input-prefix {
    font-size: 0.8rem;
    color: var(--text-color-secondary, #64748b);
    padding: 0.5rem 0.75rem;
    background: var(--surface-ground, #f1f5f9);
}

.price-input {
    flex: 1;
    border: none !important;
    background: transparent !important;
}

/* Inventory Grid */
.inventory-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

/* Field Styles */
.field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.field-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-color, #1e293b);
}

.field-hint {
    font-size: 0.7rem;
    color: var(--text-color-secondary, #64748b);
}

/* Checkbox */
.checkbox-field {
    display: flex;
    align-items: flex-start;
    gap: 0.625rem;
}

.checkbox-label {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
    cursor: pointer;
    font-size: 0.875rem;
    color: var(--text-color, #1e293b);
}

/* Tags */
.tags-input-area {
    display: flex;
    gap: 0.5rem;
    padding: 0 1.25rem 1rem;
}

.tag-input {
    flex: 1;
}

.tags-container {
    padding: 0 1.25rem 1rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.product-tag {
    font-size: 0.8rem;
}

/* Organization Grid */
.organization-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Status Selector */
.status-selector {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.status-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s;
}

.status-option:hover {
    border-color: var(--primary-color, #7c3aed);
}

.status-option.active {
    border-color: var(--primary-color, #7c3aed);
    background: #ede9fe;
}

.status-radio {
    width: 18px;
    height: 18px;
    border: 2px solid var(--surface-border, #e2e8f0);
    border-radius: 50%;
    position: relative;
    flex-shrink: 0;
}

.status-radio.selected {
    border-color: var(--primary-color, #7c3aed);
}

.status-radio.selected::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 8px;
    height: 8px;
    background: var(--primary-color, #7c3aed);
    border-radius: 50%;
}

.status-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.status-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-color, #1e293b);
}

.status-desc {
    font-size: 0.7rem;
    color: var(--text-color-secondary, #64748b);
}

/* Type Selector */
.type-selector {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.type-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0.75rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s;
}

.type-option:hover {
    background: var(--surface-ground, #f1f5f9);
}

.type-option.active {
    background: #ede9fe;
}

.type-option i {
    font-size: 1rem;
    color: var(--text-color-secondary, #64748b);
    width: 20px;
    text-align: center;
}

.type-option.active i {
    color: var(--primary-color, #7c3aed);
}

.type-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.type-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-color, #1e293b);
}

.type-desc {
    font-size: 0.68rem;
    color: var(--text-color-secondary, #64748b);
}

/* SEO Preview */
.seo-preview {
    padding: 1rem;
    background: var(--surface-ground, #f1f5f9);
    border-radius: 6px;
}

.seo-preview-title {
    font-size: 1rem;
    font-weight: 500;
    color: #1a0dab;
    margin-bottom: 0.25rem;
    text-decoration: underline;
}

.seo-preview-url {
    font-size: 0.75rem;
    color: #006621;
    margin-bottom: 0.25rem;
}

.seo-preview-desc {
    font-size: 0.8rem;
    color: var(--text-color-secondary, #64748b);
}

/* Validation Toast */
.validation-toast {
    position: fixed;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Sidebar Card */
.sidebar-card .card-section {
    padding: 1rem;
}

/* Responsive */
@media (max-width: 1024px) {
    .main-content {
        grid-template-columns: 1fr;
    }

    .sidebar-column {
        position: static;
    }

    .pricing-grid {
        grid-template-columns: 1fr;
    }

    .inventory-grid,
    .organization-grid {
        grid-template-columns: 1fr;
    }
}

/* PrimeVue Overrides */
:deep(.p-inputtext) {
    font-size: 0.875rem;
}

:deep(.p-editor-container .p-editor-content) {
    border: none;
    border-top: 1px solid var(--surface-border);
}

:deep(.p-editor-container .p-editor-toolbar) {
    border: none;
    background: var(--surface-ground);
    border-radius: 8px 8px 0 0;
}

:deep(.p-tag) {
    font-size: 0.7rem;
    font-weight: 500;
}
</style>
