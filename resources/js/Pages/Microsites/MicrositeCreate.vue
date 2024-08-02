<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import {  ClipboardTickIcon } from '@placetopay/iconsax-vue/linear';

import { SInput, SSelect, SButton, SLabel, SInputBlock, SSidebarItemGroup, SSidebar } from '@placetopay/spartan-vue';

const props = defineProps({
  categories: Array,
  documentTypes: Array,
  currencies: Array,
  micrositesTypes: Array,
});

const form = useForm({
  name: '',
  slug: '',
  category_id: '',
  document_type: '',
  document_number: '',
  logo: '',
  currency: '',
  site_type: '',
  payment_expiration: '',
  payment_fields: []
});

const errors = ref({});
const paymentFields = ref([
  {
    type: 'input',
    name: 'description',
    label: 'Dame un notica :)',
    placeholder: 'Los amo <3',
    validation: '',
    optional: true
  },
  {
    type: 'input',
    name: 'name',
    label: 'Nombre',
    placeholder: 'Juan Perez',
    validation: '',
    optional: false
  },
  {
    type: 'input',
    name: 'email',
    label: 'Correo electrónico',
    placeholder: 'juan@mail.com',
    validation: '',
    optional: false
  },
  {
    type: 'select',
    name: 'document_type',
    label: 'Tipo de documento',
    placeholder: 'juan@mail.com',
    validation: '',
    optional: false
  },
  {
    type: 'input',
    name: 'document_number',
    label: 'Número de documento',
    placeholder: '123456789',
    validation: '',
    optional: false
  },

]);

const newField = ref({
  type: 'input',
  name: '',
  label: '',
  placeholder: '',
  validation: '',
  optional: false
});

const addCustomField = () => {
  paymentFields.value.push({ ...newField.value });
  newField.value = {
    type: 'input',
    name: '',
    label: '',
    placeholder: '',
    validation: '',
    optional: false
  };
};

const removeCustomField = (index) => {
  paymentFields.value.splice(index, 1);
};

const submitForm = () => {
  form.payment_fields = paymentFields.value;
  form.post(route('microsites.store'), {
    onError: (err) => {
      console.log(err);
    },
  });
};
</script>

