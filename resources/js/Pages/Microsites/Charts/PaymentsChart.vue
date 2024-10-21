<template>
    <div class="w-full h-[500px]">
        <canvas ref="chartRef" id="paymentsChart" class="w-full h-full"></canvas>
    </div>

</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps<{
    paymentsByMonth: {
        month: string;
        total_payments: number;
    }[];
}>();

const chartRef = ref(null);

onMounted(() => {
    if (chartRef.value) {
        const ctx = chartRef.value.getContext('2d');

        const labels = props.paymentsByMonth.map(payment => payment.month);
        const data = props.paymentsByMonth.map(payment => payment.total_payments);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pagos por mes',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(0, 0, 0, 0)',
                    fill: true,
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
                        text: 'Monto pagos Mensuales',
                    },
                },
            },
        });
    }
});
</script>