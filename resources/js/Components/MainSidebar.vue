<script setup lang="ts">
import { SSidebar, SSidebarItemGroup, SSidebarItem } from '@placetopay/spartan-vue';
import { HomeIcon, ReceiptTextIcon, DocumentCodeIcon, ClipboardTickIcon, ShieldSecurityIcon, Profile2UserIcon } from '@placetopay/iconsax-vue/linear';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    modelValue: string;
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
console.log(value.value);
</script>

<template>
    <SSidebar class="w-60 pb-8" placetopayHeader v-model="value">
        <SSidebarItem @click="navigate('/dashboard')" path="Dashboard" :icon="HomeIcon">Dashboard</SSidebarItem>
        <SSidebarItem @click="navigate('/microsites')" path="Sitios" :icon="DocumentCodeIcon">Sitios</SSidebarItem>
        <SSidebarItem path="balance" :icon="ReceiptTextIcon">Transacciones</SSidebarItem>

        <SSidebarItemGroup :icon="ClipboardTickIcon">
            <template #title>Administración</template>
            <SSidebarItem @click="navigate('/users')" path="users" :icon="Profile2UserIcon">Usuarios</SSidebarItem>
            <SSidebarItem @click="navigate('/role-permission')" path="roles" :icon="ShieldSecurityIcon">Roles y permisos</SSidebarItem>
        </SSidebarItemGroup>

        <SSidebarItemGroup :icon="ShieldSecurityIcon">
            <template #title>Security</template>
            <SSidebarItem path="metrics">Logs</SSidebarItem>
        </SSidebarItemGroup>
    </SSidebar>
</template>