<script setup lang="ts">
import { ref } from 'vue';
import { SAccordion, SModalLeft, SAvatar, SDropdown, SDropdownItem, SDataTable, SBadge } from '@placetopay/spartan-vue';
import { MenuIcon, LogoutIcon, UserEditIcon, ReceiptTextIcon, DocumentCodeIcon } from '@placetopay/iconsax-vue/linear';
import { router } from '@inertiajs/vue3'
import MainSidebar from '../Components/MainSidebar.vue';
import { computed } from 'vue';
import { usePage, Link } from "@inertiajs/vue3";
const page = usePage();
const permissions = page.props.auth?.permissions;
const roles = page.props.auth?.user.roles;
const open = ref(true);

const goBack = () => {
    router.visit('/');
};

const goLogout = () => {
    router.post(route('logout'));
};

const goEditProfile = () => {
    router.visit('/profile');
};


const props = defineProps<{
    modelValue: string;
}>();

const value = computed({
    get: () => props.modelValue,
    set: (value: string) => emit('update:modelValue', value),
});

const emit = defineEmits({
    'update:modelValue': (value) => true,
});

</script>

<template>
    <div class="flex h-screen overflow-hidden">
        <SAccordion :open="open" class="hidden lg:block">
            <MainSidebar v-model="value" :permissions="permissions" :roles="roles" />
        </SAccordion>

        <SModalLeft breakpoint="lg" :open="open" @close="() => (open = false)">
            <MainSidebar v-model="value" />
        </SModalLeft>


        <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600 overflow-hidden">
            <nav class="flex w-full justify-between items-center border-b border-gray-200 px-5 py-2">
                <button @click="open = !open">
                    <MenuIcon class="relative h-6 w-6 text-gray-400" />
                </button>

                <SDropdown leftToRight>
                    <template #reference>
                        <SAvatar name="John Doe" />
                    </template>

                    <SDropdownItem :icon="UserEditIcon" @click="goEditProfile"> Ver perfil </SDropdownItem>
                    <SDropdownItem :icon="LogoutIcon">
                        <Link :href="route('logout')" method="post" as="button"
                            class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cerrar sesión
                        </Link>
                    </SDropdownItem>
                </SDropdown>

            </nav>

            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="h-full w-full overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
