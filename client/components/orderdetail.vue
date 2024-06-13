<template>
    <div v-if="isActive" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent">
        <div class="w-3/4 max-w-3xl bg-gray-900 rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-100">Order Details</h3>
                <button @click="closeModal" class="float-right text-gray-100 hover:text-gray-600">×</button>
            </div>
            <div class="px-6 py-4">
                <template v-if="loading">
                    <Spinner :is-active="loading" />
                </template>
                <template v-else>
                    <table class="min-w-full bg-gray-900 divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Product Name</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Unit Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-100 bg-gray-900 divide-y divide-gray-200">
                            <tr v-for="detail in orderDetails" :key="detail.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Use a span to show loading state while product name is being fetched -->
                                    <span v-if="getProductById(detail.product_id) === 'Loading...'">Loading...</span>
                                    <!-- Display product name once fetched -->
                                    <span v-else>{{ getProductById(detail.product_id) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ detail.quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₱{{ detail.unit_price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₱{{ detail.total_price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from 'vue';
import Spinner from '~/components/loading/Spinner.vue';

interface OrderDetail {
    id: number;
    product_id: number;
    quantity: number;
    unit_price: number;
    total_price: number;
}

const props = defineProps({
    isActive: {
        type: Boolean,
        default: false
    },
    orderId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['close']);

const orderDetails = ref<OrderDetail[]>([]);
const loading = ref(false);
const productsMap = ref<Map<number, string>>(new Map());

const fetchOrderDetails = async (orderId: number) => {
    try {
        loading.value = true;
        const response = await fetch(`http://127.0.0.1:8000/api/OrderDetails/order/${orderId}`, {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('_token')}`
            }
        });
        if (!response.ok) {
            throw new Error('Failed to fetch order details');
        }
        const data = await response.json();
        orderDetails.value = data.data; // Assuming data structure is correct
        // Fetch product names
        await fetchProductNames(data.data);
    } catch (error) {
        console.error('Error fetching order details:', error);
    } finally {
        loading.value = false;
    }
};

const fetchProductNames = async (details: OrderDetail[]) => {
    for (const detail of details) {
        if (!productsMap.value.has(detail.product_id)) {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/products/${detail.product_id}`);
                if (response.ok) {
                    const productData = await response.json();
                    productsMap.value.set(detail.product_id, productData.name);
                } else {
                    throw new Error(`Failed to fetch product ${detail.product_id}`);
                }
            } catch (error) {
                console.error(`Error fetching product name for product ID ${detail.product_id}:`, error);
                productsMap.value.set(detail.product_id, 'Error'); // Set an error state in productsMap
            }
        }
    }
};

watch(() => props.isActive, (newValue) => {
    if (newValue) {
        fetchOrderDetails(props.orderId);
    }
});

const closeModal = () => {
    emit('close');
};

const getProductById = (productId: number) => {
    return productsMap.value.get(productId) || 'Loading...'; // Placeholder for loading state
};
</script>
