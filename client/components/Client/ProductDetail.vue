<template>
  <div v-if="loading" class="flex items-center justify-center">
    <UIcon name="i-bi-arrow-clockwise" class="text-4xl animate-spin" />
  </div>
  <div v-else class="grid items-center justify-center w-3/4 gap-5 m-auto">
    <nuxt-link :to="backLink" class="flex items-center justify-start gap-1 hover:opacity-50 w-fit">
      <UIcon name="i-bi-arrow-bar-left"/>
      <p>Back to {{ backText }}</p>
    </nuxt-link>

    <div class="flex items-center justify-center w-full h-auto bg-blue-200 rounded-lg dark:bg-blue-800">
      <img :src="product.photo" class="w-[500px] h-[500px] rounded-l-lg border p-5 bg-white"/>

      <div class="flex flex-col gap-3 p-10">
        <h1 class="text-xl font-semibold">{{ product.name }}</h1>
        <p class="text-sm tracking-wider">{{ product.description }}</p>
        <p class="text-sm tracking-wider">{{ product.category }}</p>
        <h1 class="text-2xl font-black">${{ product.price }}</h1>

        <!-- Radio buttons for sizes -->
        <div class="mt-5">
          <h3 class="text-lg font-semibold">Select Size:</h3>
          <div class="flex flex-col gap-2">
            <label v-for="size in product.sizes" :key="size.size" class="flex items-center gap-2">
              <input type="radio" :value="size.size" v-model="selectedSize" />
              <span>{{ size.size }} ({{ size.quantity }} available)</span>
            </label>
          </div>
        </div>

        <div class="flex flex-row gap-3 mt-5">
          <UButton 
            class="bg-yellow-600 rounded hover:bg-yellow-600 hover:bg-opacity-50 dark:bg-yellow-200 dark:hover:bg-yellow-300" 
            :disabled="!selectedSize" 
            label="Add to Cart" 
            icon="i-bi-cart-plus"
            @click="addToCart(product.id, selectedSize)"
          />

          <UButton 
            class="rounded" 
            :disabled="!selectedSize" 
            label="Buy now" 
            icon="i-bi-cart-check" 
            trailing
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  backLink: {
    type: String,
    required: true
  },
  backText: {
    type: String,
    required: true
  },
  product: {
    type: Object,
    required: true,
    default: () => ({
      id: '',
      name: '',
      description: '',
      price: 0,
      photo: '',
      category: '',
      sizes: []
    })
  },
  loading: {
    type: Boolean,
    required: true
  },
  addToCart: {
    type: Function,
    required: true
  }
});

const selectedSize = ref(null);

</script>
