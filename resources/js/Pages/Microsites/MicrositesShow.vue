<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ArrowLeft1Icon } from '@placetopay/iconsax-vue/linear';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    microsite: {
        category: { name: string };
        slug: string;
        name: string;
        document_type: string;
        document_number: string;
        logo: string;
        currency: string;
        site_type: string;
        payment_expiration: number;
        created_at: string;
        updated_at: string;
    };
}>();

const goBack = () => {
    router.visit('/microsites');
}
</script>

<template>
    <Head title="Microsite Details" />
    <AuthenticatedMainLayout>
        <div class="flex flex-col items-center">
            <div class="container p-4 sm:p-6 lg:p-8 bg-white">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                <button @click="goBack">
                    <ArrowLeft1Icon class="relative h-6 w-6 text-gray-400 hover:text-gray-800 duration-200" />
                </button>
                    {{ props.microsite.name }}
                </h2>

                
                <div class="mb-4">
                    <label class="block text-gray-700">Slug</label>
                    <p class="text-gray-900">{{ props.microsite.slug }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Name</label>
                    <p class="text-gray-900">{{ props.microsite.name }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Tipo de Documento</label>
                    <p class="text-gray-900">{{ props.microsite.document_type }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Número de documento</label>
                    <p class="text-gray-900">{{ props.microsite.document_number }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Logo</label>
                    <img :src="props.microsite.logo" alt="Logo" class="w-32 h-auto">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Moneda</label>
                    <p class="text-gray-900">{{ props.microsite.currency }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Tipo de sitio</label>
                    <p class="text-gray-900">{{ props.microsite.site_type }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Tiempo de expiración</label>
                    <p class="text-gray-900">{{ props.microsite.payment_expiration }} minutos</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Fecha de creación:</label>
                    <p class="text-gray-900">{{ props.microsite.created_at }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Fecha de actualización:</label>
                    <p class="text-gray-900">{{ props.microsite.updated_at }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
