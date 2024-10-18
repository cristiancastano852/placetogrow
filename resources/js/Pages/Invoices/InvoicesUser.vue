<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import { SDataTable, SBadge, SModalCard, SLabel, SButton } from '@placetopay/spartan-vue';
import { format } from 'date-fns';

const colorByType = {
    PAID: 'green',
    REJECTED: 'red',
    PENDING: 'yellow',
    EXPIRED: 'red',
    IN_PROCESS: 'primary',
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
    { id: 'reference', header: 'Referencia' },
    { id: 'document_number', header: 'Número de documento' },
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'microsite.name', header: 'Sitio de pago' },
    { id: 'expiration_date', header: 'Expira' },
    { id: 'amount', header: 'Monto' },
    { id: 'late_fee_amount', header: 'Recargo' },
    { id: 'total_amount', header: 'Total a pagar' },
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

const showLateFeeModal = ref(false);
const selectedInvoice = ref(null);

const paymentInvoice = (invoice) => {
    if (invoice.status === 'EXPIRED') {
        selectedInvoice.value = invoice;
        showLateFeeModal.value = true; 
    } else {
        const id = invoice.id;
        const microsite = invoice.microsite_id;
        router.post(route('invoice.invoicesPayment', { microsite: microsite}), {
            invoice_id: id,
        });
    }
};

const proceedToPayment = () => {
    const id = selectedInvoice.value.id;
    const microsite = selectedInvoice.value.microsite_id;
    router.post(route('invoice.invoicesPayment', { microsite: microsite }), {
        invoice_id: id,
    });
    showLateFeeModal.value = false;
};



</script>

<template>

    <Head title="Pago" />
    <AuthenticatedMainLayout v-model="value">
        <div class="m-8">
            <h1 class="mb-8">Facturas</h1>
            <div v-if="$page.props.flash.message" class="alert alert-warning">
                {{ $page.props.flash.message }}
            </div>
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

                        <button v-if="record.status!=='PAID' &&  record.status !== 'IN_PROCESS'" @click="paymentInvoice(record)"
                                class="text-green-600 hover:text-green-900">Pagar</button>
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
            <div v-else>
                <p>No hay facturas</p>
            </div>
            <SModalCard
                class="w-96"
                :open="showLateFeeModal"
                @close="() => showLateFeeModal = false"
            >
                <template #title>¡Factura Vencida!</template>
                <template #description>
                    <p>Esta factura está vencida, se aplicará un recargo de <strong>{{ selectedInvoice?.late_fee_amount }} {{ selectedInvoice?.currency }}</strong>.</p>
                    <p>¿Deseas continuar con el pago?</p>
                </template>
                <template #actions>
                    <SButton class="w-full" variant="primary" @click="proceedToPayment">
                        Continuar con el pago
                    </SButton>
                </template>
            </SModalCard>

        </div>
        

    </AuthenticatedMainLayout>
    
</template>