<script setup>
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue';
import { SButton, SInputDate, SSelect, SBadge, SDataTable } from '@placetopay/spartan-vue';
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

const { props: { auth: { permissions, roles }, metrics } } = usePage();
const invoicesExpirateAndDue = ref(metrics.invoicesAlert);
const ensureArray = (obj) => {
    if (Array.isArray(obj)) return obj;
    return Object.keys(obj).map(key => obj[key]);
};

const handlePaginationChange = ({ page, size }) => {
    router.get(route('dashboard'), { page, size });
};

const dataInvoices = computed(() => ensureArray(invoicesExpirateAndDue.value.data));

const pagination = ref({
    page: invoicesExpirateAndDue.value.currentPage || 1,
    size: invoicesExpirateAndDue.value.perPage || 10,
    count: invoicesExpirateAndDue.value.last_page || 0,
});
const total = ref(invoicesExpirateAndDue.value.total || 0);
const statusInvoices = ref(metrics.statusInvoices) || {};
const NumberInvoicesPending = statusInvoices.value.PENDING || 0;
const numberInvoicesPaid = statusInvoices.value.PAID || 0;
const numberInvoicesExpired = statusInvoices.value.EXPIRED || 0;
const numberInvoiceDueExpiration = statusInvoices.value.numberInvoicesDueExpire || 0;


const value = ref('Dashboard');
const cols = [
    { id: 'reference', header: 'Referencia' },
    { id: 'name', header: 'Factura' },
    { id: 'status', header: 'Estado' },
    { id: 'expiration_date', header: 'Fecha de expiración' },
    { id: 'total_amount', header: 'Valor a pagar' },
];

const colorByType = {
    EXPIRED: 'red',
    PENDING: 'yellow',
};


const startDate = ref(null);
const endDate = ref(null);
const selectedMicrosite = ref(null);

const chartPendingVsPaidRef = ref(null);
const chartPendingVsExpiredRef = ref(null);
const chartStackedBarRef = ref(null);
const showModal = ref(false);

onMounted(() => {
    let delayed;
    showModal.value = true;
    if (chartPendingVsPaidRef.value) {
        const ctxPaid = chartPendingVsPaidRef.value.getContext('2d');
        new Chart(ctxPaid, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'Pagadas'],
                datasets: [
                    {
                        label: 'Facturas',
                        data: [NumberInvoicesPending || 0, numberInvoicesPaid || 0],
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
                animation: {
                    onComplete: () => {
                        delayed = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed) {
                            delay = context.dataIndex * 300;
                        }
                        return delay;
                    },
                    duration: 2000
                },
            },
        });
    };
    if (chartPendingVsExpiredRef.value) {
        const ctxExpired = chartPendingVsExpiredRef.value.getContext('2d');
        new Chart(ctxExpired, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'Vencidas'],
                datasets: [
                    {
                        label: 'Facturas',
                        data: [NumberInvoicesPending, numberInvoicesExpired],
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
                animation: {
                    onComplete: () => {
                        delayed = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed) {
                            delay = context.dataIndex * 300;
                        }
                        return delay;
                    },
                    duration: 2000
                },
            },
        });
    };
    if (chartStackedBarRef.value) {
        const ctxStackedBar = chartStackedBarRef.value.getContext('2d');
        new Chart(ctxStackedBar, {
            type: 'bar',
            data: {
                labels: ['Pendientes', 'Próximas a vencer', 'Vencidas'],
                datasets: [{
                    label: 'Alerta',
                    data: [NumberInvoicesPending || 0, numberInvoiceDueExpiration || 0, numberInvoicesExpired || 0],
                    backgroundColor: [
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(255, 99, 132)',
                    ],
                    borderWidth: 1
                }],

            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Estado de Facturas'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    };
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
                    <header class="m-12" v-if="roles.includes('Guests')">
                        <h1 class="text-base font-semibold text-gray-900">
                            Bienvenido a nuestro portal de administración
                        </h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Aquí podrás ver el estado de tus facturas, subscripciones y realizar pagos
                        </p>
                    </header>
                    <div v-else>
                        <div class="mb-4 flex flex-wrap items-center bg-gray-50 p-4 shadow-md rounded-lg">
                            <div class="flex-1 px-2">
                                <p class="text-sm text-gray-600">Micrositio</p>
                                <SSelect class="w-full" v-model="selectedMicrosite">
                                    <option value="0" selected="True">Todos</option>
                                    <option v-for="microsite in props.microsites" :value="microsite.id">{{
                                        microsite.name }}
                                    </option>
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

                        <h2 class="text-lg font-semibold text-gray-900 mx-8 mt-8">Metricás de facturas</h2>

                        <div class="flex justify-between mx-8 mt-8">
                            <div class="flex justify-center items-center mx-4 bg-white p-4 rounded-lg shadow-lg"
                                style="width: 100%;">
                                <canvas ref="chartPendingVsPaidRef" style="max-width: 100%; max-height: 100%;"></canvas>
                            </div>
                            <div class="flex justify-center items-center mx-4 bg-white p-4 rounded-lg shadow-lg"
                                style="width: 100%;">
                                <canvas ref="chartPendingVsExpiredRef"
                                    style="max-width: 100%; max-height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    <h1 class="text-base font-semibold text-gray-900 ml-12">Estado General de Facturas</h1>
                    <div class="mx-8 mt-2 h-full">
                        <div class="flex justify-center items-center mx-4 bg-white p-4 rounded-lg shadow-lg"
                            style="width: 100%;">
                            <canvas ref="chartStackedBarRef" style="max-width: 100%; max-height: 100%;"></canvas>
                        </div>
                    </div>

                    <div class="mx-8 mt-6">
                        <h2 class="text-lg font-semibold text-gray-900">Detalle de Facturas</h2>
                        <div v-if="total==0">
                            <p class="text-gray-600">No hay facturas para mostrar</p>

                        </div>
                        <div v-else>
                        <SDataTable :cols="cols" :data="dataInvoices" numericPaginator :pagination="pagination"
                            @paginationChange="handlePaginationChange">
                            <template #col[status]="{ value }">
                                <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                            </template>
                        </SDataTable>
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
