<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedMainLayout from '@/Layouts/AuthenticatedMainLayout.vue';
import { ClipboardTickIcon } from '@placetopay/iconsax-vue/linear';
import { SInput, SSelect, SButton, SLabel, SInputBlock, SSteps, SStepsItem } from '@placetopay/spartan-vue';
import EditPlans from '@/Components/Plans/EditPlans.vue';

const props = defineProps({
    microsite_id: {
        type: Number,
        required: true
    },
    plan: {
        type: Object,
        required: true
    },
    microsite_name: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        required: true
    }
});


</script>

<template>

    <Head title="Crear micrositio" />
    <AuthenticatedMainLayout v-model="value">
        <div class="container mx-auto p-4">
            <div>
                <EditPlans :microsite_id="microsite_id"
                :microsite_name="microsite_name"
                :plan="plan"
                :errors="errors"
                
                />
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
