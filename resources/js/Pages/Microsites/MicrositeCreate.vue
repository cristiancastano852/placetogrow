<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';

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
  payment_retries: 1,
  retry_duration: 3,
  late_fee_percentage: '',
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
  console.log(currentStep.value);
  if (currentStep.value < 3) currentStep.value += 1;
  console.log("asdasd", currentStep.value);
  console.log(form.site_type);
};

const previousStep = () => {
  if (currentStep.value > 1) currentStep.value -= 1;
};

const showSubscriptionFields = computed(() => form.site_type === 'Subscripciones');
const showInvoicesFields = computed(() => form.site_type === 'Facturas');
const showDonationsFields = computed(() => form.site_type === 'Donaciones');

</script>

<template>

  <Head title="Crear micrositio" />
  <AuthenticatedMainLayout v-model="value">
    <div class="container mx-auto p-4 flex flex-col flex-center">
      <h1 class="text-2xl font-bold mb-4 text-center mb-8 text-orange-700">Crear sitio de pago</h1>
      <div class=" flex">
        <div class="mr-8">
          <SSteps class="flex mb-4 justify-between">
            <SStepsItem :status="currentStep === 1 ? 'current' : 'complete'" @click="currentStep = 1"
              class="flex-1 text-center">
              Paso 1
              <template #description>Configuración básica</template>
            </SStepsItem>
            <SStepsItem :status="currentStep === 2 ? 'current' : currentStep > 2 ? 'complete' : 'upcoming'"
              @click="currentStep = 2" class="flex-1 text-center">
              paso 2
              <template #description>Configurar campos</template>
            </SStepsItem>

            <SStepsItem last :status="currentStep === 3 ? 'current' : currentStep > 3 ? 'complete' : 'upcoming'"
              @click="currentStep = 3" class="flex-1 text-center">
              Paso 3
              <template #description>Configuración específica</template>
            </SStepsItem>
          </SSteps>
        </div>

        <form @submit.prevent="submitForm"
          class="border border-gray-300 w-full mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow ">
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
                <SLabel for="currency" class="block text-gray-700">Moneda</SLabel>
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
          <div v-if="currentStep === 3">
            <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">
              Configuración específica del micrositio
            </SLabel>
            <div v-if="showSubscriptionFields">
              <div class="flex gap-4 my-4">
                
                <div class="w-full">
                  <SLabel for="retry_duration" class="block text-gray-700">Intervalo entre intentos de cobro</SLabel>
                  <SSelect v-model="form.retry_duration" id="retry_duration" class="w-full">
                    <option value="6" >Cada 6 horas</option>
                    <option value="12">Cada 12 horas</option>
                    <option value="24">Cada 24 horas</option>
                  </SSelect>
                </div>
                <div class="w-full">
                  <SLabel for="payment_retries" class="block text-gray-700">Número de reintentos del pago</SLabel>
                  <SInput v-model="form.payment_retries" id="payment_retries" placeholder="Número de reintentos"/>
                </div>
              </div>
              <p>Se reinterara de nuevo el cobro cada {{ form.retry_duration }} horas, maximo {{ form.payment_retries }} veces</p>
            </div>

            <div v-if="showInvoicesFields">
              <div class="flex gap-4 mb-4">
                <div class="w-full">
                  <SLabel for="late_fee_percentage " class="block text-gray-700">Porcentaje de penalización por retraso
                  </SLabel>
                  <SInput v-model="form.payment_retries" id="late_fee_percentage " placeholder="Ej: 5%" />
                </div>
              </div>
            </div>
            <div v-if="showDonationsFields">
              <p class="text-center text-gray-700">¡Todo listo!</p>
            </div>

          </div>



          <div class="mt-4 flex justify-between">
            <SButton v-if="currentStep > 1" @click="previousStep" class="bg-gray-500 text-white">Anterior</SButton>
            <SButton v-if="currentStep < 3" @click="nextStep" class="bg-blue-500 text-white">Siguiente</SButton>
            <SButton v-if="currentStep === 3" type="submit" class="bg-blue-500 text-white">Enviar</SButton>
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