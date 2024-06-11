<template>
  <ClientProductDetail
    :back-link="'/kiosk/products'"
    :back-text="'Products'"
    :product="product"
    :loading="loading"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

definePageMeta({
  layout: 'client'
})

const route = useRoute()
const product = ref({
  id: '',
  title: '',
  description: '',
  price: 0,
  photo: ''
})
const loading = ref(true)
const router = useRouter()
const { id } = route.params

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

onMounted(fetchProduct)
</script>
