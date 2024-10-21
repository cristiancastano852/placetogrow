<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ArrowLeft1Icon } from '@placetopay/iconsax-vue/linear';
import { router } from '@inertiajs/vue3';
import PaymentsChart from './Charts/PaymentsChart.vue';

const props = defineProps<{
    microsite: {
        category: { name: string };
        slug: string;
        name: string;
        document_type: string;
        document_number: string;
        logo: string;
        currency: string;
        site_type: string;
        payment_expiration: number;
        created_at: string;
        updated_at: string;
    },
    paymentsByMonth: 
    {
        month: string;
        total_payments: number;
    }[];
    
}>();

const goBack = () => {
    router.visit('/microsites');
}
</script>

<template>
    <Head title="Microsite Details" />
    <AuthenticatedMainLayout>
        <div class="flex flex-col items-center">
            <div class="container p-4 sm:p-6 lg:p-8 bg-white">
                
                <div class="flex items-center mb-6">
                    <button @click="goBack" class="mr-4">
                        <ArrowLeft1Icon class="h-6 w-6 text-gray-400 hover:text-gray-800 duration-200" />
                    </button>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        {{ props.microsite.name }}
                    </h2>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold">Slug</label>
                    <p class="text-gray-900">{{ props.microsite.slug }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Nombre</label>
                    <p class="text-gray-900">{{ props.microsite.name }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Moneda</label>
                    <p class="text-gray-900">{{ props.microsite.currency }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Tipo de sitio</label>
                    <p class="text-gray-900">{{ props.microsite.site_type }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Tiempo de expiración del pago</label>
                    <p class="text-gray-900">{{ props.microsite.payment_expiration }} minutos</p>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Fecha de creación</label>
                    <p class="text-gray-900">{{ props.microsite.created_at }}</p>
                </div>
       
            </div>
            </div>
            <div class="mt-12 h-1200 w-1200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Pagos Mensuales</h3>
                <div class="h-1200 w-1200">
                    <PaymentsChart :paymentsByMonth="props.paymentsByMonth" />
                </div>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
