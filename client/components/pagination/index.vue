<template>
    <div>
      <nav v-if="totalItems > 0" class="flex items-center justify-between px-4 py-3 bg-gray-900 border-t border-gray-600 sm:px-6 lg:mt-4" aria-label="Pagination">
        <div class="hidden sm:block">
          <p class="text-sm text-gray-200">
            Showing
            <span class="font-medium">{{ startItem }}</span>
            to
            <span class="font-medium">{{ endItem }}</span>
            of
            <span class="font-medium">{{ totalItems }}</span>
            results
          </p>
        </div>
        <div class="flex justify-between flex-1 sm:justify-end">
          <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 font-bold text-gray-100 transition-colors duration-300 ease-in-out bg-indigo-600 rounded hover:bg-indigo-500">Previous</button>
          <button @click="nextPage" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-4 py-2 ml-3 font-bold text-gray-100 transition-colors duration-300 ease-in-out bg-indigo-600 rounded hover:bg-indigo-500">Next</button>
        </div>
      </nav>
      <div v-else class="flex items-center justify-between px-4 py-3 bg-gray-900 border-t border-gray-600 sm:px-6 lg:mt-4" aria-label="Pagination">
        <div class="hidden sm:block">
          <p class="text-sm text-gray-200">
            Showing
            <span class="font-medium">{{ totalItems > 0 ? startItem : 0 }}</span>
            of
            <span class="font-medium">{{ totalItems }}</span>
            results
          </p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits, computed } from 'vue';
  
  const props = defineProps({
    totalItems: Number,
    currentPage: Number,
    itemsPerPage: Number,
  });
  
  const emits = defineEmits(['prevPage', 'nextPage']);
  
  const totalPages = computed(() => Math.ceil(props.totalItems / props.itemsPerPage));
  const startItem = computed(() => (props.currentPage - 1) * props.itemsPerPage + 1);
  const endItem = computed(() => Math.min(props.currentPage * props.itemsPerPage, props.totalItems));
  
  const prevPage = () => emits('prevPage');
  const nextPage = () => emits('nextPage');
  </script>
  