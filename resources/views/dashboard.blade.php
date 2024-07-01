<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="text-2xl font-semibold mb-4">Bienvenido</h4>
                    <p class="text-lg">En las opciones de navegación puedes encontrar las opciones para gestionar todo lo que necesites.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
