<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ref } from 'vue';

const value = ref('microsites');
const form = useForm({
    file: null, 
});

const props = defineProps({
    microsite: {
        type: Object,
        required: true,
    },
});

const submit = () => {
    form.post(route('import.store', {microsite: props.microsite.id}), {
        onSuccess: () => {
            form.reset();
        },
    });
};

</script>

<template>

    <Head title="Importar Facturas" />
    <AuthenticatedMainLayout v-model="value">
        <div class="flex justify-between w-full">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Import invoices
            </h1>
            <Link href="{{ route('admin.imports.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                <em class="fa-solid fa-arrow-left"></em> Back
            </Link>
        </div>

        <div class="flex w-full justify-center my-4">
            <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="file" class="block text-gray-700">File</label>
                        <input 
                            type="file" 
                            name="file" 
                            id="file" 
                            class="w-full border-gray-300 rounded"
                            @change="(e) => form.file = e.target.files[0]"
                            required
                        />
                        <div v-if="form.errors.file" class="text-red-500">{{ form.errors.file }}</div>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        {{ $t('sites.save') }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedMainLayout>
    
</template>