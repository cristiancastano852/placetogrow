<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';

const selectedRoles = ref({});
const props = defineProps<{
    users: {
        id: number;
        name: string;
        email: string;
        roles: string[];
    }[];
    roles: {
        name: string;
    }[];
}>();

const users = ref(props.users);
const roles = ref(props.roles);

watch(users, (newUsers) => {
    newUsers.forEach(user => {
        if (user.roles.length) {
            selectedRoles.value[user.id] = user.roles[0].name;
        }
    });
}, { immediate: true });

function confirmDeletion(userId) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        router.delete(route('users.destroy', userId));
    }
}

function updateUserRole(userId) {
    router.put(route('admin.users.update', userId), {
        role: selectedRoles.value[userId]
    });
}

function createUser() {
    router.get(route('users.create'));
}

const value = ref('users');
</script>
<template>
    <Head title="Users Management" />
    <AuthenticatedMainLayout v-model="value">
        <main class="h-screen w-full overflow-hidden">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-4">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 border-b border-gray-200">
                        <h1 class="text-xl text-gray-800 leading-tight">Usuarios</h1>
                        <button @click="createUser" class="bg-blue-500 text-white px-4 py-2 rounded">
                            <em class="fa-solid fa-plus"></em> Crear Nuevo Usuario
                        </button>
                    </div>

                    <div class="p-6 max-h-[calc(100vh-200px)]">
                        <div class="grid grid-cols-1 gap-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                            <div v-for="user in users" :key="user.id"
                                class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex justify-start">
                                        <button @click="confirmDeletion(user.id)" class="text-red-800 mx-4">
                                            <em class="fa-solid fa-trash"></em>
                                        </button>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">{{ user.name }}</p>
                                            <p class="text-xs text-gray-500">{{ user.email }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <form @submit.prevent="updateUserRole(user.id)">
                                            <div>
                                                <label for="role"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Cambiar Rol:
                                                </label>
                                                <select v-model="selectedRoles[user.id]" id="role"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-28 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option v-for="role in roles" :key="role.name" :value="role.name">
                                                        {{ role.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mt-2">
                                                <button type="submit"
                                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-28">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedMainLayout>
</template>
