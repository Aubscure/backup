<template>
  <div class="flex flex-col justify-center flex-1 min-h-screen px-6 py-12 bg-gray-900 lg:px-8 scrollbar-hide">
    <div class="self-center p-5 bg-gray-800 rounded-lg shadow-md ring-1 ring-gray-700 shadow-gray-700 w-96">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <img class="w-auto h-12 mx-auto mt-5" src="~/assets/css/img/LOGO1.png" alt="STI" />
          <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-center text-gray-100">Sign in to your account</h2>
      </div>

      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm lg:p-4">
          <form class="space-y-6" action="#" method="POST" @submit.prevent="handleLogin()">
              <div>
                  <p class="text-sm font-medium text-center text-red-900">{{ state.errors && state.errors._data && state.errors._data.error}}</p>
                  <label for="email" class="block text-base font-medium leading-6 text-gray-100">Email address</label>
                  <div class="mt-2">
                      <input v-model="state.user.email" id="email" name="email" type="email" autocomplete="email" required="" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6" />
                  </div>
              </div>

              <div>
                  <div class="flex items-center">
                      <label for="password" class="block text-base font-medium leading-6 text-gray-100">Password</label>
                  </div>
                  <div class="mt-2">
                      <input v-model="state.user.password" id="password" name="password" type="password" autocomplete="current-password" required="" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6" />
                  </div>
              </div>

              <div>
                  <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-base font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</template>

<script setup>
const state = reactive({
  errors: [],
  user:{
      email: null,
      password: null,
  },
})

definePageMeta({
  layout: 'empty'
})

async function handleLogin(){
const params = { 
  email: state.user.email,
  password: state.user.password
}
try{
  const response = await $fetch(`http://127.0.0.1:8000/api/login`, {
  method: 'POST',
  body: params})

  if(response.data){
  localStorage.setItem('_token', response.data.token)
  state.token = localStorage.getItem('_token')
  navigateTo('/admin/dashboard');}
  }

catch(error){
  state.errors = error.response
  console.log(error.response)
  console.log('error', error)
  }
}

function isLogged(){
  if(localStorage.getItem('_token')){
      alert("Not logged in! log in first.")
      navigateTo('/admin')
  }
}

onMounted(() => {
  isLogged()
});
</script>

<style lang="scss" scoped>

</style>    