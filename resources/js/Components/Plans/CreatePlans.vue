<template>
  <div class="container mx-auto p-4">
    <Head title="Create Plans" />
    <h1 class="text-2xl font-bold mb-4">Create Plans for Microsite {{ microsite_name }}</h1>
    
    <div class="mb-4 flex justify-between items-center">
      <button @click="addPlan" type="button" class="bg-blue-500 text-white px-4 py-2 rounded" :disabled="plans.length >= 3">
        Add New Plan
      </button>
      <span class="text-sm text-gray-600">{{ plans.length }} / 3 plans created</span>
    </div>

    <div class="h-full overflow-y-auto pr-4">
      <div class="flex flex-wrap -mx-2">
        <div v-for="(plan, index) in plans" :key="index" class="w-full md:w-1/2 lg:w-1/3 px-2 mb-4">
          <div class="border rounded p-4 h-full">
            <h2 class="text-xl font-semibold mb-2">Plan {{ index + 1 }}</h2>
            
            <div class="mb-4">
              <label class="block mb-2">Name:</label>
              <input v-model="plan.name" required class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
              <label class="block mb-2">Price:</label>
              <input v-model.number="plan.price" type="number" required class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
              <label class="block mb-2">Description:</label>
              <textarea v-model="plan.description" class="w-full p-2 border rounded"></textarea>
            </div>
            
            <div class="mb-4">
              <label class="block mb-2">Duration Unit:</label>
              <select v-model="plan.duration_unit" required class="w-full p-2 border rounded">
                <option v-for="unit in duration_units" :key="unit" :value="unit">{{ unit }}</option>
              </select>
            </div>
            
            <div class="mb-4">
              <label class="block mb-2">Billing Frequency:</label>
              <input v-model.number="plan.billing_frequency" type="number" required class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
              <label class="block mb-2">Duration Period:</label>
              <input v-model.number="plan.duration_period" type="number" required class="w-full p-2 border rounded">
            </div>
            
            <button @click="removePlan(index)" type="button" class="bg-red-500 text-white px-4 py-2 rounded">Remove Plan</button>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-4">
      <button @click="submitPlans" class="bg-green-500 text-white px-4 py-2 rounded">Submit Plans</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
  microsite_id: {
    type: Number,
    required: true
  },
  duration_units: {
    type: Array,
    required: true
  },
  microsite_name:{
      type: String,
      required: true
  },
  plans:{
      type: Object,
      required: true
  },
  errors:{
      type: Object,
      required: true
  }
})

const plans = ref([
  {
    name: '',
    price: 0,
    description: '',
    duration_unit: 'month',
    billing_frequency: 1,
    duration_period: 1
  }
])

if (props.plans.length > 0) {
  plans.value = props.plans
}

const addPlan = () => {
  if (plans.value.length < 3) {
    plans.value.push({
      name: '',
      price: 0,
      description: '',
      duration_unit: 'month',
      billing_frequency: 1,
      duration_period: 1
    })
  }
}

const removePlan = (index) => {
  if (plans.value.length > 1) {
    plans.value.splice(index, 1)
  }
}

const submitPlans = () => {
  router.post(route('plans.store', { microsite: props.microsite_id}), { plans: plans.value }, {
    preserveState: true,
    preserveScroll: true
  })
}
</script>