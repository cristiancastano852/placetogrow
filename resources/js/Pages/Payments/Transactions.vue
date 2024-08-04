<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import {SDataTable, SBadge} from '@placetopay/spartan-vue';

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
});

const cols = [
    { id: 'id', header: 'ID' },
    { id: 'reference', header: 'Referencia' },
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'currency', header: 'Moneda' },
    { id: 'amount', header: 'Monto' },
];

const value = ref('payments');

</script>

<template>

    <Head title="Pago" />
    <AuthenticatedMainLayout v-model="value">
        <div class="m-8" v-if="props.payments.length > 0">
            <h1>Transacciones</h1>
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
                        </SDataTable>
        </div>
        <div v-else>
            <p>No hay transacciones</p>
        </div>
    </AuthenticatedMainLayout>
</template>
