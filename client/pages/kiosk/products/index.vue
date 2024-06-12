<template>
  <div class="w-full h-auto">
    <div v-if="loading" class="flex items-center justify-center">
      <UIcon name="i-bi-arrow-clockwise" class="text-4xl animate-spin" />
    </div>
    <section v-else class="flex flex-col">
      <div class="flex flex-col gap-5">
        <section class="flex items-center gap-2">
          <hr class="w-1/12 border-blue-200 dark:border-blue-800">
          <h1 class="text-base font-bold">Highlights</h1>
          <hr class="w-full border-blue-200 dark:border-blue-800">
        </section>
        <section class="grid w-full grid-cols-4 gap-4 px-20 text-blue-800 dark:text-blue-900">
          <nuxt-link v-for="(product, index) in products" :to="`/kiosk/products/${product.id}`" :key="index" class="flex flex-col justify-between gap-2 p-3 bg-white border rounded">
              <img :src="getFullImageUrl(product.photo)" alt="img" class="w-auto h-24 mx-auto">
              <p class="text-sm text-gray-500">{{ product.name }}</p>
              <p class="text-sm text-gray-500">{{ product.description }}</p>
              <p class="text-sm text-gray-500">{{ product.category }}</p>
              <h1 class="mx-auto font-semibold">${{ product.price }}</h1>
              <h3 class="font-bold">Sizes and Quantities:</h3>
                <ul>
                  <li v-for="size in product.sizes" :key="size.size" class="text-gray-400">
                    {{ size.size }}: {{ size.quantity }}
                  </li>
                </ul>
            </nuxt-link>
        </section>
      </div>
      
      <div class="w-full h-auto py-10 mt-20 text-blue-900 bg-white dark:bg-blue-900">
        <h1 class="px-20 text-xl font-bold text-blue-900 dark:text-white">SHOP BY</h1>
        <section class="flex items-center gap-2">
          <hr class="w-1/12 border-blue-200 dark:border-blue-700">
          <h1 class="text-base font-bold dark:text-white">Programs</h1>
          <hr class="w-full border-blue-200 dark:border-blue-700">
        </section>
        <div class="grid h-full gap-5 px-20 mt-5 lg:flex">
          <section class="flex flex-col justify-between w-full h-full gap-2 p-10 bg-yellow-200 rounded">
            <img class="w-auto h-auto rounded-t" src="~/assets/img/shs-2.jpg" alt="shs">
            <div class="flex items-center justify-between">
              <h1 class="font-semibold">Senior High Merch</h1>
              <UButton to="/kiosk/shs" class="flex items-center bg-yellow-600 rounded hover:opacity-50 gap-x-1 hover:bg-yellow-600 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-600 dark:hover:bg-opacity-50">
                <p class="text-sm">View Products</p>
                <UIcon name="i-bi-arrow-bar-right" class="text-lg"/>
              </UButton>
            </div>
          </section>
          <section class="flex flex-col justify-between w-full h-full gap-2 p-10 bg-blue-300 rounded">
            <img class="w-auto h-auto rounded-t" src="~/assets/img/college-1.jpg" alt="college">
            <div class="flex items-center justify-between">
              <h1 class="font-semibold">College Merch</h1>
              <UButton to="/kiosk/college" class="flex items-center bg-blue-500 rounded hover:opacity-50 gap-x-1 hover:bg-blue-500 dark:text-white dark:bg-blue-500 dark:hover:bg-blue-500 dark:hover:bg-opacity-50">
                <p class="text-sm">View Products</p>
                <UIcon name="i-bi-arrow-bar-right" class="text-lg"/>
              </UButton>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>
</template>




<script setup>
import { ref, onMounted } from 'vue';

definePageMeta({
  layout: 'client'
});

const products = ref([]);
const loading = ref(true);
const baseUrl = 'http://127.0.0.1:8000/';  // Base URL of your Laravel app

async function fetchProducts() {
  const headers = {
    Authorization: "Bearer " + localStorage.getItem('_token'),
    Accept: "application/json"
  };

  try {
    const response = await $fetch('http://127.0.0.1:8000/api/products', {
      method: 'GET',
      headers: headers
    });
    
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
  } finally {
    loading.value = false;
  }
}

function getFullImageUrl(photo) {
  return baseUrl + photo;
}

onMounted(() => {
  fetchProducts();
});

</script>

