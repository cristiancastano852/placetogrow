<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';
import {SDataTable} from '@placetopay/spartan-vue';

const colorByType = {
    Facturas: 'green',
    Donaciones: 'blue',
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
    { id: 'description', header: 'Description' },
    { id: 'status', header: 'Estado' },
    { id: 'amount', header: 'Monto' },
    { id: 'actions', header: 'Actions' }
];

console.log(props.payments);
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

                            <!-- <template #col[actions]="{ record }">
                                <div class="flex gap-4">
                                    <button @click="viewMicrosite(record.id)"
                                        class="text-neutral-600 hover:text-neutral-900">Show</button>
                                    <button @click="editMicrosite(record.id)"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button @click="deleteMicrosite(record.id)"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </div>
                            </template> -->
                        </SDataTable>
        </div>
        <div v-else>
            <p>No hay transacciones</p>
        </div>
    </AuthenticatedMainLayout>
</template>
