<script setup>
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head, router } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue';
import { SButton, SInputDate, SSelect, SBreadcrumbsItem, SBreadcrumbs } from '@placetopay/spartan-vue';
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

                    <div class="mb-8 flex justify-between bg-gray-50 p-8 shadow-md">
                        <SSelect class="mr-4" v-model="selectedMicrosite">
                            <option value="0" selected="True">Todos</option>
                            <option v-for="microsite in props.microsites" :value="microsite.id">{{ microsite.name }}
                            </option>
                        </SSelect>
                        <SInputDate v-model="startDate" placeholder="Fecha de inicio" class="mr-4" />
                        <SInputDate v-model="endDate" placeholder="Fecha final" class="mr-4" />
                        <SButton @click="applyFilters" class="w-full">Aplicar Filtros</SButton>
                    </div>
                    <h4 class="text-2xl font-semibold mb-4">Bienvenido</h4>
                    <p class="text-lg">Métricas de Facturas</p>
                    <div class="flex justify-between">
                        <div class="flex-1 mx-4" style="width: 20%; height: 20%;">
                            <canvas ref="chartPendingVsPaidRef" width="w-1/5" height="h-1/5"></canvas>
                        </div>
                        <div class="flex-1 mx-4" style="width: 20%; height: 20%;">
                            <canvas ref="chartPendingVsExpiredRef" width="w-1/5" height="h-1/5"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
