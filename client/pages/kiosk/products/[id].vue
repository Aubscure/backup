<template>
  <ClientProductDetail
    :back-link="'/kiosk/products'"
    :back-text="'Products'"
    :product="product"
    :loading="loading"
    :addToCart="addToCart"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

definePageMeta({
  layout: 'client'
})

const router = useRouter()
const { id } = useRoute().params
const product = ref({
  id: '',
  name: '',
  description: '',
  price: 0,
  photo: '',
  category: '',
  sizes: []
})
const loading = ref(true)

const fetchProduct = async () => {
  loading.value = true
  const params = {
    Authorization: "Bearer " + localStorage.getItem('_token'),
    Accept: "application/json"
  }

  try {
    const response = await fetch(`http://127.0.0.1:8000/api/products/${id}`, {
      headers: params
    }).then(res => res.json())

    if (response.data) {
      product.value = response.data
    } else {
      throw new Error('Product not found')
    }
  } catch (error) {
    console.error('Failed to fetch product data:', error.message)
    router.push('/kiosk/products') // Navigate back to products page on error
  } finally {
    loading.value = false
  }
}

const addToCart = (productId, selectedSize) => {
  const itemToAdd = {
    id: productId,
    title: product.value.name,
    description: product.value.description,
    price: product.value.price,
    image: product.value.photo,
    size: selectedSize, // Add the selected size to the cart item
    quantity: 1 // Initial quantity
  }
  
  // Get existing cart items from localStorage
  const existingCartItems = JSON.parse(localStorage.getItem('cart')) || [];
  
  // Add the new item to the existing items
  existingCartItems.push(itemToAdd);

  console.log("Item Added", itemToAdd)
  
  // Store the updated cart items back to localStorage
  localStorage.setItem('cart', JSON.stringify(existingCartItems));
}

onMounted(fetchProduct)
</script>
