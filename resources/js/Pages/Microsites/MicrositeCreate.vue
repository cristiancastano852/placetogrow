<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { SInput, SSelect, SButton, SLabel, SInputBlock } from '@placetopay/spartan-vue';

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
const paymentFields = ref([]);

const addCustomField = () => {
  paymentFields.value.push({
    type: 'text',
    name: '',
    label: '',
    placeholder: '',
    validation: '',
    optional: false
  });
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
        class="border border-gray-300 max-w-md mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow">
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

        <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">Añadir más campos
        </SLabel>

        <div v-for="(field, index) in paymentFields" :key="index" class="border border-gray-300 p-4 rounded-lg mb-4">
          <div class="flex gap-4 mb-2">
            <div class="flex-1">
              <SLabel for="label" class="block text-gray-700">Etiqueta</SLabel>
              <SInput v-model="field.label" id="label" placeholder="Etiqueta del campo" />
            </div>
            <div class="flex-1">
              <SLabel for="name" class="block text-gray-700">Nombre</SLabel>
              <SInput v-model="field.name" id="name" placeholder="Nombre del campo" />
            </div>
          </div>
          <div class="flex gap-4 mb-2">
            <div class="flex-1">
              <SLabel for="type" class="block text-gray-700">Tipo</SLabel>
              <SSelect v-model="field.type" id="type">
                <option value="select">Seleccionar</option>
                <option value="input">Ingresar</option>
                <!-- Otros tipos de campos -->
              </SSelect>
            </div>
            <div class="flex-1">
              <SLabel for="placeholder" class="block text-gray-700">Placeholder</SLabel>
              <SInput v-model="field.placeholder" id="placeholder" placeholder="Placeholder del campo" />
            </div>
          </div>
          <div class="flex gap-4 mb-2">
            <div class="flex-1">
              <SLabel for="validation" class="block text-gray-700">Validación</SLabel>
              <SSelect v-model="field.validation" id="validation">
                <option value="">Ninguna</option>
                <option value="email">Email</option>
                <option value="phone">Teléfono</option>
                <option value="url">URL</option>
                <!-- Otros tipos de validación -->
              </SSelect>
            </div>
            <div class="flex-1">
              <SLabel for="optional" class="block text-gray-700">Opcional</SLabel>
              <input v-model="field.optional" id="optional" type="checkbox" />
            </div>
          </div>
          <SButton @click="removeCustomField(index)" class="bg-red-500 text-white">Eliminar campo</SButton>
        </div>

        <SButton @click="addCustomField" class="w-full bg-green-500 text-white">Añadir campo</SButton>
        <SButton type="submit" class="w-full mt-4 bg-blue-500 text-white">Crear micrositio</SButton>
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
