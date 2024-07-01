<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[877px]"
            src="https://laravel.com/assets/img/welcome/background.svg"
        />
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-start gap-2 py-10 lg:grid-cols-3 -mt-32">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <svg
                            class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]"
                            viewBox="0 0 62 65"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill="currentColor"
                            />
                        </svg>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end items-center">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 border border-transparent text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Ver menú
                        </Link>
                        <Link
                            v-else
                            :href="route('login')"
                            class="ml-3 rounded-md px-3 py-2 border border-transparent text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Iniciar sesión
                        </Link>
                    </nav>
                </header>

                <main class="mt-6 flex flex-col items-center justify-center text-center">
                    <h1 class="text-3xl font-bold text-black dark:text-white">Bienvenido a nuestra página</h1>
                    <p class="mt-4 text-lg text-black/70 dark:text-white/70">Donde puedes hacer todos tus pagos</p>
                    <Link
                        :href="route('micrositesall')"
                        class="mt-8 inline-block rounded-md bg-[#FF2D20] px-5 py-3 text-white transition hover:bg-[#E6221A] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FF2D20] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-black"
                    >
                        Ver sitios de pago
                    </Link>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Development by <a href="https://www.linkedin.com/in/cristiancastano852/" class="text-[#FF2D20] hover:underline">Cristian Castaño</a>
                </footer>
            </div>
        </div>
    </div>
</template>
