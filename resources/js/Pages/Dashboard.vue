<script setup>
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head, router } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue';
import { SButton, SInputDate, SSelect, SBreadcrumbsItem, SBreadcrumbs, SInputDateBlock } from '@placetopay/spartan-vue';
import Chart from 'chart.js/auto';

const props = defineProps({
    metrics: {
        type: Object,
        required: true
    },
    microsites: {
        type: Object,
        required: true
    }
});

const invoicesPending = ref(props.metrics.PENDING);
const invoicesPaid = ref(props.metrics.PAID);
const invoicesExpired = ref(props.metrics.EXPIRED);

const value = ref('Dashboard');

const startDate = ref(null);
const endDate = ref(null);
const selectedMicrosite = ref(null);

const chartPendingVsPaidRef = ref(null);
const chartPendingVsExpiredRef = ref(null);

onMounted(() => {
    const ctxPaid = chartPendingVsPaidRef.value.getContext('2d');
    new Chart(ctxPaid, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Pagadas'],
            datasets: [
                {
                    label: 'Facturas',
                    data: [invoicesPending.value, invoicesPaid.value],
                    backgroundColor: ['#f9c74f', '#90be6d'],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Pendientes vs. Pagadas',
                },
            },
        },
    });

    const ctxExpired = chartPendingVsExpiredRef.value.getContext('2d');
    new Chart(ctxExpired, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Vencidas'],
            datasets: [
                {
                    label: 'Facturas',
                    data: [invoicesPending.value, invoicesExpired.value],
                    backgroundColor: ['#f9c74f', '#f94144'],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Pendientes vs. Vencidas',
                },
            },
        },
    });
});

const applyFilters = () => {
    router.visit(route('dashboard', {
        startDate: startDate.value,
        endDate: endDate.value,
        micrositeId: selectedMicrosite.value,
    }));
};
</script>

<template>

    <Head title="Home" />
    <AuthenticatedMainLayout v-model="value">
        <div class="">
            <div class="w-full h-full  ">
                <div class="text-gray-900">
                    <div class="mb-4 flex flex-wrap items-center bg-gray-50 p-4 shadow-md rounded-lg">
    <div class="flex-1 px-2">
        <p class="text-sm text-gray-600">Micrositio</p>
        <SSelect class="w-full" v-model="selectedMicrosite">
            <option value="0" selected="True">Todos</option>
            <option v-for="microsite in props.microsites" :value="microsite.id">{{ microsite.name }}</option>
        </SSelect>
    </div>
    <div class="flex-1 px-2">
        <p class="text-sm text-gray-600">Fecha inicial</p>
        <SInputDate v-model="startDate" placeholder="Fecha de inicio" class="w-full" />
    </div>
    <div class="flex-1 px-2">
        <p class="text-sm text-gray-600">Fecha final</p>
        <SInputDate v-model="endDate" placeholder="Fecha final" class="w-full" />
    </div>
    <div class="flex-1 px-2">
        <SButton @click="applyFilters" class="w-full mt-6">Aplicar Filtros</SButton>
    </div>
</div>

                    
                    <h1 class="text-base font-semibold text-gray-900 mx-12">
                        Bienvenido,
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 mx-12">
                        Visualización de métricas de facturación.
                    </p>
                    <div class="flex justify-between mx-8">
                        <div class="flex justify-center items-center mx-4 bg-white p-4 rounded-lg shadow-lg"
                            style="width: 100%;">
                            <canvas ref="chartPendingVsPaidRef"
                                style="max-width: 100%; max-height: 100%;"></canvas>
                        </div>

                        <div class="flex justify-center items-center mx-4 bg-white p-4 rounded-lg shadow-lg"
                            style="width: 100%;">
                            <canvas ref="chartPendingVsExpiredRef"
                                style="max-width: 100%; max-height: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
