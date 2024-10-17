<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { MenuIcon, UserAddIcon } from '@placetopay/iconsax-vue/linear';
import { SAccordion, SButton, SAvatar, SDropdown, SDropdownItem, SDataTable, SBadge } from '@placetopay/spartan-vue';
import { router } from '@inertiajs/vue3'

const open = ref(true);

const goLogin = () => {
    router.visit('/login');
}

const goLogout = () => {
        router.post('/logout');
}

const goDashboard = () => {
    router.visit('/dashboard');
}


</script>

<template>
    <div class="flex h-screen w-full flex flex-1 flex-col h-full ">

        <Head title="Micrositios" />
        <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
            <nav class="flex w-full justify-between items-center border-b border-gray-200 px-5 py-2">
                <button @click="open = !open">
                </button>

                <SDropdown leftToRight>
                    <template #reference>
                        <SButton :leftIcon="UserAddIcon" size="md" rounded="full" />
                    </template>

                    <SDropdownItem v-if="!$page.props.auth.user" :icon="LogoutIcon" @click="goLogin">{{$t('auth.login')}}
                    </SDropdownItem>
                    <SDropdownItem v-if="$page.props.auth.user" :icon="LogoutIcon" @click="goLogout"> Cerrar sesión
                    </SDropdownItem>
                    <SDropdownItem v-if="$page.props.auth.user" @click="goDashboard"> Configuración y historial
                    </SDropdownItem>
                </SDropdown>
            </nav>

            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="h-full w-full py-10 overflow-y-auto">
                <slot />
            </main>
        </div>

        <footer class="flex w-full justify-center gap-8 bg-white text-gray-400">
            <span>© Placetopay</span>

            <SDropdown placement="top-start">
                <template #reference>
                    <button class="flex items-center gap-1.5">
                        <ChatBubbleBottomCenterTextIcon class="h-4 w-4" />
                        Idioma
                    </button>
                </template>

                <div class="text-sm font-medium text-gray-800 w-36">
                    <SDropdownItem>Español</SDropdownItem>
                    <SDropdownItem>Inglés</SDropdownItem>
                </div>
            </SDropdown>
        </footer>
    </div>
</template>
