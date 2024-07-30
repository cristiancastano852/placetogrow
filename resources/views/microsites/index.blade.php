<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ trans('sites.title') }}
            </h2>
            <a href="{{ route('microsites.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                <em class="fa-solid fa-plus"></em>
            </a>
        </div>
    </x-slot>
    <div class="flex w-full justify-center my-4">
        <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
            @if (session('success'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @elseif (session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if ($microsites->isEmpty())
                <div class="text-center">
                    <p class="text-lg mb-4">
                        No hay micrositios registrados.
                    </p>
                    <a href="{{ route('microsites.create') }}"
                        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                        <em class="fa-solid fa-plus"></em> Agregar micrositio
                    </a>
                    </p>

            @elseif ($microsites->isNotEmpty())

                <table class="w-full my-0 align-middle text-dark border-neutral-200">
                    <thead class="align-bottom">
                        <tr class="font-semibold text-[0.95rem] text-secondary-dark">
                            <th class="pb-3 text-start min-w-[175px]">Nombre</th>
                            <th class="pb-3 text-start min-w-[100px]">Categoria</th>
                            <th class="pb-3 text-start min-w-[100px]">Tipo de sitio</th>
                            @if (auth()->user()->hasRole('Admin'))
                                <th class="pb-3 text-start min-w-[100px]">Creado por</th>
                            @endif
                            <th class="pb-3 text-start min-w-[100px]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($microsites as $site)
                            <tr class="border-b border-dashed last:border-b-0">
                                <td class="p-3 pl-0">
                                    <div class="flex items-center">
                                        <a href="/"
                                            class="transition ease-in-out delay-150 hover:scale-105 mb-1 text-start duration-200 text-lg/normal text-secondary-inverse hover:text-primary">
                                            {{ $site->name }} </a>
                                    </div>
                                </td>
                                <td class="p-3 pl-0 text-start">
                                    <span class="text-light-inverse text-md/normal">{{ $site->category->name }}</span>
                                </td>
                                <td class="p-3 pl-0 text-start">
                                    <span
                                        class="text-light
                                        text-md/normal">{{ $site->site_type }}</span>
                                </td>
                                @if (auth()->user()->hasRole('Admin'))
                                    <td class="p-3 pl-0 text-start">
                                        <span
                                            class="text-light
                                            text-md/normal">{{ $site->user->name }}</span>
                                    </td>
                                @endif
                                <td class="flex justify-end p-3 pr-0 text-end gap-3">
                                    <a href="{{ route('microsites.show', $site) }}">
                                        <em class="fa-solid fa-eye"></em>
                                    </a>
                                    <a href="{{ route('microsites.edit', $site) }}">
                                        <em class="fa-solid fa-pencil"></em>
                                    </a>
                                    <form action="{{ route('microsites.destroy', $site) }}" method="POST"
                                        class="inline-block" onsubmit="return confirmDeletion()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-800">
                                            <em class="fa-solid fa-trash"></em>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        function confirmDeletion() {
            return confirm({{ trans('sites.confirm_delete') }});
        }
    </script>
</x-app-layout>
