<template>
    <div class="container p-4 mx-auto">
      <h1 class="mb-6 text-3xl font-bold text-gray-200">Orders</h1>
  
      <!-- Tabs for order status -->
      <div class="mb-4 border-b border-gray-200">
        <nav class="flex -mb-px space-x-8">
          <a href="#" 
              class="px-1 pb-4 text-sm font-medium text-gray-200 border-b-2 border-transparent whitespace-nowrap hover:border-gray-300"
              :class="{'border-indigo-500 text-indigo-500': activeTab === 'all'}"
              @click.prevent="activeTab = 'all'">All Orders</a>
          <a href="#" 
              class="px-1 pb-4 text-sm font-medium text-gray-200 border-b-2 border-transparent whitespace-nowrap hover:border-gray-300"
              :class="{'border-red-500 text-red-500': activeTab === 'pending'}"
              @click.prevent="activeTab = 'pending'">Pending</a>
          <a href="#" 
              class="px-1 pb-4 text-sm font-medium text-gray-200 border-b-2 border-transparent whitespace-nowrap hover:border-gray-300"
              :class="{'border-yellow-500 text-yellow-500': activeTab === 'processing'}"
              @click.prevent="activeTab = 'processing'">Processing</a>
          <a href="#" 
              class="px-1 pb-4 text-sm font-medium text-gray-200 border-b-2 border-transparent whitespace-nowrap hover:border-gray-300"
              :class="{'border-green-500 text-green-500': activeTab === 'completed'}"
              @click.prevent="activeTab = 'completed'">Completed</a>
        </nav>
      </div>
  
      <Spinner v-if="loading" :is-active="loading" class="min-h-60"/>
  
      <div v-else-if="totalOrders > 0">
        <table class="min-w-full divide-y divide-gray-600">
          <thead class="text-xs font-medium text-left text-gray-200 bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 tracking-wider uppercase">ID</th>
              <th scope="col" class="px-6 py-3 tracking-wider uppercase">Customer ID</th>
              <th scope="col" class="px-6 py-3 tracking-wider uppercase">Date</th>
              <th scope="col" class="px-6 py-3 tracking-wider uppercase">Total Price</th>
              <th scope="col" class="px-6 py-3 tracking-wider uppercase">Status</th>
              <th scope="col" class="px-6 py-3 tracking-wider uppercase">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-200 bg-gray-800 divide-y divide-gray-600">
            <tr v-for="order in filteredOrders" :key="order.id">
              <td class="px-6 py-4 text-sm whitespace-nowrap">{{ order.id }}</td>
              <td class="px-6 py-4 text-sm whitespace-nowrap">{{ order.customer_id }}</td>
              <td class="px-6 py-4 text-sm whitespace-nowrap">{{ order.order_date }}</td>
              <td class="px-6 py-4 text-sm whitespace-nowrap">₱{{ order.total_price }}</td>
              <td class="px-6 py-4 text-sm whitespace-nowrap">
                <span 
                :class="[
                  'font-semibold',
                  order.order_status.toLowerCase() === 'pending' ? 'text-red-500' : '',
                  order.order_status.toLowerCase() === 'processing' ? 'text-yellow-500' : '',
                  order.order_status.toLowerCase() === 'completed' ? 'text-green-500' : ''
                ]">
                {{ formattedStatus(order.order_status) }}
              </span>
              </td>
              <td class="px-6 py-4 text-sm whitespace-nowrap">
                <nuxt-link :to="`/admin/orders/${order.id}`" class="text-indigo-200 hover:text-indigo-400">View Order</nuxt-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <EmptyState />
      </div>
      <Pagination 
            :totalItems="totalOrders"
            :currentPage="state.currentPage"
            :itemsPerPage="state.itemsPerPage"
            @prevPage="previousPage"
            @nextPage="nextPage"
          />
    </div>
  </template>
  
  <script setup>
  definePageMeta({
    layout: 'admin'
  });
  
  import { ref, reactive, computed, onMounted } from 'vue'
  import EmptyState from '~/components/form/EmptyState.vue';
  import Spinner from '~/components/loading/Spinner.vue';
  
  const orders = ref([])
  const loading = ref(true)
  const activeTab = ref('all')
  
  const state = reactive({
    errors: null,
    currentPage: 1,
    itemsPerPage: 8,
  })
  
  const fetchOrders = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/orders', {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('_token')}`
        }
      })
      if (!response.ok) {
        throw new Error('Failed to fetch orders')
      }
      const data = await response.json()
      orders.value = data.data
    } catch (error) {
      console.error('Error fetching orders:', error)
    } finally {
      loading.value = false
    }
  }
  
  const filteredOrders = computed(() => {
    if (activeTab.value === 'all') {
      return orders.value
    }
    return orders.value.filter(order => order.order_status.toLowerCase() === activeTab.value)
  })
  
  const paginatedOrders = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredOrders.value.slice(start, end)
  })
  
  const totalOrders = computed(() => filteredOrders.value.length)
  const startRecord = computed(() => (currentPage.value - 1) * itemsPerPage + 1)
  const endRecord = computed(() => Math.min(currentPage.value * itemsPerPage, totalOrders.value))
  
  
  
  const formattedStatus = (status) => {
  return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
  }
  
  const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
  }
  
  const nextPage = () => {
  if (endRecord.value < totalOrders.value) {
    currentPage.value++
  }
  }
  
  onMounted(() => {
    fetchOrders()
  })
  </script>
  
  <style scoped>
  
  </style>