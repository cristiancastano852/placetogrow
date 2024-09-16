<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import { SDataTable, SBadge, SSelect, SLabel, SButton } from '@placetopay/spartan-vue';
import { format } from 'date-fns';

const colorByType = {
    PAID: 'green',
    REJECTED: 'red',
    PENDING: 'yellow',
};

const props = defineProps({
    invoices: {
        type: Object,
        required: true,
    },
    microsites : {
        type: Object,
        required: true,
    }
});



function formatDate(dateString: string): string {
    return format(new Date(dateString), 'dd/MM/yyyy HH:mm');
}

const cols = [
    { id: 'id', header: 'ID' },
    { id: 'reference', header: 'Referencia' },
    { id: 'document_number', header: 'Número de documento' },
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'currency', header: 'Moneda' },
    { id: 'amount', header: 'Monto' },
    { id: 'microsite.name', header: 'Sitio de pago' },
    { id: 'expiration_date', header: 'Expira' },
    { id: 'actions', header: 'Acciones' },
];

const value = ref('invoices');

const selectedMicrosite = ref(0);

const searchByMicrosite = () => {
  if (selectedMicrosite.value === 0) {
    router.get(route('payments.transactions'));
  } else {
    router.get(route('payments.transactionsByMicrosite', { microsite: selectedMicrosite.value }));
  }
};

const paymentInvoice = (invoice) => {
    const id = invoice.id;
    const microsite = invoice.microsite_id;
    router.post(route('invoice.invoicesPayment', { microsite: microsite}), {
        invoice_id: id,
    });
};


</script>

<template>

    <Head title="Pago" />
    <AuthenticatedMainLayout v-model="value">
        
        <div class="m-8">
            <h1 class="mb-8">Facturas</h1>
            <div  v-if="props.invoices.data.length > 0">
                <SDataTable :cols="cols" :data="props.invoices.data">
                    <template #col[description]="{ value }">
                        <div class="flex items center">
                            <span class="ml-2">{{ value }}</span>
                        </div>
                    </template>

                    <template #col[status]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                    </template>
                    <template #col[currency]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                    </template>
                    <template #col[expiration_date]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ formatDate(value) }}</SBadge>
                    </template>
                    <template #col[actions]="{ record }">

                        <button v-if="record.status!=='PAID'" @click="paymentInvoice(record)"
                                class="text-green-600 hover:text-green-900">Pagar</button>
                    </template>
                </SDataTable>
            </div>
            <div v-else>
                <p>No hay facturas</p>
            </div>
        </div>
        

    </AuthenticatedMainLayout>
    
</template>