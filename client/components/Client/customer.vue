<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
      <div class="p-6 bg-white rounded w-96">
        <h2 class="mb-4 text-lg font-bold">Enter Your Details</h2>
        <form @submit.prevent="saveOrder">
          <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="firstname">First Name</label>
            <input v-model="firstname" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="firstname" type="text" placeholder="First Name">
          </div>
          <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="lastname">Last Name</label>
            <input v-model="lastname" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="lastname" type="text" placeholder="Last Name">
          </div>
          <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="studentnumber">Student Number</label>
            <input v-model="studentnumber" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="studentnumber" type="text" placeholder="Student Number">
          </div>
          <div class="mb-4">
            <h3 class="mb-2 text-sm font-bold text-gray-700">Order Summary</h3>
            <ul>
              <li v-for="(item, index) in items" :key="index">
                <span class="font-semibold">{{ item.title }}</span> - Qty: {{ item.quantity }} - Total: ${{ (parsePrice(item.price) * item.quantity).toFixed(2) }}
              </li>
            </ul>
            <p class="mt-2 font-bold">Grand Total: ${{ total }}</p>
          </div>
          <div class="flex justify-end gap-2">
            <button @click="closeModal" class="px-4 py-2 font-bold text-gray-800 bg-gray-300 rounded focus:outline-none focus:shadow-outline" type="button">Cancel</button>
            <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, defineProps, defineEmits, computed } from 'vue';
  
  const props = defineProps({
    isVisible: Boolean,
    items: Array,
  });
  
  const emit = defineEmits(['close', 'save']);
  
  const firstname = ref('');
  const lastname = ref('');
  const studentnumber = ref('');
  
  const closeModal = () => {
    emit('close');
  };
  
  const parsePrice = (price) => {
    return parseFloat(price.replace(/[^0-9.-]+/g, ""));
  };
  
  // Define computed property to calculate total
  const total = computed(() => {
    let sum = 0;
    for (const item of props.items) {
      sum += parsePrice(item.price) * item.quantity;
    }
    return sum.toFixed(2);
  });
  
  const saveOrder = async () => {
  try {
    const formData = new FormData();
    formData.append('customer_first_name', firstname.value);
    formData.append('customer_last_name', lastname.value);
    formData.append('customer_id_number', studentnumber.value);
    formData.append('total_price', total.value); // Use the computed total

    const response = await fetch('http://127.0.0.1:8000/api/orders', {
      method: 'POST',
      headers: {
        Authorization: "Bearer " + localStorage.getItem('_token'), // Include bearer token here
      },
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Error saving order');
    }

    // Emit event to inform the parent component to reset the form
    emit('save');
  } catch (error) {
    console.error('Error saving order:', error.message);
    // Handle error, show notification, etc.
  }
};

  </script>
  