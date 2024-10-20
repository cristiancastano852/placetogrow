<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { SDataTable, SBadge, SButton } from '@placetopay/spartan-vue';
import { DocumentUploadIcon, BackSquareIcon } from '@placetopay/iconsax-vue/linear';

import { format } from 'date-fns';
import { ref } from 'vue';

const value = ref('Sitios');
const form = useForm({
    file: null,
});

function formatDate(dateString: string): string {
    return format(new Date(dateString), 'dd/MM/yyyy');
}

const props = defineProps({
    invoices: {
        type: Array,
        required: true,
    },
    microsite: {
        type: Object,
        required: true,
    },
});

const cols = [
    { id: 'reference', header: 'Referencia' },
    { id: 'status', header: 'Estado' },
    { id: 'document_type', header: 'Tipo de documento' },
    { id: 'document_number', header: 'Número de documento' },
    { id: 'name', header: 'Nombre' },
    { id: 'email', header: 'Email' },
    { id: 'currency', header: 'Moneda' },
    { id: 'amount', header: 'Monto' },
    { id: 'late_fee_amount', header: 'Recargo' },
    { id: 'total_amount', header: 'Total a pagar' },
    { id: 'expiration_date', header: 'Fecha de expiración' },
    { id: 'created_at', header: 'Fecha de creación' },
];

const colorByType = {
    PAID: 'green',
    PENDING: 'blue',
    FAILED: 'yellow',
};


const importInvoices = (id) => {
    router.visit(route('import.create', id));
}

const returnPage = () => {
    router.visit(route('microsites.index'));
}



</script>

<template>

    <Head title="Facturas" />
    <AuthenticatedMainLayout v-model="value">
        <div class="flex h-screen">
            <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
                <main class="h-full w-full">
                    <div class="flex w-full justify-center m-8">
                        <div class="w-full flex justify-between mx-8">
                            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                                Facturas del micrositio: {{ props.microsite.name }}
                            </h1>
                            <div>
                                <SButton class="mr-4" :leftIcon="DocumentUploadIcon" size="sm" rounded="full"
                                    variant="outline" @click="importInvoices(props.microsite.id)">Importar</SButton>
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
                            <template #col[amount]="{ record, value }">
                                <span>{{ value }} {{ record.currency }}</span>
                            </template>
                            <template #col[late_fee_amount]="{ record, value }">
                                <span>{{ value }} {{ record.currency }}</span>
                            </template>
                            <template #col[total_amount]="{ record, value }">
                                <SBadge color="indigo"> {{ value }} {{ record.currency }} </SBadge>
                            </template>
                        </SDataTable>
                    </div>
                </main>
            </div>
        </div>
    </AuthenticatedMainLayout>

</template>