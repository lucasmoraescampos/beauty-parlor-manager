<template>
  <div class="h-screen">
    <header>
      <nav class="bg-gray-800 border-gray-200 px-4 lg:px-6 py-2.5">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
          <div class="flex items-center">
            <img src="https://flowbite.com/docs/images/logo.svg" class="mr-3 h-6 sm:h-9" alt="Flowbite Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap text-white">Flowbite</span>
          </div>
        </div>
      </nav>
    </header>

    <section class="flex items-center justify-center h-full p-4 bg-gray-800 -mt-12">
      <div class="w-full max-w-lg bg-white border border-gray-200 rounded-lg shadow">
        <div v-if="employee" class="flex flex-col items-center gap-6 p-8">
          <img class="w-32 h-32 mb-3 rounded-full shadow-lg"
            :src="employee.profile" alt="Profile" />

          <dl class="w-full text-gray-900 divide-y divide-gray-200">
            <div class="flex flex-col pb-3">
              <dt class="mb-1 text-gray-500 md:text-lg">Nome</dt>
              <dd class="text-lg font-semibold">{{ employee.name }}</dd>
            </div>
            <div class="flex flex-col py-3">
              <dt class="mb-1 text-gray-500 md:text-lg">Matrícula</dt>
              <dd class="text-lg font-semibold">{{ employee.registrationNumber }}</dd>
            </div>
            <div class="flex flex-col py-3">
              <dt class="mb-1 text-gray-500 md:text-lg">Departamento</dt>
              <dd class="text-lg font-semibold">{{ employee.department }}</dd>
            </div>
            <div class="flex flex-col pt-3">
              <dt class="mb-1 text-gray-500 md:text-lg">Cargo</dt>
              <dd class="text-lg font-semibold">{{ employee.jobTitle }}</dd>
            </div>
          </dl>

        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { onMounted, ref } from 'vue';

interface Employee {
  name: string
  registrationNumber: string
  department: string
  jobTitle: string
  profile: string
}

const props = defineProps<{
  uid: string
}>()

const employee = ref<Employee>()

axios.get(`/api/employee/${props.uid}`)
  .then(({ data }) => {
    employee.value = {
      name: data.name,
      registrationNumber: data.registration_number,
      department: data.department.name,
      jobTitle: data.job_title.name,
      profile: data.profile,
    }
  })
</script>