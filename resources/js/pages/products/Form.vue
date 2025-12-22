<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: null,
    description:null
    
})
</script>

<template>
    <form @submit.prevent="form.post('admin/products/store')">
        <input type="text" v-model="form.name">
        <div v-if="form.errors.name">{{ form.errors.name }}</div>


        <input type="text" v-model="form.description">
        <div v-if="form.errors.description">{{ form.errors.description }}</div>
    
        <button type="submit" :disabled="form.processing">Save</button>
    </form>
</template>