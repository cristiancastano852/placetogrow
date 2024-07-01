<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sites
            </h2>
            <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                <em class="fa-solid fa-plus"></em>
            </a>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-4">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 border-b border-gray-200">
                <h1 class="text-xl text-gray-800 leading-tight">Usuarios</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
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
                    @foreach ($users as $user)
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex justify-start">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirmDeletion()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-800 mx-4">
                                            <em class="fa-solid fa-trash"></em>
                                        </button>
                                    </form>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>

                                <div>
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div>

                                            <label for="role"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cambiar
                                                Rol:</label>
                                            <select name="role" id="role"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-28 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        @if ($user->hasRole($role->name)) selected @endif>
                                                        {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-28">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
