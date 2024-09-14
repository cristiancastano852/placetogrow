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
        router.visit(`/microsite/pay/${microsite.slug}_${microsite.id}`);
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
            
        </div>
    </GuestMicrositesLayout>
</template>
