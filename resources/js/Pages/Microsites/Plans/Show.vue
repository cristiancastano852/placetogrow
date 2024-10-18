<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import GuestMicrositesLayout from '@/Layouts/GuestMicrositesLayout.vue';
import { SModalCard, SButton } from '@placetopay/spartan-vue'; // Importar componentes necesarios
import { ref } from 'vue';
import { MagicStarIcon , LogoutIcon, ClipboardTextIcon   } from '@placetopay/iconsax-vue/linear';

const buyerModal = ref(false);

const closeModal2 = () => {
    buyerModal.value = false;
};

const props = defineProps({
    plans: {
        type: Array,
        required: true,
    },
    microsite: {
        type: Array,
        required: true,
    },
});

const createSubscriptionPlan = () => {
    const data = {
        plan_id: selectedPlan.value.id,
        buyer: formData.value,
    };
    router.post(route('subscriptions.store', { microsite: props.microsite.id }), data);
    showModal.value = false;
    
};
const showModal = ref(false); 
const selectedPlan = ref(null);
const formData = ref({});

const initializeFormData = () => {
    props.microsite.payment_fields.forEach(field => {
        formData.value[field.name] = field.optional ? '' : null;
    });
};


const documentTypes = ['CC', 'CE', 'NIT', 'PPN'];
const handleSelectPlan = (plan) => {
    selectedPlan.value = plan;
    initializeFormData();
    buyerModal.value = true;
};
</script>

<template>
    <Head title="Micrositios" />
    <GuestMicrositesLayout>
        <div class="container mx-auto py-8">
            <h1 class="text-2xl font-bold mb-4">Tu suscripcion al micrositio {{ microsite.name }}</h1>
            <h1 class="text-2xl font-bold mb-4">Planes</h1>
            <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">Selecciona tu plan para hacer tu pago</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="plan in props.plans" :key="plan.id"
                    class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ plan.name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">Duración del plan {{ plan.duration_period }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Precio: {{ plan.price }} cada {{ plan.billing_frequency }} {{ plan.duration_unit }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Descripción: {{ plan.description }}</p>
                    <SButton @click="handleSelectPlan(plan)" class="mt-4"> Seleccionar plan</SButton>
                </div>
            </div>
        </div>
    <SModalCard
        class="w-96"
        :icon="ClipboardTextIcon" 
        :open="buyerModal" 
        @close="closeModal2"
    >
        <template #title>¡Un datos más y listo!</template>
        <template #description>
            <div v-for="field in microsite.payment_fields" :key="field.name" class="mb-4">
                    <label :for="field.name" class="block font-medium text-gray-700">{{ field.label }}</label>
                    <div v-if="field.type === 'input'">
                        <input
                            v-model="formData[field.name]"
                            :type="field.input_type || 'text'"
                            :name="field.name"
                            :placeholder="field.placeholder"
                            :required="!field.optional"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                        />
                    </div>
                    <div v-else-if="field.type === 'select'">
                        <select
                            v-model="formData[field.name]"
                            :name="field.name"
                            :required="!field.optional"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                        >
                            <option v-for="type in documentTypes" :key="type" :value="type">{{ type }}</option>
                        </select>
                    </div>
                </div>
        </template>
        <template #actions>
            <SButton :icon="MagicStarIcon " class="w-full" variant="primary" @click="createSubscriptionPlan">
                Subscribirme
            </SButton>
        </template>
    </SModalCard>
    </GuestMicrositesLayout>
</template>
