<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';



const props = defineProps({
    payment: {
        type: Object,
        required: true,
    },
});

</script>

<template>

    <Head title="Pago" />
    <GuestMicrositesLayout>
        <div class="container mx-auto h-full">
            <div class="flex justify-center items-center h-full ">
                <div class="flex w-1/2 flex-col bg-white border shadow-sm rounded-xl p-4 md:p-5 max-w-50 mt-10">
                    <h3 class="text-lg font-bold text-gray-800">
                        Resumen de tu pago
                    </h3>
                    <p class="mt-1 mb-4 text-xs font-medium uppercase text-gray-500">
                        {{ props.payment.description }}
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong>Referencia</strong>: {{ props.payment.reference }}
                        </div>
                        <div>
                            <strong>Monto</strong>: {{ props.payment.amount }} {{ props.payment.currency }}
                        </div>
                        <div>
                            <strong>Estado</strong>: {{ props.payment.status }}
                        </div>
                        <div>
                            <strong>Fecha</strong>: {{ props.payment.created_at }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestMicrositesLayout>
</template>
