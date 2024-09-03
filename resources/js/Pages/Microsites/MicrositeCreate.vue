<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ClipboardTickIcon } from '@placetopay/iconsax-vue/linear';

import { SInput, SSelect, SButton, SLabel, SInputBlock, SSteps, SStepsItem } from '@placetopay/spartan-vue';

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
const value = ref('Sitios');

const currentStep = ref(1);

const nextStep = () => {
  if (currentStep.value < 2) currentStep.value += 1;
};

const previousStep = () => {
  if (currentStep.value > 1) currentStep.value -= 1;
};

</script>

<template>

  <Head title="Crear micrositio" />
  <AuthenticatedMainLayout v-model="value">
    <div class="container mx-auto p-4 flex flex-col flex-center">
      <h1 class="text-2xl font-bold mb-4 text-center mb-8">Crear sitio de pago</h1>
      <div class=" flex">
        <div class="mr-8">
          <SSteps class="flex mb-4 justify-between">
            <SStepsItem :status="currentStep === 1 ? 'current' : 'complete'" @click="currentStep = 1"
              class="flex-1 text-center">
              Paso 1
              <template #description>Configuración básica</template>
            </SStepsItem>
            <SStepsItem last :status="currentStep === 2 ? 'current' : currentStep > 2 ? 'complete' : 'upcoming'"
              @click="currentStep = 2" class="flex-1 text-center">
              paso 2
              <template #description>Configurar campos</template>
            </SStepsItem>
          </SSteps>
        </div>

        <form @submit.prevent="submitForm"
          class="border border-gray-300 w-full mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow "
        >
          <div v-if="currentStep === 1">
            <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">Configuración
              básica</SLabel>

            <div class="flex gap-4 mb-4">
              <div class="w-full">
                <SLabel for="name" class="block text-gray-700">Nombre</SLabel>
                <SInput v-model="form.name" id="name" placeholder="Nombre del micrositio" />
              </div>
              <div class="w-full">
                <SLabel for="slug" class="block text-gray-700">Slug</SLabel>
                <SInput v-model="form.slug" id="slug" placeholder="Slug del micrositio" />
              </div>
            </div>
            <div class="flex gap-4 mb-4">
              <div class="w-full">
                <SLabel for="category_id" class="block text-gray-700">Categorías</SLabel>
                <SSelect v-model="form.category_id" id="category_id" class="w-full">
                  <option v-for="category in props.categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </SSelect>
              </div>
              <div class="w-full">
                <SLabel for="logo" class="block text-gray-700">Logo</SLabel>
                <SInput v-model="form.logo" id="logo" placeholder="URL del logo" />
              </div>
              <div class="w-full">
                <SLabel for="currency"  class="block text-gray-700">Moneda</SLabel>
                <SSelect v-model="form.currency" id="currency" class="w-full">
                  <option v-for="currency in props.currencies" :key="currency" :value="currency">
                    {{ currency }}
                  </option>
                </SSelect>
              </div>
            </div>

            <div class="flex gap-4">
              <div class="w-full">
                <SLabel for="site_type" class="block text-gray-700">Tipo de sitio</SLabel>
                <SSelect v-model="form.site_type" id="site_type" class="w-full">
                  <option v-for="siteType in props.micrositesTypes" :key="siteType" :value="siteType">
                    {{ siteType }}
                  </option>
                </SSelect>
              </div>
              <div class="w-full">
                <SLabel for="payment_expiration" class="text-gray-700">Expiración del pago</SLabel>
                <SInputBlock v-model="form.payment_expiration" id="payment_expiration" type="number"
                  placeholder="15 minutos" />
              </div>
              <div class="w-full">
                <SLabel for="document_type" class="block text-gray-700">Tipo de documento</SLabel>
                <SSelect v-model="form.document_type" id="document_type" class="w-full">
                  <option v-for="documentType in props.documentTypes" :key="documentType" :value="documentType">
                    {{ documentType }}
                  </option>
                </SSelect>
              </div>
              <div class="w-full">
                <SLabel for="document_number" class="block text-gray-700">Número de documento</SLabel>
                <SInput v-model="form.document_number" id="document_number" placeholder="Número de documento" />
              </div>
            </div>
          </div>
          <div v-if="currentStep === 2">
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
          </div>
          <div v-if="currentStep === 2" class="mt-4 flex justify-between">
            <SButton @click="previousStep" class="bg-gray-500 text-white">Anterior</SButton>
            <SButton  type="submit" class="bg-blue-500 text-white">Enviar</SButton>
          </div>
          <div class="mt-4 flex justify-end">
            <SButton v-if="currentStep === 1" @click="nextStep" class="mt-4 ">Siguiente</SButton>
          </div>
        </form>
      </div>
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
