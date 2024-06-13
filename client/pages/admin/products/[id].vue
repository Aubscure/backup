<template>
  <div class="">
    <div class="">

      <Notification :show="show" :success="success" :errorMessage="state.errors" successMessage="Product successfully saved!" @update:show="show = $event" />

      <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl font-bold text-gray-200">Update Product</h1>
        <nuxt-link to="/admin/products" class="px-4 py-2 font-bold text-white transition-colors duration-300 ease-in-out bg-indigo-600 rounded hover:bg-indigo-500">Go back</nuxt-link>
      </div>
      
      <div v-if="state.isLoading" class="flex items-center justify-center min-h-40">
        <Spinner :is-active="true" />
      </div>
      
      <div v-else-if="!state.isLoading" class="flex justify-between space-x-10">
        <div id="preview box" class="w-11/12 p-6 px-10 bg-gray-800 rounded-lg ring-1 ring-gray-700">
          <div v-if="state.products" class="flex flex-col h-auto p-4 space-y-10">
            <div class="flex justify-center w-auto h-96">
              <img :src="previewProductData.photo || photoURL" alt="Product Photo" class="object-contain"/>
            </div>
            <div class="h-auto space-y-2 text-gray-200">
              <div class="flex justify-between text-4xl font-semibold space-x-44">
                <div class="text-wrap">{{ previewProductData.name || state.data.name }}</div>
                <div class="flex items-start">{{ previewProductData.price || state.data.price }}</div>
              </div>
              <div class="text-lg font-light text-justify">{{ previewProductData.description || state.data.description }}</div>
            </div>
          </div>
        </div>
        <div id="input box" class="bg-gray-800 rounded-lg ring-1 ring-gray-700">
          <div v-if="state.products" class="flex flex-col h-auto p-4 space-y-5">
            <input v-model="state.data.name" @input="updatePreview" type="text" name="product name" class="h-10 px-3 py-2 rounded-lg" :placeholder="state.data.name">
            <textarea v-model="state.data.description" @input="updatePreview" name="description" cols="30" rows="10" class="px-3 py-2 rounded-lg h-28" :placeholder="state.data.description"></textarea>
            <div class="flex justify-center space-x-4">
              <input v-model.number="state.data.price" @input="updatePreview" type="number" :placeholder="state.data.price" class="p-2 border-2 rounded-lg input hover:border-gray-700" />
              <select v-model="state.data.category" class="p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700">
                <option value="" disabled selected>Select Category</option>
                <option value="HM">HM</option>
                <option value="TM">TM</option>
                <option value="IT">IT</option>
              </select>
            </div>
            <!-- Sizes and Quantities -->
            <div v-for="(size, index) in state.data.sizes" :key="index" class="flex space-x-2">
              <select v-model="size.size" class="p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700">
                <!-- Options for sizes -->
                <option value="" disabled selected>Select Size</option>
                <option value="XSM">XSM</option>
                <option value="SM">SM</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
              </select>
              <input type="number" v-model="size.quantity" placeholder="Quantity" class="p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700" />
              <button type="button" @click="removeSize(index)" class="p-2 text-white bg-red-600 rounded-lg btn hover:bg-red-500">Remove</button>
            </div>
            <button type="button" @click="addSize" class="p-2 text-white bg-green-600 rounded-lg btn hover:bg-green-500">Add Size</button>
            <!-- End Sizes and Quantities -->
            <!-- Modern drag-and-drop file input -->
            <div 
              @dragover.prevent 
              @dragenter.prevent 
              @drop.prevent="handleFileDrop" 
              @click="triggerFileInput"
              class="relative flex items-center justify-center p-6 border-2 border-gray-700 border-dashed rounded-lg cursor-pointer h-60 input hover:border-gray-200"
            >
              <span v-if="!state.data.photo" class="text-gray-400">Drag and drop a photo here, or click to select</span>
              <input type="file" @change="handleFileChange" class="hidden" ref="fileInput" />
              <img v-if="state.data.photo" :src="previewProductData.photo || photoURL" alt="Photo Preview" class="absolute inset-0 object-contain w-full h-full rounded-lg" />
            </div>
            <p class="self-center text-white">Image size up to 2MB only</p>
            <div class="space-y-5">
              <button @click="uploadData" class="w-full px-2 py-2 text-gray-100 transition-colors duration-300 ease-in-out bg-indigo-600 rounded-lg hover:bg-indigo-500 hover:text-gray-100">Save changes</button>
              <button @click="cancelUpdate" class="w-full p-2 text-white bg-indigo-900 bg-opacity-50 rounded-lg hover:ring-1">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
    layout: 'admin'
  });
