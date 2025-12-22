<template>
    <!-- Product Variations Card -->
    <div class="card surface-card border-round-xl surface-border border-1 p-5 shadow-sm">
        <div class="flex align-items-center justify-content-between mb-5">
            <div class="flex align-items-center">
                <div class="p-3 bg-purple-50 border-round-lg mr-3">
                    <i class="pi pi-box text-xl text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-surface-900 m-0 text-xl font-semibold">
                        Product Variations
                    </h3>
                    <p class="text-surface-500 m-0 text-sm">Add different product options</p>
                </div>
            </div>
            <Badge :value="hasVariations ? 'Variable Product' : 'Simple Product'" 
                   :severity="hasVariations ? 'success' : 'info'" />
        </div>

        <!-- Variation Type Selection -->
        <div class="field mb-6">
            <label class="text-surface-800 mb-3 block font-medium flex align-items-center">
                <i class="pi pi-sliders-h mr-2 text-purple-500"></i>
                Select Variation Types
                <Badge value="Optional" severity="info" class="ml-2" size="small" />
            </label>
            
            <div class="grid gap-3">
                <div v-for="type in variationTypes" :key="type.value" 
                     class="col-12 md:col-6 lg:col-3">
                    <div class="card cursor-pointer border-2 surface-border hover:border-primary transition-all duration-200"
                         :class="{ 'border-primary bg-primary-50': selectedTypes.includes(type.value) }"
                         @click="toggleVariationType(type.value)">
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                <i :class="[type.icon, 'text-2xl', { 'text-primary': selectedTypes.includes(type.value), 'text-surface-400': !selectedTypes.includes(type.value) }]"></i>
                            </div>
                            <div class="font-medium mb-1">{{ type.label }}</div>
                            <small class="text-surface-500 text-xs">{{ type.description }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variation Options -->
        <div v-if="selectedTypes.length > 0" class="mb-6">
            <h4 class="text-surface-800 mb-4 text-lg font-medium flex align-items-center">
                <i class="pi pi-list mr-2 text-purple-500"></i>
                Variation Options
            </h4>
            
            <div class="grid gap-4">
                <div v-for="type in selectedTypes" :key="type" 
                     class="col-12 md:col-6 lg:col-3">
                    <div class="p-4 surface-50 border-round-lg">
                        <div class="flex justify-content-between align-items-center mb-3">
                            <label class="text-surface-800 font-medium capitalize">
                                {{ getTypeLabel(type) }} Options
                            </label>
                            <Button icon="pi pi-trash" class="p-button-text p-button-danger p-button-sm"
                                    @click="removeVariationType(type)"/>
                        </div>
                        
                        <!-- Option Input -->
                        <div class="flex gap-2 mb-3">
                            <InputText v-model="newOptions[type]" 
                                       :placeholder="`Add ${getTypeLabel(type)}`" 
                                       class="flex-1"
                                       @keyup.enter="addOption(type)" />
                            <Button icon="pi pi-plus" class="p-button-outlined p-button-sm"
                                    @click="addOption(type)"
                                    :disabled="!newOptions[type]?.trim()" />
                        </div>
                        
                        <!-- Options List -->
                        <div v-if="options[type]?.length > 0" class="space-y-2">
                            <div v-for="(option, index) in options[type]" 
                                 :key="index" class="flex justify-content-between align-items-center p-2 surface-card border-round hover:surface-hover">
                                <div class="flex align-items-center">
                                    <!-- Color Swatch -->
                                    <div v-if="type === 'color'" 
                                         class="w-6 h-6 border-round mr-2 border-1 surface-border"
                                         :style="{ backgroundColor: getColorCode(option) }"></div>
                                    
                                    <!-- Size Badge -->
                                    <Badge v-else-if="type === 'size'" :value="option" 
                                           severity="info" class="mr-2" />
                                    
                                    <span class="capitalize">{{ option }}</span>
                                </div>
                                <div class="flex gap-1">
                                    <Button icon="pi pi-pencil" class="p-button-text p-button-sm"
                                            @click="editOption(type, index, option)" />
                                    <Button icon="pi pi-trash" class="p-button-text p-button-danger p-button-sm"
                                            @click="removeOption(type, index)" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Empty State -->
                        <div v-else class="text-center py-3 text-surface-500">
                            <i class="pi pi-inbox text-2xl mb-2"></i>
                            <p class="text-sm m-0">No options added yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variation Summary -->
        <div v-if="selectedTypes.length > 0" class="mb-6 p-4 surface-ground border-round">
            <div class="flex align-items-center mb-3">
                <i class="pi pi-info-circle text-primary mr-2"></i>
                <h5 class="m-0 font-medium">Variation Summary</h5>
            </div>
            
            <div class="grid">
                <div class="col-12 md:col-6">
                    <div class="text-surface-600 text-sm mb-2">Selected Variations</div>
                    <div class="flex flex-wrap gap-2">
                        <Tag v-for="type in selectedTypes" :key="type" 
                             :value="getTypeLabel(type)" 
                             icon="pi pi-check" severity="info" />
                    </div>
                </div>
                <div class="col-12 md:col-6">
                    <div class="text-surface-600 text-sm mb-2">Total Options</div>
                    <div class="text-2xl font-bold text-primary">{{ totalOptions }}</div>
                    <small class="text-surface-500">across {{ selectedTypes.length }} variation types</small>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-content-end gap-2 pt-4 border-top-1 surface-border">
            <Button label="Clear All" icon="pi pi-trash" 
                    class="p-button-outlined p-button-danger"
                    @click="clearAllVariations"
                    :disabled="selectedTypes.length === 0" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useToast } from 'primevue/usetoast'

