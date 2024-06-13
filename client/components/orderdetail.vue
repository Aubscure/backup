<template>
    <div v-if="isActive" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent">
        <div class="w-3/4 max-w-3xl bg-gray-700 rounded-lg shadow-lg shadow-gray-900">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-100">Order Details</h3>
                <button @click="closeModal" class="float-right text-gray-100 hover:text-gray-600">×</button>
            </div>
            <div class="px-6 py-4">
                <template v-if="loading">
                    <Spinner :is-active="loading" />
                </template>
                <template v-else>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Product Name</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Unit Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-100 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-100 divide-y divide-gray-200">
                            <!-- Table rows for order details -->
                            <tr v-for="detail in orderDetails" :key="detail.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ detail.product_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ detail.quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₱{{ detail.unit_price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₱{{ detail.total_price }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-end mt-4 space-x-4">
                        <!-- Cancel button (cancels order) -->
                        <button @click="confirmCancelOrder" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Cancel Order</button>
                        <!-- Paid button -->
                        <button @click="markAsPaid" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Mark as Paid</button>
                    </div>
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
        product_name: string; // Ensure product_name is defined
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
            orderDetails.value = data; // Assign directly since data is an array of objects
        } catch (error) {
            console.error('Error fetching order details:', error);
        } finally {
            loading.value = false;
        }
    };

    const updateOrderStatus = async (status: string) => {
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/orders/${props.orderId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('_token')}`
                },
                body: JSON.stringify({
                    order_status: status
                })
            });
            if (!response.ok) {
                throw new Error('Failed to update order status');
            }
            // Refresh order details after update
            await fetchOrderDetails(props.orderId);

            // If successfully marked as paid, close modal and reload page
            if (status === 'completed') {
                closeModal();
                location.reload(); // Reload the page
            }
        } catch (error) {
            console.error('Error updating order status:', error);
        }
    };

    const deleteOrder = async () => {
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/orders/${props.orderId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('_token')}`
                }
            });
            if (!response.ok) {
                throw new Error('Failed to cancel order');
            }
            closeModal(); // Close modal after successful deletion
        } catch (error) {
            console.error('Error deleting order:', error);
        }
    };

    const confirmCancelOrder = () => {
        if (window.confirm("Are you sure you want to cancel this order?")) {
            deleteOrder();
        }
    };
    
    const markAsPaid = () => {
        updateOrderStatus('completed');
    };

    const closeModal = () => {
        emit('close');
    };

    watch(() => props.isActive, (newValue) => {
        if (newValue) {
            fetchOrderDetails(props.orderId);
        }
    });
</script>
