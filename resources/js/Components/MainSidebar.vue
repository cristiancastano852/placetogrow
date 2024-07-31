<script setup lang="ts">
import { SSidebar, SSidebarItemGroup, SSidebarItem } from '@placetopay/spartan-vue';
import { HomeIcon, ReceiptTextIcon, DocumentCodeIcon, ClipboardTickIcon, ShieldSecurityIcon, Profile2UserIcon } from '@placetopay/iconsax-vue/linear';
import { computed } from 'vue';

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

console.log(value.value);
</script>

<template>
    <SSidebar class="w-60 pb-8" placetopayHeader v-model="value">
        <SSidebarItem path="home" :icon="HomeIcon">Dashboard</SSidebarItem>
        <SSidebarItem path="configuration" :icon="DocumentCodeIcon">Sitios</SSidebarItem>
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