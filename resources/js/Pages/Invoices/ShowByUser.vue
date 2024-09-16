<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';
import { SBadge, SButton, SDataTable, SSelectBlock } from '@placetopay/spartan-vue';
import { DocumentUploadIcon, BackSquareIcon } from '@placetopay/iconsax-vue/linear';

import { format } from 'date-fns';


const props = defineProps({
    microsite: {
        type: Array,
        required: true,
    },
    invoices: {
        type: Array,
        required: true,
    },
    document_number: {
        type: String,
        required: false,
    },
});

const document_number = ref('');

const paymentInvoice = (id) => {
    router.post(route('invoice.invoicesPayment', { microsite: props.microsite.id}), {
        invoice_id: id,
    });
};

function formatDate(dateString: string): string {
    return format(new Date(dateString), 'dd/MM/yyyy');
}

const cols = [
    { id: 'reference', header: 'Referencia' },
    { id: 'status', header: 'Estado' },
    { id: 'document_type', header: 'Tipo de documento' },
    { id: 'document_number', header: 'Número de documento' },
    { id: 'name', header: 'Nombre' },
    { id: 'email', header: 'Email' },
    { id: 'currency', header: 'Moneda' },
    { id: 'amount', header: 'Valor' },
    { id: 'expiration_date', header: 'Fecha de expiración' },
    { id: 'created_at', header: 'Fecha de creación' },
    { id: 'actions', header: 'Acciones' },
];

const colorByType = {
    PAID: 'green',
    PENDING: 'blue',
    FAILED: 'yellow',
};

const returnPage = () => {
    router.visit(route('microsites.index'));
}



</script>

<template>

    <Head title="Micrositios" />
    <GuestMicrositesLayout>
        <div class="flex h-screen">
            <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
                <main class="h-full w-full">
                    <div class="flex w-full justify-center m-8">
                        <div class="w-full flex justify-between mx-8">
                            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                                Facturas de {{ props.microsite.name }}
                            </h1>
                            <div>
                                <SButton :leftIcon="BackSquareIcon" size="sm" rounded="full" variant="outline"
                                    @click="returnPage">Regresar</SButton>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full justify-center m-8">
                        <SDataTable :cols="cols" :data="props.invoices.data">
                            <template #col[status]="{ value }">
                                <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                            </template>
                            <template #col[expiration_date]="{ value }">
                                <SBadge class="capitalize" :color="colorByType[value]">{{ formatDate(value) }}</SBadge>
                            </template>
                            <template #col[created_at]="{ value }">
                                <SBadge class="capitalize" :color="colorByType[value]">{{ formatDate(value) }}</SBadge>
                            </template>
                            <template #col[actions]="{ record }">
                                <button @click="paymentInvoice(record.id)"
                                        class="text-green-600 hover:text-green-900">Pagar</button>
                            </template>
                        </SDataTable>
                    </div>
                </main>
            </div>
        </div>
    </GuestMicrositesLayout>
</template>
