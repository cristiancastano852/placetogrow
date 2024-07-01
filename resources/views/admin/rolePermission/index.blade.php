<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 border-b border-gray-200">
                <h1 class="text-xl text-gray-800 leading-tight">Gestión de usuarios</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($users as $user)
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div>
                                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cambiar Rol:</label>
                                        <select name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" @if($user->hasRole($role->name)) selected @endif>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Guardar</button>
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
