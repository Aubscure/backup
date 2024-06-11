<template>
    <div class="min-h-full space-y-4 text-center text-gray-100">
        <div class="flex justify-center">
            <FaceFrownIcon class="flex justify-center text-red-600 h-44 w-44" aria-hidden="true"/>
        </div>
        <h3 class="text-xl font-semibold">{{ title }}</h3>
        <p class="text-gray-400">{{ message }}</p>
    </div>
  </template>
  
  <script setup>
  import { FaceFrownIcon } from '@heroicons/vue/24/outline'
  
  const props = defineProps({
    title: {
      type: String,
      default: "No data found"
    },
    message: {
      type: String,
      default: "We couldn't find any data at the moment."
    }
  });
  </script>