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
        <SSidebarItem path="Dashboard" :icon="HomeIcon">Dashboard</SSidebarItem>
        <SSidebarItem @click="navigate('/microsites')" path="Sitios" :icon="DocumentCodeIcon">Sitios</SSidebarItem>
        <SSidebarItem path="users" :icon="Profile2UserIcon">Users</SSidebarItem>
        <SSidebarItem path="balance" :icon="ReceiptTextIcon">Transactions</SSidebarItem>

        <SSidebarItemGroup :icon="ClipboardTickIcon">
            <template #title>Administration</template>

            <SSidebarItem path="my-merchants">Merchants</SSidebarItem>
            <SSidebarItem path="my-sites">Sites</SSidebarItem>
            <SSidebarItem path="my-users">Users</SSidebarItem>
        </SSidebarItemGroup>

        <SSidebarItemGroup :icon="ShieldSecurityIcon">
            <template #title>Security</template>

            <SSidebarItem path="roles">Roles</SSidebarItem>
            <SSidebarItem path="permissions">Permissions</SSidebarItem>
            <SSidebarItem path="metrics">Logs</SSidebarItem>
        </SSidebarItemGroup>
    </SSidebar>
</template>