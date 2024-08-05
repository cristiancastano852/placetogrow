<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    microsite: {
        id: number;
        category_id: number;
        name: string;
        slug: string;
        document_type: string;
        document_number: string;
        logo: string;
        currency: string;
        site_type: string;
        payment_expiration: number;
    };
    categories: Array<{ id: number; name: string }>;
}>();

const category_id = ref(props.microsite.category_id);
const name = ref(props.microsite.name);
const slug = ref(props.microsite.slug);
const document_type = ref(props.microsite.document_type);
const document_number = ref(props.microsite.document_number);
const logo = ref(props.microsite.logo);
const currency = ref(props.microsite.currency);
const site_type = ref(props.microsite.site_type);
const payment_expiration = ref(props.microsite.payment_expiration);

const goBack = () => {
    router.visit('/microsites');
}

const updateMicrosite = () => {
    router.put(route('microsites.update', props.microsite.id), {
        category_id: category_id.value,
        name: name.value,
        slug: slug.value,
        document_type: document_type.value,
        document_number: document_number.value,
        logo: logo.value,
        currency: currency.value,
        site_type: site_type.value,
        payment_expiration: payment_expiration.value,
    });
}

</script>

<template>
    <Head title="Edit Microsite" />
    <AuthenticatedMainLayout>
        <div class="flex flex-col items-center py-8">
            <button @click="goBack" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
                <em class="fa-solid fa-arrow-left"></em> Back
            </button>
            <div class="container p-4 sm:p-6 lg:p-8 bg-white">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                    Sitio
                </h2>
                <form @submit.prevent="updateMicrosite">
                    <div class="mb-4">
                        <label for="category_id" class="block text-gray-700">Categoria</label>
                        <select v-model="category_id" id="category_id" class="w-full border-gray-300 rounded" required>
                            <option v-for="category in props.categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Nombre del sitio</label>
                        <input v-model="name" type="text" id="name" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Slug</label>
                        <input v-model="slug" type="text" id="slug" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Tipo de documento</label>
                        <input v-model="document_type" type="text" id="document_type" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Número de documento</label>
                        <input v-model="document_number" type="text" id="document_number" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Logo</label>
                        <input v-model="logo" type="text" id="logo" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Tipo de moneda</label>
                        <input v-model="currency" type="text" id="currency" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Tipo de sitio</label>
                        <input v-model="site_type" type="text" id="site_type" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Tiempo de expiración del pago</label>
                        <input v-model="payment_expiration" type="number" id="payment_expiration" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
                </form>
            </div>
        </div>
    </AuthenticatedMainLayout>
</template>