import { reactive, onMounted, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import Spinner from '~/components/loading/Spinner.vue';
import Notification from '~/components/form/Notification.vue';

const router = useRouter()
const show = ref(false);
const success = ref(false);

const state = reactive({
  id: null,
  products: null,
  errors: null,
  isLoading: false,
  data: {
    name: null,
    description: null,
    price: null,
    photo: null,
    category: null,
    sizes: [{ size: '', quantity: '' }]
  }
})

const fileInput = ref(null)

const photoURL = computed(() => {
  if (state.data.photo && state.data.photo instanceof File) {
    return URL.createObjectURL(state.data.photo);
  }
  return null;
});

function handleFileChange(event) {
  const file = event.target.files[0]
  state.data.photo = file
  updatePreview()
}

function handleFileDrop(event) {
  const file = event.dataTransfer.files[0]
  state.data.photo = file
  updatePreview()
}

function triggerFileInput() {
  fileInput.value.click();
}
const addSize = () => {
  state.data.sizes.push({ size: '', quantity: '' });
}

const removeSize = (index) => {
  state.data.sizes.splice(index, 1);
}

async function fetchProductDetails(id) {
  state.isLoading = true;
  const params = {
    Authorization: "Bearer " + localStorage.getItem('_token'),
    Accept: "application/json"
  }

  try {
    const response = await fetch(`http://127.0.0.1:8000/api/products/${id}`, {
      headers: params
    }).then(res => res.json())

    state.data = response.data
    previewProductData.value = { ...response.data }
    state.products = true
  } catch (error) {
    state.errors = error.message;
    success.value = false; 
    show.value = true; 
    setTimeout(() => show.value = false, 1500);
    console.error('Error:', error)

    router.push('/')
  } finally {
    state.isLoading = false;
  }
}

onMounted(() => {
  state.id = router.currentRoute.value.params.id
  fetchProductDetails(state.id)
  // Clear photo input when component mounts
  state.data.photo = null;
})

const previewProductData = ref({ ...state.data })

const updatePreview = () => {
  previewProductData.value = {
    name: state.data.name,
    description: state.data.description,
    price: state.data.price,
    category: state.data.category,
    photo: state.data.photo ? (state.data.photo instanceof File ? URL.createObjectURL(state.data.photo) : state.data.photo) : null
  };
};

const updateProduct = async () => {
  try {
    // Update non-photo fields
    const response = await fetch(`http://127.0.0.1:8000/api/products/${state.id}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Authorization: "Bearer " + localStorage.getItem('_token')
      },
      body: JSON.stringify({
        name: state.data.name,
        description: state.data.description,
        price: state.data.price,
        category: state.data.category,
        sizes: state.data.sizes
      })
    })

    if (!response.ok) {
      throw new Error('Failed to update product')
    }

    success.value = true; 
    show.value = true; 
    setTimeout(() => {
      show.value = false;
      router.push('/admin/products')
    }, 1500);
    console.log('Product updated:', await response.json())
  } catch (error) {
    state.errors = error.message;
    success.value = false; 
    show.value = true; 
    setTimeout(() => show.value = false, 1500);
    console.error('Error updating product:', error)
  }
}

const updatePhoto = async () => {
  try {
    // Submit photo using POST request
    const formData = new FormData()
    formData.append('photo', state.data.photo)

    const response = await fetch(`http://127.0.0.1:8000/api/products/${state.id}/photo`, {
      method: 'POST',
      headers: {
        Authorization: "Bearer " + localStorage.getItem('_token')
      },
      body: formData
    })

    if (!response.ok) {
      throw new Error('Failed to update photo')
    }
    
    console.log('Photo updated:', await response.json())
    window.location.reload();
  } catch (error) {
    state.errors = error.message;
    success.value = false; 
    show.value = true; 
    setTimeout(() => show.value = false, 1500);
    console.error('Error updating photo:', error)
  }
}

const cancelUpdate = () => {
  router.push('/admin/products');
}

const uploadData = async () => {
  await updateProduct();
  if (state.data.photo instanceof File) {
    await updatePhoto();
  }
}
</script>