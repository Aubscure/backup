<template>
  <div class="w-full h-auto px-20">
    <header class="flex justify-between w-full">
      <h1 class="text-xl font-bold">Shopping Cart</h1>
      <div class="flex justify-between gap-2">
        <UButton to="/kiosk/products" label="Add more" class="w-auto rounded" icon="i-bi-cart-plus"/>
        <UButton to="/kiosk/cart/checkout" icon="i-bi-cart-check" label="Proceed to checkout" class="bg-yellow-600 rounded hover:bg-yellow-600 hover:bg-opacity-50 dark:bg-yellow-500 dark:hover:bg-yellow-500 dark:hover:bg-opacity-50" />
      </div>
    </header>
    <hr class="w-full mt-3 mb-3 border-blue-200 dark:border-blue-600">
    <div class="flex items-center justify-between w-full px-10 mb-2">
      <p>Your items</p>
      <p>Price</p>
    </div>
    <main class="grid grid-cols-1 overflow-auto max-h-[50vh] gap-1">
       <!-- Display cart items -->
       <div v-for="(item, index) in items" :key="index" class="flex items-center justify-between w-full h-auto p-2 bg-white border-blue-200 dark:border-blue-600 dark:bg-blue-900">
        <!-- Display item details -->
        <div class="flex w-auto h-auto gap-5">
          <img class="border rounded-sm w-36 h-36" :src="item.image" :alt="item.title">
          <div class="flex flex-col justify-between">
            <div class="flex flex-col gap-2 overflow-auto max-h-[14vh] max-w-[100vh]">
              <h1 class="font-bold">{{ item.title }}</h1>
              <p class="text-sm truncate text-slate-500">{{ item.description }}</p>
              <p class="text-sm truncate text-slate-500">Size: {{ item.size }}</p>
              <!-- Add any other item details you want to display -->
            </div>
            <!-- Display quantity and remove button -->
            <div class="flex items-center justify-start gap-3 text-xs font-semibold">
              <div class="flex items-center justify-start gap-x-1">
                <p class="text-blue-500">Qty:</p>
                <UInput type="number" 
                  class="w-[50px]"
                  v-model="item.quantity"
                  autocomplete="off"
                  color="gray" 
                  size="2xs" 
                  :ui="{rounded: 'rounded',color: {gray: {outline: 'dark:bg-custom-100 dark:text-custom-900'}}, icon: { trailing: {pointer: '' }}}"/>
              </div>
              <hr class="h-full border border-blue-200 dark:border-blue-600">
              <div class="flex items-center justify-start text-red-500 cursor-pointer gap-x-1 hover:opacity-50" @click="removeItem(index)">
                <UIcon name="i-bi-trash3"/>
                <p class="">Remove</p> 
              </div>
            </div>
          </div>
        </div>
        <!-- Display item price -->
        <div class="w-auto px-5 font-bold">
          {{ item.price }}
        </div>
      </div>
    </main>
    <hr class="w-full mt-5 mb-3 border-blue-200 dark:border-blue-600">
    <!-- Display subtotal -->
    <footer class="grid justify-end gap-3">
      <h1 class="text-lg"><span class="font-normal">Total ({{ items.length }} items): </span><span class="font-bold underline">${{ total }}</span></h1>
    </footer>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';

definePageMeta({
  layout: 'client'
});

// Define reactive variables
const items = ref([]);
const router = useRouter();

// Function to parse price as a number
const parsePrice = (price) => {
  return parseFloat(price.replace(/[^0-9.-]+/g, ""));
}

// Calculate total
const total = computed(() => {
  return items.value.reduce((acc, item) => acc + (parsePrice(item.price) * item.quantity), 0).toFixed(2);
});

// Function to remove item from cart
const removeItem = (index) => {
  items.value.splice(index, 1);
  updateLocalStorage();
}

// Fetch cart items from localStorage on component mount
onMounted(() => {
  const storedItems = JSON.parse(localStorage.getItem('cart')) || [];
  items.value = storedItems;
});

// Watch for changes in items and update localStorage
watch(items, () => {
  updateLocalStorage();
}, { deep: true });

// Update localStorage with current cart items
const updateLocalStorage = () => {
  localStorage.setItem('cart', JSON.stringify(items.value));
}
</script>