<script setup lang="ts">
import { ref } from 'vue';
import { SAccordion, SModalLeft, SAvatar, SDropdown, SDropdownItem, SDataTable, SBadge } from '@placetopay/spartan-vue';
import { MenuIcon, LogoutIcon, HomeIcon, ReceiptTextIcon, DocumentCodeIcon } from '@placetopay/iconsax-vue/linear';
import { router } from '@inertiajs/vue3'
import MainSidebar from '../Components/MainSidebar.vue';

const open = ref(true);
const value = ref('Dashboard');

const goBack = () => {
    router.visit('/');
};

const goEditProfile = () => {
    router.visit('/profile');
};


// const props = defineProps({
//     modelValue: {
//         type: String,
//         required: true,
//     },
// });
console.log(value.value);


const emit = defineEmits({
    'update:modelValue': (value) => true,
});

</script>

<template>
    <div class="flex h-screen">
        <SAccordion :open="open" class="hidden lg:block">
            <MainSidebar v-model="value" />
        </SAccordion>

        <SModalLeft breakpoint="lg" :open="open" @close="() => (open = false)">
            <MainSidebar v-model="value" />
        </SModalLeft>
        

        <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
            <!-- Navigation -->
            <nav class="flex w-full justify-between items-center border-b border-gray-200 px-5 py-2">
                <button @click="open = !open">
                    <MenuIcon class="relative h-6 w-6 text-gray-400" />
                </button>

                <SDropdown leftToRight>
                    <template #reference>
                        <SAvatar name="John Doe" />
                    </template>

                    <SDropdownItem :icon="LogoutIcon" @click="goBack"> Logout </SDropdownItem>
                    <SDropdownItem :icon="LogoutIcon" @click="goEditProfile"> Profile </SDropdownItem>
                </SDropdown>
            </nav>
            
            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="h-full w-full py-10">
                <slot />
            </main>
        </div>
    </div>
</template>

