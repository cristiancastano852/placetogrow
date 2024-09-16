<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const goBack = () => {
    router.visit('/micrositesall');
};

const props = defineProps({
    microsite: {
        type: Object,
        required: true,
    },
    errors: {
        type: Array,
        required: true,
    },
    successMessage: {
        type: String,
        required: false,
    },
});

let formData = {
    // Define fields common to all site types or default fields
};

const errors = ref<string[]>([]);

if (props.microsite.site_type === 'Facturas') {
    formData.invoice_number = '';
    formData.amount_due = '';
} else if (props.microsite.site_type === 'Donaciones') {
    formData.donor_name = '';
    formData.amount = '';
} else if (props.microsite.site_type === 'Subscripciones') {
    formData.subscription_id = '';
    formData.amount = '';
}

const submitForm = () => {
    formData.microsite_id = props.microsite.id;
    formData.fields_data = JSON.stringify(formData);
    formData.gateway = 'placetopay';

    errors.value = [];

    router.post(route('payments.store'), formData, {
        onError: (errorResponse) => {
            console.log("-----------------",errorResponse);
            errors.value.push("Hubo un error al procesar tu solicitud. Por favor, intenta de nuevo o contacta al soporte.");
        },
        onSuccess: (page) => {
            console.log(page);
        },
    });
};
</script>

<template>
    <Head title="Micrositio - {{ microsite.name }}" />
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Micrositio - {{ microsite.name }}</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">Unos datos más y podrás hacer tu pago.</p>

        <form @submit.prevent="submitForm">
            <!-- Display errors -->
            <div v-if="errors.length" class="mb-4">
                <div v-for="error in errors" :key="error" class="text-red-600">
                    {{ error }}
                </div>
            </div>

            <div v-if="microsite.site_type === 'Facturas'">
                <div>
                    <label for="invoice_number">Número de Factura</label>
                    <input v-model="formData.invoice_number" type="text" id="invoice_number" class="input" />
                </div>
            </div>
            <div v-if="microsite.site_type === 'Donaciones'">
                <div v-for="(field, index) in microsite.payment_fields" :key="index">
                    <div v-if="field.type === 'input'">
                        <label :for="field.name">{{ field.label }}</label>
                        <input v-model="formData[field.name]" :type="field.validation" :id="field.name"
                            :placeholder="field.placeholder" :required="!field.optional" class="input" />
                    </div>
                    <div v-if="field.type === 'select'">
                        <label :for="field.name">{{ field.label }}</label>
                        <select v-model="formData[field.name]" :id="field.name" class="input"
                            :required="!field.optional">
                            <option value="" selected>Seleccione una opción</option>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="NIT">NIT</option>
                            <option value="PPT">Pasaporte</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="amount">Monto en {{ microsite.currency }}</label>
                    <input v-model="formData.amount" type="text" id="amount" class="input" />
                </div>
            </div>
            <div v-if="microsite.site_type === 'Subscripciones'">
                <div>
                    <label for="subscription_id">Número de Suscripción</label>
                    <input v-model="formData.subscription_id" type="text" id="subscription_id" class="input" />
                </div>
            </div>

            <button type="button" @click="goBack"
                class="inline-flex items-center px-3 py-2 mt-2 mr-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                Cancelar
            </button>

            <button type="submit"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Pagar
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 5h12m0 0L9 1m4 4L9 9" />
                </svg>
            </button>
        </form>
    </div>
</template>

<style>
.input {
    display: block;
    width: 100%;
    padding: 0.5rem;
    margin-top: 0.5rem;
    margin-bottom: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
