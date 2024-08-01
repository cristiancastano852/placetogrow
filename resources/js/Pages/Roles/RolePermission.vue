<script setup lang="ts">
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { Head } from '@inertiajs/vue3';

const { props } = usePage();
const roles = ref(props.roles.map(role => ({
  ...role,
  permissions: role.permissions || [] // Asegúrate de que permissions sea un array
})));
const permissions = ref(props.permissions);
const rolesHasPermissions = ref(props.rolesHasPermissions);
const successMessage = ref(props.success);
const errorMessage = ref(props.error);

const hasPermission = (role: any, permission: any) => {
  const roleWithPermissions = rolesHasPermissions.value.find(r => r.id === role.id);
  return roleWithPermissions ? roleWithPermissions.permissions.some(p => p.id === permission.id) : false;
};


const updatePermissions = (roleId: number, permissionId: number, checked: boolean) => {
  const role = rolesHasPermissions.value.find(r => r.id === roleId);
  console.log("role",role);
  if (role) {
    if (!role.permissions) role.permissions = []; 

    const permission = permissions.value.find(p => p.id === permissionId);
    if (!permission) return; 

    const permissionIndex = role.permissions.findIndex(p => p.id === permissionId);
    if (checked && permissionIndex === -1) {
      role.permissions.push({ id: permissionId, name: permission.name });
    } else if (!checked && permissionIndex !== -1) {
      role.permissions.splice(permissionIndex, 1);
    }
    console.log("role.permissions",role.permissions);
  }
};

const savePermissions = (roleId: number) => {
  const role = rolesHasPermissions.value.find(r => r.id === roleId);
  const permissions = role.permissions.map(p => p.name); 
  console.log("asdasdas",permissions);
  if (role) {
    router.put(route('admin.rolePermission.edit-permissions', roleId), {
      permissions: permissions
    });
  }
};

const value = ref('roles');
</script>

<template>
  <Head title="Roles" />
  <AuthenticatedMainLayout v-model="value">
    <div class="-mt-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Permisos por Rol</h3>
            <template v-if="successMessage">
              <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Éxito!</strong>
                <span class="block sm:inline">{{ successMessage }}</span>
              </div>
            </template>
            <template v-if="errorMessage">
              <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ errorMessage }}</span>
              </div>
            </template>
            <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="role in roles" :key="role.id" class="bg-gray-100 p-4 rounded-lg mb-4">
                  <h4 class="text-lg font-semibold mb-2">{{ role.name }}</h4>
                  <div class="grid grid-cols-1 gap-2">
                    <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                      <label class="inline-flex items-center cursor-pointer">
                        <input
                          type="checkbox"
                          :name="'permissions[]'"
                          :value="permission.name"
                          class="sr-only peer"
                          :checked="hasPermission(role, permission)"
                          @change="updatePermissions(role.id, permission.id, $event.target.checked)"
                        >
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ permission.name }}</span>
                      </label>
                    </div>
                  </div>
                  <button @click="savePermissions(role.id)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-4 rounded">
                    Guardar Permisos
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedMainLayout>
</template>
