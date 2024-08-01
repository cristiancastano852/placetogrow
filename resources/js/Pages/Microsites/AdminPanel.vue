<script setup lang="ts">

import { ref } from 'vue';
import { SAccordion, SModalLeft, SAvatar, SDropdown, SDropdownItem, SDataTable, SBadge } from '@placetopay/spartan-vue';
import { MenuIcon, LogoutIcon } from '@placetopay/iconsax-vue/linear';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'

const goBack = () => {
    router.visit('/');
}

const goEditProfile = () => {
    router.visit('/profile');
}

const props = defineProps({
    microsites: {
        type: Array,
        required: true,
    },
});

console.log(props.microsites);


const open = ref(true);
const value = ref('System');


const cols = [
    { id: 'id', header: 'ID' },
    { id: 'name', header: 'Site' },
    { id: 'logo', header: 'Logo' },
    { id: 'site_type', header: 'Tipo' },
    { id: 'actions', header: 'Actions' }];

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


</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedMainLayout v-model="value">
        <div class="flex h-screen">
            <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
                <main class="h-full w-full py-10">
                    <div class="h-full w-full px-4 sm:px-6 lg:px-8">
                        <SDataTable :cols="cols" :data="props.microsites">
                            <template #col[logo]="{ value }">
                                <img :src="value" class="w-10 h-10 rounded-xl" />
                            </template>

                            <template #col[site_type]="{ value }">
                                <SBadge class="capitalize" :color="colorByType[value]">{{ value }}</SBadge>
                            </template>

                            <template #col[actions]="{ record }">
                                <div class="flex gap-4">
                                    <button @click="viewMicrosite(record.id)"
                                        class="text-neutral-600 hover:text-neutral-900">Show</button>
                                    <button @click="editMicrosite(record.id)"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <button @click="deleteMicrosite(record.id)"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </div>
                            </template>
                        </SDataTable>
                    </div>
                </main>
            </div>
        </div>
    </AuthenticatedMainLayout>

</template>