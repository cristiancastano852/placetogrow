<script setup lang="ts">
import { SSidebar, SSidebarItemGroup, SSidebarItem } from '@placetopay/spartan-vue';
import { HomeIcon, ReceiptTextIcon, DocumentCodeIcon, ClipboardTickIcon, ShieldSecurityIcon, Profile2UserIcon, Star1Icon, DocumentCopyIcon   } from '@placetopay/iconsax-vue/linear';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    modelValue: string;
    permissions: string[];
    roles : string[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>();

const value = computed({
    get: () => props.modelValue,
    set: (value: string) => emit('update:modelValue', value),
});

const navigate = (path: string) => {
    router.visit(path);
};
</script>

<template>
    <SSidebar class="w-60 pb-8" placetopayHeader v-model="value">
        <SSidebarItem @click="navigate('/dashboard')" path="Dashboard" :icon="HomeIcon">{{$t('home.title')}}</SSidebarItem>
        <SSidebarItem v-if="is('Admin') || can('microsites.view_any')" @click="navigate('/microsites')" path="Sitios" :icon="DocumentCodeIcon">{{$t('microsite.title')}}</SSidebarItem>
        <SSidebarItem v-if="is('Admin') || can('transactions.view_any')" @click="navigate('/payments')" path="payments" :icon="ReceiptTextIcon">Transacciones</SSidebarItem>
        <SSidebarItem v-if="is('Admin') || can('transactions.view_any')" @click="navigate('/subscriptions')" path="subscriptions" :icon="Star1Icon">Subscripciones</SSidebarItem>
        <SSidebarItem v-if="is('Admin') || can('transactions.view_any')" @click="navigate('/invoices')" path="invoices" :icon="DocumentCopyIcon">Facturas</SSidebarItem>

        <SSidebarItemGroup v-if="is('Admin')" :icon="ClipboardTickIcon">
            <template #title>Administration</template>
            <SSidebarItem v-if="is('Admin')" @click="navigate('/users')" path="users" :icon="Profile2UserIcon">Usuarios</SSidebarItem>
            <SSidebarItem v-if="is('Admin')" @click="navigate('/role-permission')" path="roles" :icon="ShieldSecurityIcon">Roles y permisos</SSidebarItem>
        </SSidebarItemGroup>

        <SSidebarItemGroup :icon="ShieldSecurityIcon">
            <template #title>Security</template>
            <SSidebarItem path="metrics">Logs</SSidebarItem>
        </SSidebarItemGroup>
    </SSidebar>
</template>