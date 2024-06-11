<template>

  <div class="w-full h-auto">
    <ClientCollegeCategories />

    <div class="px-20">
      <p class="mb-10 text-center uppercase">dapat diri naka display tanan products sa tanan department</p>
      <section class="grid w-full grid-cols-4 gap-4 text-blue-800 dark:text-blue-900">
        <nuxt-link v-for="(product, index) in products" :to="`/kiosk/college/${product.id}`" :key="index" class="flex flex-col justify-between gap-2 p-3 bg-white border rounded">
            <img :src="product.image" alt="img" class="w-auto h-24 mx-auto">
            <p class="text-sm text-gray-500">{{ product.title }}</p>
            <h1 class="mx-auto font-semibold">${{ product.price }}</h1>
          </nuxt-link>
      </section>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'client-college'
});

const { data: products } = await useFetch('https://fakestoreapi.com/products');
</script>