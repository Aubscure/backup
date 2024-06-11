<template>
    <div aria-live="assertive" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none md:inset-10 lg:inset-10 sm:items-start sm:p-6">
      <div class="flex flex-col items-center w-full space-y-4 sm:items-end">
        <transition enter-active-class="transition duration-300 ease-out transform"
                    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
          <div v-if="show" class="w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5">
            <div class="p-4">
              <div class="flex items-start">
                <div v-if="success" class="flex-shrink-0">
                  <CheckCircleIcon class="w-6 h-6 text-green-400" aria-hidden="true" />
                </div>
                <div v-else class="flex-shrink-0">
                  <ExclamationCircleIcon class="w-6 h-6 text-red-400" aria-hidden="true" />
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                  <p v-if="success" class="text-sm font-medium text-green-500">{{ successMessage }}</p>
                  <p v-else class="text-sm font-medium text-red-500">{{ errorMessage }}</p>
                </div>
                <div class="flex flex-shrink-0 ml-4">
                  <button type="button" @click="show = false" class="inline-flex text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <XMarkIcon class="w-5 h-5" aria-hidden="true" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline';
  import { XMarkIcon } from '@heroicons/vue/20/solid';
  
  const props = defineProps({
    show: Boolean,
    success: Boolean,
    successMessage: {
      type: String,
      default: "Action successfully completed!"
    },
    errorMessage: {
      type: String,
      default: "An error occurred!"
    }
  });
  </script>
  
  <style scoped>
  /* Add your custom styles here if needed */
  </style>
  