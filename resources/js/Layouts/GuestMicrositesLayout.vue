<script setup>
import { Head, Link } from '@inertiajs/vue3';

</script>

<template>

    <Head title="Micrositios" />
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col items-center justify-center">
        <header class="w-full bg-white dark:bg-gray-800 py-4 shadow-md">
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
        </main>
        <footer class="w-full bg-white dark:bg-gray-800 py-4 text-center text-gray-600 dark:text-gray-400">
            &copy; {{ new Date().getFullYear() }} Micrositios
        </footer>
    </div>
</template>
