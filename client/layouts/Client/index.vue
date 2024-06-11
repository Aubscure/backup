<template>

<section class="items-start block w-full h-auto">

  <header class="w-full mb-40">
    <ClientNavHeader />
  </header>
  
  <section class="z-0 w-full h-auto">
    <slot />
    <ClientFooter />
  </section>
  
</section>

<footer class="fixed bottom-0 z-20 w-full">
  <ClientNavFooter />
</footer>

</template>
