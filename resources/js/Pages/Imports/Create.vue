<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { SDataTable, SBadge, SButton } from '@placetopay/spartan-vue';
import { DocumentUploadIcon, BackSquareIcon } from '@placetopay/iconsax-vue/linear';
import { ref } from 'vue';

const value = ref('Sitios');
const form = useForm({
    file: null,
});

const props = defineProps({
    microsite: {
        type: Object,
        required: true,
    },
    imports: {
        type: Array,
        required: true,
    }
});

const submit = () => {
    form.post(route('import.store', { microsite: props.microsite.id }), {
        onSuccess: () => {
            form.reset();
        },
    });
};

const cols = [
    { id: 'status', header: 'Estado' },
    { id: 'file_name', header: 'Archivo' },
    { id: 'document_number', header: 'Número de documento' },
    { id: 'created_at', header: 'Fecha de creación' },

];

const colorByType = {
    PAID: 'green',
    PENDING: 'blue',
    FAILED: 'yellow',
};

const returnPage = () => {
    router.visit(route('microsites.index'));
}

</script>

<template>

    <Head title="Importar Facturas" />
    <AuthenticatedMainLayout v-model="value">
        <div class="mx-8">
        <div class="flex justify-between w-full px-16">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Importar facturas
            </h1>
            <SButton :leftIcon="BackSquareIcon" size="sm" rounded="full" variant="outline"
                                    @click="returnPage">Regresar</SButton>
        </div>

        <div class="flex w-full justify-center my-4">
            <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="file" class="block text-gray-700">File</label>
                        <input type="file" name="file" id="file" class="w-full border-gray-300 rounded"
                            @change="(e) => form.file = e.target.files[0]" required />
                        <div v-if="form.errors.file" class="text-red-500">{{ form.errors.file }}</div>
                    </div>
                    <SButton :icon="DocumentUploadIcon" type="submit" class="">
                        Importar
                    </SButton>
                </form>
            </div>

        </div>
        <div class="flex flex-col justify-center my-4 mx-8">
            <h2>Ultimas importaciones realizadas</h2>
            <SDataTable :cols="cols" :data="props.imports.data">
            </SDataTable>
        </div>
    </div>
    </AuthenticatedMainLayout>

</template>