// Components
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Tag from 'primevue/tag'
import Badge from 'primevue/badge'

const toast = useToast()
const emit = defineEmits(['update', 'change'])

// Props
const props = defineProps({
    initialData: {
        type: Object,
        default: () => ({})
    }
})

// Variation Types
const variationTypes = [
    { 
        value: 'size', 
        label: 'Size', 
        icon: 'pi pi-arrows-h', 
        description: 'S, M, L, XL' 
    },
    { 
        value: 'color', 
        label: 'Color', 
        icon: 'pi pi-palette', 
        description: 'Red, Blue, Green' 
    },
    { 
        value: 'material', 
        label: 'Material', 
        icon: 'pi pi-cube', 
        description: 'Cotton, Silk, Leather' 
    },
    { 
        value: 'pattern', 
        label: 'Pattern', 
        icon: 'pi pi-th-large', 
        description: 'Striped, Solid, Checkered' 
    }
]

// State
const selectedTypes = ref<string[]>(props.initialData.selectedTypes || [])
const options = reactive<Record<string, string[]>>({
    size: props.initialData.size || [],
    color: props.initialData.color || [],
    material: props.initialData.material || [],
    pattern: props.initialData.pattern || []
})
const newOptions = reactive<Record<string, string>>({})

// Computed
const hasVariations = computed(() => selectedTypes.value.length > 0)

const totalOptions = computed(() => {
    let count = 0
    selectedTypes.value.forEach(type => {
        count += options[type]?.length || 0
    })
    return count
})

// Methods
const getTypeLabel = (type: string): string => {
    const found = variationTypes.find(t => t.value === type)
    return found ? found.label : type
}

const toggleVariationType = (type: string) => {
    const index = selectedTypes.value.indexOf(type)
    if (index > -1) {
        selectedTypes.value.splice(index, 1)
    } else {
        selectedTypes.value.push(type)
    }
    emitDataUpdate()
}

const addOption = (type: string) => {
    const option = newOptions[type]?.trim()
    if (option && !options[type]?.includes(option)) {
        if (!options[type]) options[type] = []
        options[type].push(option)
        newOptions[type] = ''
        emitDataUpdate()
        
        toast.add({
            severity: 'success',
            summary: 'Option Added',
            detail: `Added "${option}" to ${getTypeLabel(type)} options`,
            life: 2000
        })
    }
}

const removeOption = (type: string, index: number) => {
    options[type].splice(index, 1)
    emitDataUpdate()
}

const editOption = async (type: string, index: number, currentValue: string) => {
    const newValue = prompt(`Edit ${getTypeLabel(type)} option:`, currentValue)
    if (newValue && newValue.trim() && newValue !== currentValue) {
        options[type][index] = newValue.trim()
        emitDataUpdate()
    }
}

const removeVariationType = (type: string) => {
    const index = selectedTypes.value.indexOf(type)
    if (index > -1) {
        selectedTypes.value.splice(index, 1)
        delete options[type]
        emitDataUpdate()
        
        toast.add({
            severity: 'info',
            summary: 'Variation Removed',
            detail: `Removed ${getTypeLabel(type)} variation`,
            life: 3000
        })
    }
}

const clearAllVariations = () => {
    if (confirm('Are you sure you want to clear all variations?')) {
        selectedTypes.value = []
        Object.keys(options).forEach(key => {
            options[key] = []
        })
        emitDataUpdate()
        
        toast.add({
            severity: 'info',
            summary: 'Cleared',
            detail: 'All variations cleared',
            life: 3000
        })
    }
}

const getColorCode = (colorName: string): string => {
    const colorMap: Record<string, string> = {
        'red': '#ef4444',
        'blue': '#3b82f6',
        'green': '#10b981',
        'yellow': '#f59e0b',
        'black': '#000000',
        'white': '#ffffff',
        'gray': '#6b7280',
        'pink': '#ec4899',
        'purple': '#8b5cf6',
        'orange': '#f97316',
        'brown': '#92400e',
        'navy': '#1e3a8a',
        'teal': '#0d9488',
        'maroon': '#991b1b',
        'olive': '#3f6212',
        'lime': '#65a30d',
        'cyan': '#06b6d4',
        'indigo': '#4f46e5',
        'violet': '#7c3aed'
    }
    return colorMap[colorName.toLowerCase()] || '#6b7280'
}

const emitDataUpdate = () => {
    const variationData = {
        selectedTypes: selectedTypes.value,
        options: { ...options },
        hasVariations: hasVariations.value,
        totalOptions: totalOptions.value
    }
    
    emit('update', variationData)
    emit('change', variationData)
}

// Watch for changes
watch([selectedTypes, options], emitDataUpdate, { deep: true })

// Expose data and methods
defineExpose({
    getVariationData: () => ({
        selectedTypes: selectedTypes.value,
        options: { ...options },
        hasVariations: hasVariations.value,
        totalOptions: totalOptions.value
    }),
    clearVariations: () => {
        selectedTypes.value = []
        Object.keys(options).forEach(key => {
            options[key] = []
        })
        emitDataUpdate()
    }
})
</script>

<style scoped>
.card {
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

:deep(.p-tag) {
    font-weight: 500;
}

:deep(.p-badge) {
    font-weight: 600;
}
</style>