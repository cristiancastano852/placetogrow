<template>
    <div class="container mx-auto p-4">

        <Head title="Mis Planes" />
        <h1 class="text-2xl font-bold mb-4">Planes creados para el micrositio: {{ microsite.name }}</h1>

        <div class="mb-4 flex justify-between items-center">
            <span class="text-m text-gray-600">{{ plans.length }} planes disponibles</span>
            <SButton :leftIcon="AddIcon" size="sm" rounded="full" @click="goToCreatePlan" type="button"> Crear nuevo plan</SButton>
        </div>

        <div class="overflow-y-auto pr-4">
            <div class="flex flex-wrap -mx-2">

                <div v-for="(plan, index) in plans" :key="index" class="w-full md:w-1/2 lg:w-1/3 px-2 mb-4">
                    <SCard class="mx-auto w-full max-w-4xl">
                        <template #header>
                            <div class="flex justify-between gap-8">
                                <SPageTitle class="p-4">Plan {{ plan.name }}</SPageTitle>
                                <div class="space-x-1 p-4">
                                    <SButton :leftIcon="EditIcon" variant="link" size="sm" rounded="full" @click="editPlan(plan.id)" />
                                    <SButton :rightIcon="TrashIcon" variant="link" size="sm" rounded="full" @click="deletePlan(plan.id)" />
                                </div>
                            </div>
                        </template>

                        <div class="mt-2">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-1">
                                <SSectionTitle class="sm:col-span-1">
                                    Precio
                                    <SDefinitionTerm>{{ microsite.currency }} {{ plan.price }}</SDefinitionTerm>
                                </SSectionTitle>
                                <SSectionTitle class="sm:col-span-1">
                                    Descripción
                                    <SDefinitionTerm>{{ plan.description }}</SDefinitionTerm>
                                </SSectionTitle>
                                <SSectionTitle class="sm:col-span-1">
                                    Duración
                                    <SDefinitionTerm> {{ plan.duration_period }} {{ plan.duration_unit }}
                                    </SDefinitionTerm>
                                </SSectionTitle>
                                <SSectionTitle class="sm:col-span-1">
                                    Frecuencia de cobro
                                    <SDefinitionTerm> {{ plan.billing_frequency }} {{ plan.duration_unit }}
                                    </SDefinitionTerm>
                                </SSectionTitle>
                            </dl>
                        </div>
                    </SCard>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, router } from '@inertiajs/vue3'
import { SButton, SCard, SPageTitle, SSectionDescription, SDefinitionTerm, SLink, SBadge, SSectionTitle } from '@placetopay/spartan-vue';
import { EditIcon, TrashIcon, AddIcon } from '@placetopay/iconsax-vue/linear';
const page = usePage();
//   const plans = ref(page.props.plans || []);
const props = defineProps({
    microsite: {
        type: Object,
        required: true
    },
    plans: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        required: true
    },
    success: {
        type: Object,
        required: true
    }
})

const plans = ref(props.plans);
console.log("plans", plans);
const microsite_name = ref(page.props.microsite_name || ''); // Obtén el nombre del micrositio

const goToCreatePlan = () => {
    router.get(route('plans.create', { microsite: props.microsite.id }));
};

const editPlan = (id) => {
    router.visit(route('plans.edit', { microsite: props.microsite.id, plan: id }));
};


const deletePlan = (id) => {
    router.delete(route('plans.destroy',  { microsite: props.microsite.id, plan: id }), {
        preserveState: true,
        preserveScroll: true
    });
};
</script>