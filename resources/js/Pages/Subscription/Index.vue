<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import { SDataTable, SBadge, SSelect, SLabel, SButton } from '@placetopay/spartan-vue';
import { format } from 'date-fns';

const colorByType = {
    ACTIVE: 'green',
    INACTIVE: 'blue',
    SUSPENDED: 'yellow',
    CANCELED: 'red',
};

const props = defineProps({
    subscriptions: {
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
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'microsite.currency', header: 'Moneda' },
    { id: 'price', header: 'Precio' },
    { id: 'microsite.name', header: 'Sitio de pago' },
    { id: 'created_at', header: 'Fecha de pago' },
    { id: 'actions', header: 'Acciones' },
];

const value = ref('subscriptions');

const selectedMicrosite = ref(0);

const searchByMicrosite = () => {
  if (selectedMicrosite.value === 0) {
    router.get(route('payments.transactions'));
  } else {
    router.get(route('payments.transactionsByMicrosite', { microsite: selectedMicrosite.value }));
  }
};

const cancelSubscription = (id) => {
    console.log(",,,,,,,,,,,,,,,,,,,,,,,",id);
    router.visit(route('subscriptions.cancel', id));
}

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
            <div  v-if="props.subscriptions.length > 0">
                <SDataTable :cols="cols" :data="props.subscriptions">
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
                    <template #col[created_at]="{ value }">
                        <SBadge class="capitalize" :color="colorByType[value]">{{ formatDate(value) }}</SBadge>
                    </template>
                    <template #col[actions]="{ record }">
                        <button @click="cancelSubscription(record.id)"
                                        class="text-red-600 hover:text-red-900">Cancelar</button>
                    </template>
                    
                </SDataTable>
            </div>
            <div v-else>
                <p>No hay transacciones</p>
            </div>
        </div>
        

    </AuthenticatedMainLayout>
    
</template>