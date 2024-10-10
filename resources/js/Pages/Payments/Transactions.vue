<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import { SDataTable, SBadge, SSelect, SLabel, SButton } from '@placetopay/spartan-vue';
import { format } from 'date-fns';

const colorByType = {
    APPROVED: 'green',
    REJECTED: 'red',
    PENDING: 'yellow',
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

console.log(props.microsites);
const cols = [
    { id: 'id', header: 'ID' },
    { id: 'reference', header: 'Referencia' },
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'currency', header: 'Moneda' },
    { id: 'amount', header: 'Monto' },
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

                    <template #col[status]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                    </template>
                    <template #col[currency]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                    </template>
                    <template #col[updated_at]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ formatDate(value) }}</SBadge>
                    </template>
                </SDataTable>
            </div>
            <div v-else>
                <p>No hay transacciones</p>
            </div>
        </div>
        

    </AuthenticatedMainLayout>
    
</template>