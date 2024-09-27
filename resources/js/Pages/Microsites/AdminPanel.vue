<script setup lang="ts">

import { ref } from 'vue';
import { SButton, SDataTable, SBadge } from '@placetopay/spartan-vue';
import { MenuIcon, ArrowDownIcon, AddIcon } from '@placetopay/iconsax-vue/linear';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router, usePage } from '@inertiajs/vue3'

const goBack = () => {
    router.visit('/');
}

const goEditProfile = () => {
    router.visit('/profile');
}
const { props: { auth: { permissions, roles } } } = usePage();

const props = defineProps({
    microsites: {
        type: Array,
        required: true,
    },
});

const open = ref(true);
const value = ref('Sitios');


const cols = [
    { id: 'id', header: 'Número' },
    { id: 'name', header: 'Sitio' },
    { id: 'logo', header: 'Logo' },
    { id: 'site_type', header: 'Tipo' },
    { id: 'actions', header: 'Acciones' }];

const colorByType = {
    Facturas: 'green',
    Donaciones: 'blue',
    Subscripciones: 'yellow',
};

const deleteMicrosite = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este microsite?')) {
        router.delete(route('microsites.destroy', id));
    }
}

const viewMicrosite = (id) => {
    router.visit(route('microsites.show', id));
}

const editMicrosite = (id) => {
    router.visit(route('microsites.edit', id));
}

const createMicrosite = () => {
    router.visit(route('microsites.create'));
}

const showPlans = (id) => {
    router.visit(route('plans.index', id));
}
const importInvoices = (id) => {
    router.visit(route('invoice.invoicesByMicrosite', id));
}

</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedMainLayout v-model="value">
        <div class="flex h-screen">
            <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
                <main class="h-full w-full py-10">
                    <div class="h-full w-full px-4 sm:px-6 lg:px-8">
                        <SButton v-if="permissions.includes('microsites.create')" :leftIcon="AddIcon" size="sm"
                            rounded="full" @click="createMicrosite()" class="m-4"> Crear nuevo micrositio</SButton>
                        <SDataTable :cols="cols" :data="props.microsites">
                            <template #col[logo]="{ value }">
                                <img :src="value" class="w-10 h-10 rounded-xl" />
                            </template>

                            <template #col[site_type]="{ value }">
                                <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                            </template>

                            <template #col[actions]="{ record }">

                                <div class="flex gap-4">
                                    <button v-if="permissions.includes('microsites.view')"
                                        @click="viewMicrosite(record.id)"
                                        class="text-neutral-600 hover:text-neutral-900">{{ $t('microsite.show') }}</button>
                                    <button v-if="permissions.includes('microsites.update')"
                                        @click="editMicrosite(record.id)"
                                        class="text-indigo-600 hover:text-indigo-900">{{ $t('microsite.edit') }}</button>
                                    <button v-if="permissions.includes('microsites.delete')"
                                        @click="deleteMicrosite(record.id)"
                                        class="text-red-600 hover:text-red-900">{{ $t('microsite.delete') }}</button>
                                    <button v-if="record.site_type === 'Subscripciones'" @click="showPlans(record.id)"
                                        class="text-green-600 hover:text-green-900">{{ $t('microsite.Plans') }}</button>
                                    <button v-if="record.site_type === 'Facturas'" @click="importInvoices(record.id)"
                                        class="text-green-600 hover:text-green-900">{{ $t('microsite.Invoices') }}</button>
                                </div>

                            </template>
                        </SDataTable>

                    </div>
                </main>
            </div>
        </div>
    </AuthenticatedMainLayout>

</template>