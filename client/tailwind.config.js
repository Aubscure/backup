/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
    "./error.vue",
  ],
  theme: {
    extend: {
      colors: {
        customDark: '#1F2937',
        customDark2: '#171e29'
      },
      backgroundImage: {
        'login': "url('/assets/css/img/1.jpg')",
      },
    },
  },
  plugins: [

  ],
}
