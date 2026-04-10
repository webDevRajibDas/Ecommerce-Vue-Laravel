<template>
    <div class="variations-compact">
        <!-- Header with toggle -->
        <div class="variations-header" @click="isExpanded = !isExpanded">
            <div class="header-left">
                <div class="toggle-icon" :class="{ expanded: isExpanded }">
                    <i class="pi pi-chevron-right"></i>
                </div>
                <div class="header-info">
                    <span class="header-title">Variants</span>
                    <span class="header-meta" v-if="hasVariations">
                        {{ selectedTypes.length }} option{{ selectedTypes.length !== 1 ? 's' : '' }} · {{ totalCombinations }} variant{{ totalCombinations !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
            <Button 
                v-if="hasVariations"
                icon="pi pi-plus" 
                size="small" 
                severity="secondary" 
                text
                @click.stop="showAddOption = !showAddOption"
            />
        </div>

        <!-- Expanded Content -->
        <div class="variations-content" v-show="isExpanded">
            <!-- Add variation type -->
            <div class="add-type-section" v-if="showAddOption || availableTypes.length > 0">
                <div class="available-types">
                    <button
                        v-for="type in availableTypes"
                        :key="type.value"
                        class="type-add-btn"
                        @click="addVariationType(type.value)"
                    >
                        <i :class="type.icon"></i>
                        <span>+ {{ type.label }}</span>
                    </button>
                </div>
            </div>

            <!-- Variation options list -->
            <div class="options-list" v-if="selectedTypes.length > 0">
                <div 
                    v-for="type in selectedTypes" 
                    :key="type" 
                    class="option-group"
                >
                    <div class="option-group-header">
                        <span class="group-label">{{ getTypeLabel(type) }}</span>
                        <button class="remove-type-btn" @click="removeVariationType(type)">
                            <i class="pi pi-times"></i>
                        </button>
                    </div>
                    
                    <!-- Option tags -->
                    <div class="option-tags">
                        <div
                            v-for="(opt, idx) in options[type]"
                            :key="idx"
                            class="option-tag"
                        >
                            <span 
                                v-if="type === 'color'" 
                                class="color-swatch" 
                                :style="{ background: getColorValue(opt) }"
                            ></span>
                            <span class="tag-text">{{ opt }}</span>
                            <button class="tag-remove" @click="removeOption(type, idx)">
                                <i class="pi pi-times"></i>
                            </button>
                        </div>
                        <span class="empty-state" v-if="!options[type]?.length">
                            No {{ getTypeLabel(type).toLowerCase() }} options yet
                        </span>
                    </div>

                    <!-- Add option input -->
                    <div class="add-option-row">
                        <InputText 
                            v-model="newOptions[type]"
                            :placeholder="`Add ${getTypeLabel(type).toLowerCase()}...`"
                            size="small"
                            @keyup.enter="addOption(type)"
                            class="option-input"
                        />
                        <Button 
                            icon="pi pi-plus" 
                            size="small" 
                            severity="secondary" 
                            outlined
                            @click="addOption(type)"
                            :disabled="!newOptions[type]?.trim()"
                        />
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div class="empty-variants" v-if="selectedTypes.length === 0">
                <i class="pi pi-box"></i>
                <span>This product has no variants</span>
                <span class="empty-hint">Add options like Size, Color, etc. to create variants</span>
            </div>

            <!-- Summary bar -->
            <div class="summary-bar" v-if="hasVariations">
                <div class="summary-info">
                    <span class="summary-badge">{{ totalCombinations }} variants</span>
                    <span class="summary-text">
                        Based on {{ selectedTypes.length }} option{{ selectedTypes.length !== 1 ? 's' : '' }}
                    </span>
                </div>
                <Button 
                    label="Clear all" 
                    size="small" 
                    severity="danger" 
                    text 
                    @click="clearAllVariations"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const emit = defineEmits(['update', 'change']);

const props = defineProps({
    initialData: { 
        type: Object, 
        default: () => ({ hasVariations: false, options: {}, selectedTypes: [] }) 
    }
});

// Variation type definitions
const variationTypes = [
    { value: 'size',     label: 'Size',     icon: 'pi pi-arrows-alt',  placeholder: 'e.g., S, M, L, XL' },
    { value: 'color',    label: 'Color',    icon: 'pi pi-palette',     placeholder: 'e.g., Red, Blue, Black' },
    { value: 'material', label: 'Material', icon: 'pi pi-cube',        placeholder: 'e.g., Cotton, Silk, Wool' },
    { value: 'pattern',  label: 'Pattern',  icon: 'pi pi-th-large',    placeholder: 'e.g., Striped, Solid, Floral' },
    { value: 'style',    label: 'Style',    icon: 'pi pi-star',        placeholder: 'e.g., Casual, Formal, Sport' }
];

// State
const isExpanded = ref(false);
const showAddOption = ref(false);
const selectedTypes = ref<string[]>(props.initialData.selectedTypes || []);
const options = reactive<Record<string, string[]>>({
    size: props.initialData.options?.size || [],
    color: props.initialData.options?.color || [],
    material: props.initialData.options?.material || [],
    pattern: props.initialData.options?.pattern || [],
    style: props.initialData.options?.style || []
});
const newOptions = reactive<Record<string, string>>({});

// Computed
const availableTypes = computed(() => 
    variationTypes.filter(t => !selectedTypes.value.includes(t.value))
);

const hasVariations = computed(() => selectedTypes.value.length > 0);

const totalOptions = computed(() =>
    selectedTypes.value.reduce((sum, t) => sum + (options[t]?.length || 0), 0)
);

const totalCombinations = computed(() => {
    if (!hasVariations.value) return 0;
    return selectedTypes.value.reduce((product, t) => {
        const count = options[t]?.length || 0;
        return count > 0 ? product * count : product;
    }, 1);
});

// Methods
const getTypeLabel = (type: string) =>
    variationTypes.find(t => t.value === type)?.label ?? type;

const getColorValue = (name: string): string => {
    const colorMap: Record<string, string> = {
        red: '#ef4444', blue: '#3b82f6', green: '#10b981', yellow: '#f59e0b',
        black: '#111827', white: '#f3f4f6', gray: '#6b7280', grey: '#6b7280',
        pink: '#ec4899', purple: '#8b5cf6', orange: '#f97316', brown: '#92400e',
        navy: '#1e3a8a', teal: '#0d9488', cyan: '#06b6d4', indigo: '#4f46e5',
        gold: '#fbbf24', silver: '#d1d5db', beige: '#f5f5dc', cream: '#fffdd0',
        khaki: '#c3b091', olive: '#808000', burgundy: '#800020', coral: '#ff7f50',
        mint: '#98ff98', lavender: '#e6e6fa', peach: '#ffdab9', rose: '#ff007f'
    };
    return colorMap[name.toLowerCase()] || '#e5e7eb';
};

const addVariationType = (type: string) => {
    if (!selectedTypes.value.includes(type)) {
        selectedTypes.value.push(type);
        if (!options[type]) options[type] = [];
        showAddOption.value = false;
        emitUpdate();
    }
};

const removeVariationType = (type: string) => {
    const index = selectedTypes.value.indexOf(type);
    if (index > -1) {
        selectedTypes.value.splice(index, 1);
        options[type] = [];
        emitUpdate();
    }
};

const addOption = (type: string) => {
    const value = newOptions[type]?.trim();
    if (value && !options[type]?.includes(value)) {
        if (!options[type]) options[type] = [];
        options[type].push(value);
        newOptions[type] = '';
        emitUpdate();
    }
};

const removeOption = (type: string, index: number) => {
    options[type]?.splice(index, 1);
    emitUpdate();
};

const clearAllVariations = () => {
    selectedTypes.value = [];
    Object.keys(options).forEach(key => options[key] = []);
    emitUpdate();
};

const emitUpdate = () => {
    const data = {
        selectedTypes: selectedTypes.value,
        options: { ...options },
        hasVariations: hasVariations.value,
        totalOptions: totalOptions.value,
        totalCombinations: totalCombinations.value
    };
    emit('update', data);
    emit('change', data);
};

// Expose methods for parent component
defineExpose({
    getVariationData: () => ({
        selectedTypes: selectedTypes.value,
        options: { ...options },
        hasVariations: hasVariations.value,
        totalOptions: totalOptions.value,
        totalCombinations: totalCombinations.value
    }),
    clearVariations: clearAllVariations
});
</script>

<style scoped>
/* Main Container */
.variations-compact {
    background: var(--surface-card, #ffffff);
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 8px;
    overflow: hidden;
}

/* Header */
.variations-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background 0.15s;
    user-select: none;
}

.variations-header:hover {
    background: var(--surface-ground, #f1f5f9);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.toggle-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: transform 0.2s;
}

.toggle-icon i {
    font-size: 0.7rem;
    color: var(--text-color-secondary, #64748b);
    transition: transform 0.2s;
}

.toggle-icon.expanded i {
    transform: rotate(90deg);
}

.header-info {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.header-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-color, #1e293b);
}

.header-meta {
    font-size: 0.7rem;
    color: var(--text-color-secondary, #64748b);
}

/* Content */
.variations-content {
    border-top: 1px solid var(--surface-border, #e2e8f0);
    padding: 1rem;
}

/* Add Type Section */
.add-type-section {
    margin-bottom: 1rem;
}

.available-types {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.type-add-btn {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.75rem;
    border: 1px dashed var(--surface-border, #e2e8f0);
    border-radius: 6px;
    background: transparent;
    cursor: pointer;
    font-size: 0.8rem;
    color: var(--text-color-secondary, #64748b);
    transition: all 0.15s;
}

.type-add-btn:hover {
    border-color: var(--primary-color, #7c3aed);
    color: var(--primary-color, #7c3aed);
    background: #ede9fe;
}

.type-add-btn i {
    font-size: 0.7rem;
}

/* Options List */
.options-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1rem;
}

.option-group {
    background: var(--surface-ground, #f1f5f9);
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 8px;
    padding: 0.875rem;
}

.option-group-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.625rem;
}

.group-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-color, #1e293b);
    text-transform: capitalize;
}

.remove-type-btn {
    width: 24px;
    height: 24px;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-color-secondary, #64748b);
    transition: all 0.15s;
}

.remove-type-btn:hover {
    background: #fef2f2;
    color: #ef4444;
}

.remove-type-btn i {
    font-size: 0.7rem;
}

/* Option Tags */
.option-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    min-height: 28px;
}

.option-tag {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.5rem 0.25rem 0.375rem;
    background: var(--surface-card, #ffffff);
    border: 1px solid var(--surface-border, #e2e8f0);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--text-color, #1e293b);
}

.color-swatch {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
}

.tag-text {
    text-transform: capitalize;
}

.tag-remove {
    width: 18px;
    height: 18px;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-color-secondary, #64748b);
    transition: all 0.15s;
    padding: 0;
}

.tag-remove:hover {
    background: #fef2f2;
    color: #ef4444;
}

.tag-remove i {
    font-size: 0.55rem;
}

.empty-state {
    font-size: 0.75rem;
    color: var(--text-color-secondary, #64748b);
    font-style: italic;
}

/* Add Option Row */
.add-option-row {
    display: flex;
    gap: 0.5rem;
}

.option-input {
    flex: 1;
}

/* Empty Variants State */
.empty-variants {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    text-align: center;
    gap: 0.5rem;
}

.empty-variants i {
    font-size: 2rem;
    color: var(--text-color-secondary, #64748b);
    opacity: 0.5;
}

.empty-variants span {
    font-size: 0.875rem;
    color: var(--text-color-secondary, #64748b);
}

.empty-hint {
    font-size: 0.75rem !important;
    opacity: 0.7;
}

/* Summary Bar */
.summary-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.875rem;
    border-top: 1px solid var(--surface-border, #e2e8f0);
}

.summary-info {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.summary-badge {
    background: #ede9fe;
    color: #7c3aed;
    padding: 0.25rem 0.625rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.summary-text {
    font-size: 0.75rem;
    color: var(--text-color-secondary, #64748b);
}
</style>