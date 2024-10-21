<template>

    <Head title="Crear plan" />
    <div class="container mx-auto p-4 flex flex-col flex-center">
      <h1 class="text-2xl font-bold mb-4 text-center mb-8">Edita el plan {{ form.name }}</h1>
      <div class=" flex">
        <div class="mr-8">
        </div>
        <h1></h1>
        <form @submit.prevent="submitPlans"
          class="border border-gray-300 w-full mx-auto space-y-4 bg-black/5 p-4 rounded-lg shadow ">
  
          <div>
            <SLabel class="bg-black/5 -mx-4 -mt-4 rounded-t-lg p-2 !font-bold text-center !text-lg shadow">Actualiza la
              información</SLabel>
  
            <div class="w-full p-2">
              <SLabel for="name" class="block text-gray-700">Nombre</SLabel>
              <SInput id="name" placeholder="Nombre del micrositio" v-model="form.name" />
              <SCaption :text="props.errors['plan.name']" />
            </div>
            <div class="w-full p-2">
              <SLabel for="price" class="block text-gray-700">Precio</SLabel>
              <SInput id="price" placeholder="2000" v-model="form.price" type="number">
                <template #left>
                  <div class="flex items-center">
                    <span class="font-bold">{{ microsite.currency }}</span>
                  </div>
                </template>
              </SInput>
              <SCaption :text="props.errors['plan.price']" />
  
            </div>
  
            <div class="w-full p-2">
              <SLabel for="description" class="block text-gray-700">Descripción</SLabel>
              <STextArea id="description" placeholder="Plan familiar con beneficios para 5 personas"
                v-model="form.description" />
              <SCaption :text="props.errors['plan.description']" />
            </div>
            <div class="w-full p-2">
              <SLabel for="duration_unit">Unidad de duración</SLabel>
              <SSelect class="w-full" id="duration_unit" v-model="form.duration_unit" placeholder="Seleccione un opción">
                <option v-for="unit in duration_units" :key="unit" :value="unit">{{ unit }}</option>
              </SSelect>
              <SCaption :text="props.errors['plan.duration_unit']" />
            </div>
            <div class="w-full p-2">
              <SLabel for="billing_frequency">Frecuencia del cobro</SLabel>
              <SInput id="billing_frequency" placeholder="1" v-model="form.billing_frequency" type="number"/>
              <SCaption :text="props.errors['plan.billing_frequency']" />
            </div>
            <div class="w-full p-2">
              <SLabel for="duration_period">Periodo de duración</SLabel>
              <SInput id="duration_period" v-model="form.duration_period" placeholder="12" type="number"/>
              <SCaption :text="props.errors['plan.duration_period']" />
            </div>
          </div>
          <hr class="my-8 border-t border-gray-300" />
  
          <section class="flex justify-end gap-2">
            <SButton variant="secondary" type="button" @click="returnPage">Cancelar</SButton>
            <SButton variant="primary" type="submit">Guardar</SButton>
          </section>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { Head, router } from '@inertiajs/vue3'
  import { STextArea, SButton, SInput, SLabel, SSelect, SCaption, } from '@placetopay/spartan-vue'
  
  const props = defineProps({
    microsite: {
      type: Object,
      required: true
    },
    duration_units: {
      type: Array,
      required: true
    },
    plan: {
      type: Object,
      required: true
    },
    errors: {
      type: Object,
      required: true
    }
  })
  
  const form = ref({
  name: props.plan.name || '',
  price: props.plan.price || '',
  description: props.plan.description || '',
  duration_unit: props.plan.duration_unit || '',
  billing_frequency: props.plan.billing_frequency || '',
  duration_period: props.plan.duration_period || ''
})
  
  
  const submitPlans = () => {
    router.put(route('plans.update', { microsite: props.microsite.id, plan: props.plan.id }), {
      plan: form.value
    }, {
      preserveState: true,
      preserveScroll: true
    })
  }
  
  const returnPage = () => {
    router.visit(route('plans.index', { microsite: props.microsite.id }))
  }
  </script>