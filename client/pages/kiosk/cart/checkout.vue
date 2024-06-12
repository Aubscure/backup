<template>
  <div class="w-full h-auto px-20">
    <div class="flex justify-between w-full">
      <h1 class="text-xl font-bold">Check Out</h1>
      <div class="flex justify-between gap-2">
        <UButton to="/kiosk/cart" label="Cancel" class="w-auto rounded" icon="i-bi-arrow-bar-left"/>
        <UButton @click="openModal" :label="`Purchase - $${total}`" class="bg-yellow-600 rounded hover:bg-yellow-600 hover:bg-opacity-50 dark:bg-yellow-500 dark:hover:bg-yellow-500 dark:hover:bg-opacity-50" icon="i-bi-bag-check" />
      </div>
    </div>
    <hr class="w-full mt-3 mb-5 border-blue-200 dark:border-blue-600">
    <main class="grid grid-cols-1 overflow-auto max-h-[50vh] gap-1">
      <section v-for="(item) in items" :key="item.id" class="flex items-center justify-between w-full h-auto p-2 bg-white border-blue-200 dark:border-blue-600 dark:bg-blue-900">
        <div class="flex w-auto h-auto gap-5">
          <img class="border rounded-sm w-36 h-36" :src="item.image" :alt="item.image">
          <div class="flex flex-col justify-between">
            <div class="flex flex-col gap-2 overflow-auto max-h-[14vh] max-w-[100vh]">
              <h1 class="font-bold">{{ item.title }}</h1>
              <p class="text-sm truncate text-slate-500">{{ item.description }}</p>
              <p class="text-xs">{{ item.status }}</p>
            </div>
            <div class="flex items-center justify-start gap-3 text-xs font-semibold">
              <div class="flex items-center justify-start gap-x-1">
                <p class="text-blue-500">Qty:{{ item.quantity }}</p>
              </div>
              <div class="flex items-center justify-start gap-x-1">
                <p class="text-blue-500">Size:{{ item.size }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-auto px-5 font-bold">
          {{ item.price }}
        </div>
      </section>
    </main>
    <ModalComponent :isVisible="isModalVisible" @close="closeModal" @save="handleSave" :items="items" :total="total"/>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import ModalComponent from './../../../components/Client/customer.vue';

definePageMeta({
  layout: 'client'
});

// Define reactive variables
const items = ref([]);
const isModalVisible = ref(false);

// Function to parse price as a number
const parsePrice = (price) => {
  return parseFloat(price.replace(/[^0-9.-]+/g, ""));
}

// Calculate total
const total = computed(() => {
  return items.value.reduce((acc, item) => acc + (parsePrice(item.price) * item.quantity), 0).toFixed(2);
});

// Fetch cart items from localStorage on component mount
onMounted(() => {
  const storedItems = JSON.parse(localStorage.getItem('cart')) || [];
  items.value = storedItems;
});

// Functions to control the modal
const openModal = () => {
  isModalVisible.value = true;
};

const closeModal = () => {
  isModalVisible.value = false;
};

const handleSave = (details) => {
  console.log('Details saved:', details);
  // Implement further actions after saving details, e.g., process the purchase
};
</script>
