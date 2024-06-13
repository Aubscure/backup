<template>
    <div class="min-h-full bg-gray-800 shadow-lg lg:block lg:overflow-y-auto rounded-xl scrollbar-hide ring-1 ring-gray-800 shadow-gray-950">
      <header class="">
        <div class="px-4 py-5 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <h1 class="text-5xl font-semibold tracking-tight text-gray-100">Dashboard</h1>
        </div>
      </header>
      <main class="grid-rows-2 px-48 pb-6">
        <div class="py-6 my-6 bg-gray-700 bg-opacity-50 shadow-md max-w-7xl sm:px-6 lg:px-8 rounded-xl shadow-gray-950">
          <table class="w-full table-auto">
            <thead>
              <tr>
                <th v-for="column in columns" :key="column.id" class="px-4 pb-5 text-3xl font-medium text-gray-100 border-b-2 border-gray-700">{{ column.name }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(stat, index) in stats" :key="index" class="">
                <td v-for="column in columns" :key="column.id" class="py-2 text-xl font-light text-gray-100 ">{{ getValue(stat, column.name) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="justify-start max-w-sm py-6 my-6 bg-gray-700 bg-opacity-50 rounded-lg shadow-md shadow-gray-950 sm:px-6 lg:px-8">
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-3xl font-medium text-gray-100 border-b-2 border-gray-700">{{ orderColumns[0].name }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(orderStat, index) in orderStats" :key="index" class="flex justify-center">
                        <td class="max-w-xs px-4 py-2 text-xl font-light text-gray-100">{{ orderStat.Orders }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="my-5">
          <nuxt-link to="./../../kiosk/products" class="px-5 py-2 text-white bg-gray-700 bg-opacity-50 rounded-lg shadow-md shadow-gray-950 ">INITIALIZE KIOSK</nuxt-link>
        </div>

      </main>
    </div>
  </template>
  
  <script setup>
  definePageMeta({
    layout: 'admin'
  });
  
  const stats = [
    { id: 1, Course: 'HM', Top: 11, Bottom: 22, Accessory: 33 },
    { id: 2, Course: 'TM', Top: 11, Bottom: 22, Accessory: 33 },
    { id: 3, Course: 'BSIT/BSCS/BSCPE', Top: 11, Bottom: 22, Accessory: 33 },
  ];
  
  const columns = [
    { id: 1, name: 'Course' },
    { id: 2, name: 'Top' },
    { id: 3, name: 'Bottom' },
    { id: 4, name: 'Accessory' },
  ];
  const orderStats = [
    { id: 1, Orders: 245 },
  ];
  const orderColumns = [
    { id: 1, name: 'Orders'}
  ];
  
  function getValue(stat, columnName) {
    return stat[columnName];
  }
  </script>
  