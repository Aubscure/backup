<template>
  <div class="container p-4 mx-auto bg-gray-800 rounded-lg shadow-md shadow-gray-950">
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
            :class="{'border-green-500 text-green-500': activeTab === 'completed'}"
            @click.prevent="activeTab = 'completed'">Completed</a>
      </nav>
    </div>

    <Spinner v-if="loading" :is-active="loading" class="min-h-60"/>

    <div v-else-if="totalOrders > 0">
      <table class="min-w-full overflow-hidden divide-y divide-gray-600 rounded-lg shadow-md shadow-gray-900">
        <thead class="text-lg text-left text-gray-200 bg-gray-700 bg-opacity-50">
          <tr class="">
            <th scope="col" class="px-6 py-6 tracking-wider uppercase">Customer Name</th>
            <th scope="col" class="px-6 py-6 tracking-wider uppercase">Date</th>
            <th scope="col" class="px-6 py-6 tracking-wider uppercase">Total Price</th>
            <th scope="col" class="px-6 py-6 tracking-wider uppercase">Status</th>
            <th scope="col" class="px-6 py-6 tracking-wider uppercase">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-200 bg-gray-700 bg-opacity-50 divide-y divide-gray-600 ">
          <tr v-for="order in paginatedOrders" :key="order.id">
            <td class="px-6 py-3 text-lg whitespace-nowrap">{{ getCustomerName(order.customer_id) }}</td>
            <td class="px-6 py-3 text-sm whitespace-nowrap">{{ order.order_date }}</td>
            <td class="px-6 py-3 text-sm whitespace-nowrap">₱{{ order.total_price }}</td>
            <td class="px-6 py-3 text-sm whitespace-nowrap">
              <span 
                :class="[
                  'font-semibold',
                  order.order_status.toLowerCase() === 'pending' ? 'text-red-500' : '',
                  order.order_status.toLowerCase() === 'completed' ? 'text-green-500' : ''
                ]">
                {{ formattedStatus(order.order_status) }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm whitespace-nowrap">
              <button @click="viewOrderDetails(order.id)" class="text-indigo-200 hover:text-indigo-400">View Order</button>
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
    <OrderDetailsModal 
      :isActive="isModalActive" 
      :orderId="selectedOrderId" 
      @close="isModalActive = false"
      class="fixed inset-0 z-50 flex items-center justify-center"
    />
  </div>
</template>


<script setup>
  definePageMeta({
    layout: 'admin'
  });
import { ref, reactive, computed, onMounted } from 'vue';
import EmptyState from '~/components/form/EmptyState.vue';
import Spinner from '~/components/loading/Spinner.vue';
import OrderDetailsModal from '~/components/orderdetail.vue';

const orders = ref([]);
const customers = ref([]);
const loading = ref(true);
const activeTab = ref('all');
const isModalActive = ref(false);
const selectedOrderId = ref(null);

const state = reactive({
  errors: null,
  currentPage: 1,
  itemsPerPage: 8,
});

const fetchOrders = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/orders', {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('_token')}`
      }
    });
    if (!response.ok) {
      throw new Error('Failed to fetch orders');
    }
    const data = await response.json();
    orders.value = data.data;
  } catch (error) {
    console.error('Error fetching orders:', error);
  } finally {
    loading.value = false;
  }
};

const fetchCustomers = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/customers', {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('_token')}`
      }
    });
    if (!response.ok) {
      throw new Error('Failed to fetch customers');
    }
    const data = await response.json();
    customers.value = data.data;
    console.log(customers.value); // Add this line to log customer data
  } catch (error) {
    console.error('Error fetching customers:', error);
  } finally {
    loading.value = false;
  }
};

const filteredOrders = computed(() => {
  if (activeTab.value === 'all') {
    return orders.value;
  }
  return orders.value.filter(order => order.order_status.toLowerCase() === activeTab.value);
});

const paginatedOrders = computed(() => {
  const start = (state.currentPage - 1) * state.itemsPerPage;
  const end = start + state.itemsPerPage;
  return filteredOrders.value.slice(start, end);
});

const totalOrders = computed(() => filteredOrders.value.length);
const startRecord = computed(() => (state.currentPage - 1) * state.itemsPerPage + 1);
const endRecord = computed(() => Math.min(state.currentPage * state.itemsPerPage, totalOrders.value));

const formattedStatus = (status) => {
  return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
};

const getCustomerName = (customerId) => {
  const customer = customers.value.find(c => c.id === customerId);
  if (customer) {
    return `${customer.first_name} ${customer.last_name}`;
  } else {
    return 'Unknown';
  }
};

const previousPage = () => {
  if (state.currentPage > 1) {
    state.currentPage--;
  }
};

const nextPage = () => {
  if (endRecord.value < totalOrders.value) {
    state.currentPage++;
  }
};

const viewOrderDetails = (orderId) => {
  selectedOrderId.value = orderId;
  isModalActive.value = true;
};

onMounted(() => {
  fetchOrders();
  fetchCustomers();
});
</script>

