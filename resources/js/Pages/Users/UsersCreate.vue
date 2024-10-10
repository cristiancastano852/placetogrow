<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';

const props = defineProps<{
  roles: {
    id: number;
    name: string;
  }[];
}>();

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const selectedRole = ref(props.roles[0]?.id || '');

function submitForm() {
  router.post(route('users.store'), {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: passwordConfirmation.value,
    role: selectedRole.value,
  });
}
</script>

<template>
  <Head title="Crear Usuario" />
  <AuthenticatedMainLayout>
    <div class="flex w-full justify-center my-4">
      <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
        <form @submit.prevent="submitForm">
          <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
            <input
              id="name"
              v-model="name"
              type="text"
              class="block mt-1 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
              required
              autofocus
            />
          </div>

          <div class="mt-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Correo</label>
            <input
              id="email"
              v-model="email"
              type="email"
              class="block mt-1 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
              required
            />
          </div>

          <div class="mt-4">
            <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Rol</label>
            <select
              v-model="selectedRole"
              id="role"
              class="block mt-1 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
            >
              <option v-for="role in props.roles" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
          </div>

          <div class="mt-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Contraseña</label>
            <input
              id="password"
              v-model="password"
              type="password"
              class="block mt-1 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
              required
            />
          </div>

          <div class="mt-4">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmar contraseña</label>
            <input
              id="password_confirmation"
              v-model="passwordConfirmation"
              type="password"
              class="block mt-1 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
              required
            />
          </div>

          <button
            type="submit"
            class="bg-blue-500 text-white px-4 mt-4 py-2 rounded hover:bg-blue-600"
          >
            Guardar
          </button>
        </form>
      </div>
    </div>
  </AuthenticatedMainLayout>
</template>
