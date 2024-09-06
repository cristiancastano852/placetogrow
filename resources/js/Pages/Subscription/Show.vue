<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';
import { SBadge, SButton } from '@placetopay/spartan-vue';

const props = defineProps({
    subscription : {
        type: Object,
        required: true,
    },
    microsite: {
        type: Object,
        required: true,
    },
});


</script>

<template>
    <Head title="Micrositios" />
    <GuestMicrositesLayout>
        <div class="container mx-auto py-8">
            <h1 class="text-2xl font-bold mb-4">Tu suscripcion al micrositio {{ props.microsite.name }}</h1>
            <h1 class="text-2xl font-bold mb-4">Planes</h1>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">Tenemos noticias sobre tu subscripcion</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">Subscripcion para el plan: {{ subscription.name }}</p>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">Estado {{ subscription.status }}</p>
            </div>
           
        </div>
    </GuestMicrositesLayout>
</template>
