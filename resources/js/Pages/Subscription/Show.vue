<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';
import { SBadge, SButton, SCard, SDefinitionTerm, SLabel } from '@placetopay/spartan-vue';

const props = defineProps({
    subscription : {
        type: Object,
        required: true,
    },
    microsite: {
        type: Object,
        required: true,
    },
});

const goToHome = () => {
    router.visit('/');
}

</script>

<template>
    <Head title="Micrositios" />
    <GuestMicrositesLayout>
        <SCard class="mx-auto w-full max-w-4xl">
            <SLabel class="bg-black/5 -mx-6 -mt-6 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">
                Resumen de tu proceso de subscripción
            </SLabel>
            <SLabel v-if="props.subscription.status == 'ACTIVE'" class="flex items-center justify-center  -mx-6 -mt-1 p-2 !font-bold text-center !text-lg shadow">
                <div class="w-96 flex items-center justify-center bg-green-100 text-green-500 rounded-full p-2 text-sm ">
                    <CardTick1Icon class="w-6 h-6" />
                    <p class="pl-1"> Subscripción activa</p>
                </div>
            </SLabel>
            <SLabel v-else class="flex items-center justify-center  -mx-6 -mt-1 p-2 !font-bold text-center !text-lg shadow">
                <div class="w-96 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full p-2 text-sm ">
                    <DangerIcon class="w-6 h-6" />
                    <p class="pl-1"> Subscripción pendiente de confirmación</p>
                </div>
            </SLabel>
            <div class="mt-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <SDefinitionTerm class="sm:col-span-1">
                        Estado
                        <template #description>{{ props.subscription.status }}</template>
                    </SDefinitionTerm>
                    <SDefinitionTerm class="sm:col-span-1">
                        Primer cobro por
                        <template #description>{{ props.subscription.price }}</template>
                    </SDefinitionTerm>

                    <SDefinitionTerm class="sm:col-span-1">
                        Referencia de pago
                        <template #description>
                            {{ props.subscription.reference }}
                        </template>
                    </SDefinitionTerm>
                    <SDefinitionTerm class="sm:col-span-1">
                        Description
                        <template #description>
                            {{ props.subscription.description }}
                        </template>
                    </SDefinitionTerm>
                </dl>
            </div>
            <div class="flex justify-center mt-6">
                <SButton @click="goToHome">
                    Volver al sitio de pago
                </SButton>
            </div>
        </SCard>
    </GuestMicrositesLayout>
</template>
