<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrar Permisos de Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Permisos por Rol</h3>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($roles as $role)
                            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                                <h4 class="text-lg font-semibold mb-2">{{ $role->name }}</h4>

                                <form action="{{ route('admin.rolePermission.edit-permissions', $role) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach ($permissions as $permission)
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->name }}" class="sr-only peer"
                                                    {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                                <span
                                                    class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ trans('permissions.' . $permission->name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">
                                        Guardar Permisos
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
