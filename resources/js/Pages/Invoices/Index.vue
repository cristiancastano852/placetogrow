<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';
import { SBadge, SButton, SInputBlock, SSelectBlock } from '@placetopay/spartan-vue';
const colorByType = {
    Subscripciones: 'green',
    Facturas: 'red',
    Donaciones: 'yellow',
};

const props = defineProps({
    microsite: {
        type: Array,
        required: true,
    },
});

const document_number = ref('');

const submitForm = () => {
    router.post(route('invoice.invoicesByDocument', { microsite: props.microsite.id}), {
        document_number: document_number.value,
    });
};

</script>

<template>

    <Head title="Micrositios" />
    <GuestMicrositesLayout>
        <div class="container mx-auto py-8 w-1/2">
                <div class="bg-gray-50 p-8 shadow-md">
                    <header class="mb-4">
                        <h1 class="text-base font-semibold text-gray-900">
                            Encuentra tus facturas
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Utilice el número de documento para encontrar sus facturas pendientes.
                        </p>
                    </header>

                    <form @submit.prevent="submitForm" class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
                        <section class="grid grid-cols-6 gap-6">
                            <div class="col-span-3">
                                <SInputBlock id="document_number" v-model="document_number" label="Número de documento" />
                            </div>
                        </section>
                        <hr class="my-8" />
                        <section class="flex justify-end gap-2">
                            <SButton variant="secondary" >Cancelar</SButton>
                            <SButton variant="primary" type="submit">Buscar</SButton>
                        </section>
                    </form>
                </div>
        </div>
    </GuestMicrositesLayout>
</template>
