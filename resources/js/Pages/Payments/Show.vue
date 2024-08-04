<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { SLabel, SButton, SCard, SSectionTitle, SSectionDescription, SDefinitionTerm, SLink } from '@placetopay/spartan-vue';
import { TickCircleIcon, DangerIcon, CardTick1Icon } from '@placetopay/iconsax-vue/linear';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';


const props = defineProps({
    payment: {
        type: Object,
        required: true,
    },
});

const goMicrosite = () => {
    router.visit('/');
};

</script>

<template>

    <Head title="Pago" />
    <GuestMicrositesLayout>
        <SCard class="mx-auto w-full max-w-4xl">
            <SLabel class="bg-black/5 -mx-6 -mt-6 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">
                Resumen de tu pago
            </SLabel>
            <SLabel v-if="props.payment.status == 'APPROVED'" class="flex items-center justify-center  -mx-6 -mt-1 p-2 !font-bold text-center !text-lg shadow">
                <div class="w-96 flex items-center justify-center bg-green-100 text-green-500 rounded-full p-2 text-sm ">
                    <CardTick1Icon class="w-6 h-6" />
                    <p class="pl-1"> Pago realizado con exito</p>
                </div>
            </SLabel>
            <SLabel v-else class="flex items-center justify-center  -mx-6 -mt-1 p-2 !font-bold text-center !text-lg shadow">
                <div class="w-96 flex items-center justify-center bg-red-100 text-red-500 rounded-full p-2 text-sm ">
                    <DangerIcon class="w-6 h-6" />
                    <p class="pl-1"> ¡Ups! Hubo un problema con tu pago</p>
                </div>
            </SLabel>
            <div class="mt-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <SDefinitionTerm class="sm:col-span-1">
                        Estado
                        <template #description>{{ props.payment.status }}</template>
                    </SDefinitionTerm>

                    <SDefinitionTerm class="sm:col-span-1">
                        Sitio de pago
                        <template #description>{{ props.payment.microsite_id }}</template>
                    </SDefinitionTerm>

                    <SDefinitionTerm class="sm:col-span-1">
                        Valor
                        <template #description>
                            <!-- <SLink href="#">Jane Doe</SLink> -->
                            {{ props.payment.amount }} {{ props.payment.currency }}
                           
                        </template>
                    </SDefinitionTerm>

                    <SDefinitionTerm class="sm:col-span-1">
                        Referencia de pago
                        <template #description>
                            {{ props.payment.reference }}
                        </template>
                    </SDefinitionTerm>
                    <SDefinitionTerm class="sm:col-span-1">
                        Description
                        <template #description>
                            {{ props.payment.description }}
                        </template>
                    </SDefinitionTerm>
                    <SDefinitionTerm class="sm:col-span-1">
                        Correo
                        <template #description>
                            testing@mail.com
                            <!-- {{ props.payment.email }} -->
                        </template>
                    </SDefinitionTerm>
                    
                </dl>
            </div>
            <div class="flex justify-center mt-6">
                <SButton @click="goMicrosite">
                    Volver al sitio de pago
                </SButton>
            </div>
        </SCard>
    </GuestMicrositesLayout>
</template>
