<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';
import { SBadge, SButton } from '@placetopay/spartan-vue';
const colorByType = {
    Subscripciones: 'green',
    Facturas: 'red',
    Donaciones: 'yellow',
};
const goToMicrositesPayment = (microsite) => {
    if (microsite.site_type == "Subscripciones") {
        router.visit(`/microsites/${microsite.id}/plans/show`);
    }else{
        router.visit(`/microsites/${microsite.id}/invoice`);
    }
};

const props = defineProps({
    microsites: {
        type: Array,
        required: true,
    },
});

</script>

<template>

    <Head title="Micrositios" />
    <GuestMicrositesLayout>
        <div class="container mx-auto py-8">
            <h1 class="text-2xl font-bold mb-4">Sitios</h1>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">Selecciona tu sitio para hacer tu pago</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="microsite in props.microsites" :key="microsite.id"
                    class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <img :src="microsite.logo" alt="Logo de Micrositio"
                        class="w-24 h-24 object-cover mx-auto mb-4 rounded-full shadow-lg" />
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ microsite.name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">Categoría: {{ microsite.category.name }}</p>
                    <div class="flex justify-between items-center">
                        <SBadge class="capitalize" :color="colorByType[microsite.site_type]">{{ microsite.site_type }}
                        </SBadge>

                        <SButton @click="goToMicrositesPayment(microsite)" variant="outline">
                            Ir al sitio
                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                            </svg>
                        </SButton>
                    </div>
                </div>
            </div>
        </div>
    </GuestMicrositesLayout>
</template>
