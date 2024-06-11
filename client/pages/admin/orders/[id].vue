<template>
    <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div v-if="orderDetails && orderDetails.length">
        <div class="mb-6 overflow-hidden bg-white shadow sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Order #{{ orderId }}</h3>
          </div>
          <div class="border-t border-gray-200">
            <dl class="divide-y divide-gray-200">
              <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between">
                  <div class="text-sm font-medium text-gray-500">Product ID</div>
                  <div class="text-sm font-medium text-gray-500">Quantity</div>
                  <div class="text-sm font-medium text-gray-500">Unit Price</div>
                </div>
                <div v-for="(detail, index) in displayedDetails" :key="detail.id" class="flex justify-between mt-2">
                  <div class="text-sm text-gray-900">{{ detail.product_id }}</div>
                  <div class="text-sm text-gray-900">{{ detail.quantity }}</div>
                  <div class="text-sm text-gray-900">{{ detail.unit_price }}</div>
                </div>
              </div>
            </dl>
          </div>
        </div>
        <div v-if="showAll" class="mb-6 overflow-hidden bg-white shadow sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6">
            <div v-for="(detail, index) in remainingDetails" :key="detail.id" class="flex justify-between mt-2">
              <div class="text-sm text-gray-900">{{ detail.product_id }}</div>
              <div class="text-sm text-gray-900">{{ detail.quantity }}</div>
              <div class="text-sm text-gray-900">{{ detail.unit_price }}</div>
            </div>
          </div>
        </div>
        <div class="mt-4 text-right">
          <button @click="toggleShowAll" class="text-blue-500 hover:text-blue-700 focus:outline-none">
            {{ showAll ? 'View Less' : 'View More' }}
          </button>
        </div>
        <div class="mt-4 text-right">
          <div class="text-sm font-medium text-gray-500">Subtotal:</div>
          <div class="text-sm font-medium text-gray-900">{{ subtotal }}</div>
        </div>
      </div>
      <div v-else>
        <p class="text-center text-gray-500">No order details found.</p>
      </div>
    </div>
  </template>
  
  <script setup>
  definePageMeta({
    layout: 'admin'
  });
  
  import { ref, computed } from 'vue'
  import { useRoute } from 'vue-router'
  import { useFetch } from '#app'
  
  const route = useRoute()
  const orderId = route.params.id
  
  const token = localStorage.getItem('_token')
  
  const { data: orderDetails, error } = await useFetch(`/api/OrderDetails/${orderId}`, {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json'
    }
  })
  
  if (error.value) {
    console.error('Error fetching order details:', error.value)
  }
  
  const showAll = ref(false)
  const displayedDetails = computed(() => showAll.value ? orderDetails.value : orderDetails.value.slice(0, 3))
  const remainingDetails = computed(() => orderDetails.value.slice(3))
  
  const subtotal = computed(() => {
    return orderDetails.value.reduce((sum, detail) => sum + (detail.unit_price * detail.quantity), 0)
  })
  
  const toggleShowAll = () => {
    showAll.value = !showAll.value
  }
  </script>
  
  <style scoped>
  /* Add styles for the container */
  .order-container {
    max-height: 200px;
    overflow-y: hidden;
    position: relative;
  }
  
  .order-container::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50px;
    background: linear-gradient(transparent, white);
    display: block;
    pointer-events: none;
  }
  </style>
  