<template>

  <Head title="Crear micrositio" />
  <AuthenticatedMainLayout>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">Crear sitio de pago</h1>
      
      <form @submit.prevent="submitForm"
        class="border border-gray-300 max-w-lg mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow">
        <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">Configuración
          básica</SLabel>
  
        <div class="flex gap-4">
          <div>
            <SLabel for="name" class="block text-gray-700">Nombre</SLabel>
            <SInput v-model="form.name" id="name" placeholder="Nombre del micrositio" />
          </div>
          <div>
            <SLabel for="slug" class="block text-gray-700">Slug</SLabel>
            <SInput v-model="form.slug" id="slug" placeholder="Slug del micrositio" />
          </div>
        </div>
        <div class="flex gap-4">
          <div>
            <SLabel for="category_id" class="block text-gray-700">Categorías</SLabel>
            <SSelect v-model="form.category_id" id="category_id">
              <option v-for="category in props.categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </SSelect>
          </div>

          <div>
            <SLabel for="logo" class="block text-gray-700">Logo</SLabel>
            <SInput v-model="form.logo" id="logo" placeholder="URL del logo" />
          </div>
        </div>
        <div class="flex gap-4">
          <div>
            <SLabel for="currency" class="block text-gray-700">Moneda</SLabel>
            <SSelect v-model="form.currency" id="currency">
              <option v-for="currency in props.currencies" :key="currency" :value="currency">
                {{ currency }}
              </option>
            </SSelect>
          </div>

          <div>
            <SLabel for="site_type" class="block text-gray-700">Tipo de sitio</SLabel>
            <SSelect v-model="form.site_type" id="site_type">
              <option v-for="siteType in props.micrositesTypes" :key="siteType" :value="siteType">
                {{ siteType }}
              </option>
            </SSelect>
          </div>
          <div>
            <SLabel for="payment_expiration" class="text-gray-700">Expiración del pago</SLabel>
            <SInputBlock v-model="form.payment_expiration" id="payment_expiration" type="number"
              placeholder="10 minutos" />
          </div>
        </div>
        <div class="flex gap-4">
          <div>
            <SLabel for="document_type" class="block text-gray-700">Tipo de documento</SLabel>
            <SSelect v-model="form.document_type" id="document_type">
              <option v-for="documentType in props.documentTypes" :key="documentType" :value="documentType">
                {{ documentType }}
              </option>
            </SSelect>
          </div>
          <div>
            <SLabel for="document_number" class="block text-gray-700">Número de documento</SLabel>
            <SInput v-model="form.document_number" id="document_number" placeholder="Número de documento" />
          </div>
        </div>

        <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">
          Campos configurados
        </SLabel>

        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border">
            <thead>
              <tr>
                <th class="px-4 py-2 border-b">Etiqueta</th>
                <th class="px-4 py-2 border-b">Nombre</th>
                <th class="px-4 py-2 border-b">Tipo</th>
                <th class="px-4 py-2 border-b">Placeholder</th>
                <th class="px-4 py-2 border-b">Validación</th>
                <th class="px-4 py-2 border-b">Opcional</th>
                <th class="px-4 py-2 border-b">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(field, index) in paymentFields" :key="index">
                <td class="px-4 py-2 border-b">{{ field.label }}</td>
                <td class="px-4 py-2 border-b">{{ field.name }}</td>
                <td class="px-4 py-2 border-b">{{ field.type }}</td>
                <td class="px-4 py-2 border-b">{{ field.placeholder }}</td>
                <td class="px-4 py-2 border-b">{{ field.validation }}</td>
                <td class="px-4 py-2 border-b">{{ field.optional ? 'Sí' : 'No' }}</td>
                <td class="px-4 py-2 border-b">
                  <SButton @click="removeCustomField(index)" class="bg-red-500 text-white">Eliminar</SButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">
          Añadir más campos para el pagador
        </SLabel>

        <div class="border border-gray-300 p-4 rounded-lg mb-4">
          <div class="flex gap-4 mb-2">
            <div class="flex-1">
              <SLabel for="newFieldLabel" class="block text-gray-700">Etiqueta</SLabel>
              <SInput v-model="newField.label" id="newFieldLabel" placeholder="Etiqueta del campo" />
            </div>
            <div class="flex-1">
              <SLabel for="newFieldName" class="block text-gray-700">Nombre</SLabel>
              <SInput v-model="newField.name" id="newFieldName" placeholder="Nombre del campo" />
            </div>
          </div>
          <div class="flex gap-4 mb-2">
            <div class="flex-1">
              <SLabel for="newFieldType" class="block text-gray-700">Tipo</SLabel>
              <SSelect v-model="newField.type" id="newFieldType">
                <option value="select">Seleccionar</option>
                <option value="input">Ingresar</option>
                <!-- Otros tipos de campos -->
              </SSelect>
            </div>
            <div class="flex-1">
              <SLabel for="newFieldPlaceholder" class="block text-gray-700">Placeholder</SLabel>
              <SInput v-model="newField.placeholder" id="newFieldPlaceholder" placeholder="Placeholder del campo" />
            </div>
          </div>
          <div class="flex gap-4 mb-2">
            <div class="flex-1">
              <SLabel for="newFieldValidation" class="block text-gray-700">Validación</SLabel>
              <SSelect v-model="newField.validation" id="newFieldValidation">
                <option value="">Ninguna</option>
                <option value="email">Email</option>
                <option value="phone">Teléfono</option>
                <option value="url">URL</option>
                <!-- Otros tipos de validación -->
              </SSelect>
            </div>
            <div class="flex-1">
              <SLabel for="newFieldOptional" class="block text-gray-700">Opcional</SLabel>
              <input v-model="newField.optional" id="newFieldOptional" type="checkbox" />
            </div>
          </div>
          <SButton @click="addCustomField" class="bg-green-500 text-white">Añadir campo</SButton>
        </div>
        <div class="flex justify-center mt-4">
          <SButton type="submit" class="bg-blue-500 text-white">Crear micrositio</SButton>
        </div>
      </form>
    </div>
  </AuthenticatedMainLayout>
</template>

<style scoped>
.container {
  max-width: 1200px;
}

.custom-field {
  border-color: #e2e8f0;
}

.custom-field input,
.custom-field select {
  border-color: #d1d5db;
}

.custom-field input:focus,
.custom-field select:focus {
  border-color: #4a5568;
}
</style>
