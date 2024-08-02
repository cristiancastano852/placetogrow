<script setup>
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const value = ref('Dashboard');
</script>

<template>
    <Head title="Home" />
    <AuthenticatedMainLayout v-model="value">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                    <h4 class="text-2xl font-semibold mb-4">Bienvenido</h4>
                    <p class="text-lg">En las opciones de navegación puedes encontrar las opciones para gestionar todo lo que necesites.</p>
                </div>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
