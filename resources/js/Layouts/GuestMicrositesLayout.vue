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


</script>

<template>
    <div class="flex h-screen w-full flex flex-1 flex-col ">

        <Head title="Micrositios" />
        <!-- <header class="w-full bg-white dark:bg-gray-800 py-4 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Sitios</h1>
                <div>
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    Ver menu
                    </Link>
                    <Link v-if="!$page.props.auth.user" :href="route('dashboard')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                    Iniciar sesión
                    </Link>
                </div>
            </div>
        </header>
        <main class="flex-grow container mx-auto py-8">
            <slot></slot>
        </main> -->
        <div class="flex flex-1 flex-col items-start bg-gray-100 font-bold text-gray-600">
            <nav class="flex w-full justify-between items-center border-b border-gray-200 px-5 py-2">
                <button @click="open = !open">
                </button>

                <SDropdown leftToRight>
                    <template #reference>
                        <SButton :leftIcon="UserAddIcon" size="md" rounded="full" />
                    </template>

                    <SDropdownItem v-if="!$page.props.auth.user" :icon="LogoutIcon" @click="goLogin"> Iniciar sesión
                    </SDropdownItem>
                </SDropdown>
            </nav>

            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="h-full w-full py-10 overflow-hidden">
                <slot />
            </main>
        </div>
        <!-- <footer class="w-full bg-white dark:bg-gray-800 py-4 text-center text-gray-600 dark:text-gray-400">
            &copy; {{ new Date().getFullYear() }} Micrositios
        </footer> -->
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
