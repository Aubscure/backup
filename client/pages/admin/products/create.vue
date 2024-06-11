<template>
  <div>
    <div class="flex items-center justify-between mb-10">
      <h1 class="text-3xl font-bold text-gray-200">Create Product</h1>
      <nuxt-link to="/admin/products" class="px-4 py-2 font-bold text-white transition-colors duration-300 ease-in-out bg-indigo-600 rounded hover:bg-indigo-500">Go back</nuxt-link>
    </div>

    <Notification :show="show" :success="success" :errorMessage="state.errors" successMessage="Product successfully saved!" />
    
    <div class="flex justify-center w-full my-36">
      <div class="flex items-center justify-center w-10/12 h-full p-10 bg-gray-800 shadow-md ring-1 shadow-gray-700 ring-gray-700 rounded-xl">
        <div>
          <form @submit.prevent="handleSubmit" class="flex justify-center space-x-10 flex-col-2">
            <div class="flex flex-col self-center w-[800px] px-10 space-y-5">
              <!-- Modern drag-and-drop file input -->
              <div 
                @dragover.prevent 
                @dragenter.prevent 
                @drop.prevent="handleFileDrop" 
                @click="triggerFileInput"
                class="relative flex items-center justify-center object-contain p-6 border-2 border-gray-700 border-dashed rounded-lg cursor-pointer h-96 input hover:border-gray-200"
              >
                <span v-if="!state.data.photo" class="text-gray-400">Drag and drop a photo here, or click to select</span>
                <input type="file" @change="handleFileChange" class="hidden" ref="fileInput" />
                <img v-if="state.data.photo" :src="photoURL" alt="Photo Preview" class="absolute inset-0 object-contain w-full h-full rounded-lg" />
              </div>
              <p class="self-center text-white">Image size up to 2MB only</p>
            </div>
            <div class="grid content-between grid-col w-96">
              <input 
                type="text" 
                placeholder="Name" 
                class="p-2 border-2 border-gray-400 rounded-lg textb input hover:border-gray-700" 
                v-model="state.data.name" 
              />
              <textarea 
                placeholder="Description" 
                class="h-40 p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700" 
                v-model="state.data.description">
              </textarea>
              <input 
                type="number" 
                placeholder="Quantity" 
                class="p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700" 
                v-model="state.data.quantity" 
              />
              <input 
                type="number" 
                placeholder="Price" 
                class="p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700" 
                v-model="state.data.price" 
              />

                            <!-- Dropdown menu for category selection -->
              <select v-model="state.data.category" class="p-2 border-2 border-gray-400 rounded-lg input hover:border-gray-700">
                <option value="" disabled selected>Select Category</option>
                <option value="HM">HM</option>
                <option value="TM">TM</option>
                <option value="IT">IT</option>
              </select>

              <button 
                type="submit" 
                class="p-2 text-white bg-indigo-600 rounded-lg btn hover:bg-indigo-600"
                :disabled="state.loading"
              >
                <span v-if="state.loading">Submitting...</span>
                <span v-else>Submit Product</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  
</template>


<script setup>
definePageMeta({
    layout: 'admin'
  });
  
import { ref, reactive, computed } from 'vue';
import Notification from '~/components/form/Notification.vue';

const fileInput = ref(null);
const show = ref(false);
const success = ref(false);

const state = reactive({
  errors: null,
  loading: false,
  data: {
    name: null,
    description: null,
    quantity: null,
    price: null,
    photo: null,
    category: null,
  }
});

const photoURL = computed(() => {
  return state.data.photo ? URL.createObjectURL(state.data.photo) : null;
});

function handleFileChange(event) {
  const file = event.target.files[0];
  state.data.photo = file;
}

function handleFileDrop(event) {
  const file = event.dataTransfer.files[0];
  state.data.photo = file;
}

function triggerFileInput() {
  fileInput.value.click();
}

async function handleSubmit() {
  state.loading = true;
  state.errors = null;

  if (!state.data.name || !state.data.description || !state.data.quantity || !state.data.price || !state.data.photo) {
    state.errors = "All fields are required.";
    state.loading = false;
    show.value = true;
    setTimeout(() => show.value = false, 1500);
    return;
  }

  try {
    const formData = new FormData();
    formData.append('name', state.data.name);
    formData.append('description', state.data.description);
    formData.append('quantity', state.data.quantity);
    formData.append('price', state.data.price);
    formData.append('photo', state.data.photo);
    formData.append('category', state.data.category);

    const response = await fetch('http://127.0.0.1:8000/api/products', {
      method: 'POST',
      headers: {
        Authorization: "Bearer " + localStorage.getItem('_token'),
      },
      body: formData,
    });

    if (!response.ok) {
      state.errors = "Error submitting form. Please try again.";
      state.loading = false;
      show.value = true;
      setTimeout(() => show.value = false, 1500);
      return;
    }
    
    resetForm()
    console.log('Product created:', await response.json());
    success.value = true; 
    show.value = true; 
    setTimeout(() => show.value = false, 1500);
    window.location.reload();
  } catch (error) {
    state.errors = "Error creating product: " + error.message;
    success.value = false; 
    show.value = true; 
    setTimeout(() => show.value = false, 1500);
  } finally {
    state.loading = false;
  }
}

const resetForm = () => {
  state.data.value = { name: '', description: '', photo: '', quantity: '', price: '', category: '' };
};
</script>