<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import { SDataTable, SBadge, SSelect, SLabel, SButton } from '@placetopay/spartan-vue';
import { format } from 'date-fns';

const colorByStatus = {
    APPROVED: 'green',
    REJECTED: 'red',
    PENDING: 'yellow',
};

const colorByType = {
    SUBCRIPTION: 'green',
    INVOICE: 'blue',
    DONATION: 'yellow',
};

const props = defineProps({
    payments: {
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
    { id: 'type', header: 'Pago de' },
    { id: 'reference', header: 'Referencia' },
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'amount', header: 'Monto total' },
    { id: 'microsite.name', header: 'Sitio de pago' },
    { id: 'updated_at', header: 'Fecha de pago' },
];

const value = ref('payments');

const selectedMicrosite = ref(0);

const searchByMicrosite = () => {
  if (selectedMicrosite.value === 0) {
    router.get(route('payments.transactions'));
  } else {
    router.get(route('payments.transactionsByMicrosite', { microsite: selectedMicrosite.value }));
  }
};

</script>

<template>

    <Head title="Pago" />
    <AuthenticatedMainLayout v-model="value">
        <div class="m-8">
            <div v-if="!is('Guests')" >
                <SLabel>Filtra por sitio de pago</SLabel>
                <div class="flex mb-8">
                    <SSelect v-model="selectedMicrosite" rounded="left">
                        <option value="0">Todos</option>
                        <option v-for="microsite in props.microsites" :value="microsite.id">{{ microsite.name }}</option>
                    </SSelect>
                    <SButton color="primary" rounded="right" @click="searchByMicrosite">Buscar</SButton>
                </div>
            </div>
            <h1>Transacciones</h1>
            <div  v-if="props.payments.length > 0">
                <SDataTable :cols="cols" :data="props.payments">
                    <template #col[description]="{ value }">
                        <div class="flex items center">
                            <span class="ml-2">{{ value }}</span>
                        </div>
                    </template>
                    <template #col[type]="{ record }">
                        <div class="flex items-center">
                            <SBadge v-if="record.subscription_id" class="capitalize" :color="colorByType['SUBCRIPTION']" dot>Subcripción</SBadge>
                            <SBadge v-else-if="record.invoice_id" class="capitalize" :color="colorByType['INVOICE']" dot>Factura</SBadge>
                            <SBadge v-else class="capitalize" :color="colorByType['DONATION']" dot>Donación</SBadge>
                        </div>
                    </template>

                    <template #col[status]="{ value }">
                        <SBadge class="capitalize" :color="colorByStatus[value]">{{ value }}</SBadge>
                    </template>
                    <template #col[amount]="{record,  value }">
                        <SBadge class="capitalize">{{record.currency}} {{ value }}</SBadge>
                    </template>
                    <template #col[updated_at]="{ value }">
                        <SBadge class="capitalize" :color="colorByStatus[value]">{{ formatDate(value) }}</SBadge>
                    </template>
                </SDataTable>
            </div>
            <div v-else>
                <p>No hay transacciones</p>
            </div>
        </div>
        

    </AuthenticatedMainLayout>
    
</template>