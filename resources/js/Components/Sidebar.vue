<script setup>
import { onMounted, ref, computed, watch } from 'vue';
import { Link } from '@inertiajs-fix-scroll/vue3';
const logo = window._asset + `assets/dist/img/AdminLTELogo.png`;
const avatar = ref(window._asset + `assets/dist/img/user2-160x160.jpg`);

// Membuat computed property untuk menghasilkan kondisi sidebar
const isSidebarCollapsed = computed(() => {
   return !$('.sidebar-collapse').length < 1;
});

const _route = computed(() => {
   return route().current();
});

onMounted(() => {
   console.log(route().current());
})

</script>

<style scoped>
.inactive {
   display: none;
}
</style>
<template>

   <aside id="default-sidebar"
      class="bg-white fixed top-0 left-0 z-40 w-62 h-screen transition-transform -translate-x-full sm:translate-x-0"
      aria-label="Sidebar">
      <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
         <ul class="space-y-2 font-medium mx-6 flex flex-col gap-4 nonactive ">
            <li class="flex flex-col items-center gap-2 ">
               <a href="">
                  <img class="w-20 h-20 rounded-full"
                     src="https://cdn0-production-images-kly.akamaized.net/n17EE2hpje18SmgwTh0JIrlCNiA=/640x853/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3960331/original/039270200_1647052633-IMG-20171112-WA0024.jpg"
                     alt="Jese Leos">
               </a>
               <p class="text-base font-semibold leading-none text-gray-900 dark:text-white">
               <div class="info" :class="{ inactive: isSidebarCollapsed }">
                  <a href="#" class="d-block">{{ $page.props.auth.user.name }}</a>
               </div>
               </p>
               <p class="text-sm font-normal">
                  <a href="#" class="hover:underline">Waiter 4.h 53m</a>
               </p>
               <button type="button" class="btn btn-outline-secondary py-2 border-gray-200 text-sm">Open
                  Profile</button>
            </li>
            <li>
               <a :href="route('dashboard')" :class="[(_route == 'dashboard') ? 'active ' : '']"
                  class="inline-flex items-center px-5 p-2 w-full text-gray-300 rounded-lg group">
                  <i class="text-lg bi bi-ui-checks-grid"></i>
                  <span class="ms-3 text-base font-semibold">Dashboard</span>
               </a>
            </li>
            <li>
               <a :href="route('orders.index')" :class="[_route.includes('orders') ? 'active' : '']"
                  class="inline-flex items-center px-5 p-2 w-full text-gray-300 rounded-lg group">
                  <i class="text-lg bi bi-cart3"></i>
                  <span class="ms-3 text-base font-semibold"> Order</span>
               </a>
            </li>
            <li>
               <a :href="route('transaksis.index')" :class="[_route.includes('transaksis') ? 'active' : '']"
                  class="inline-flex items-center px-5 p-2 w-full text-gray-300 rounded-lg group">
                  <i class="text-lg bi bi-credit-card"></i>
                  <span class="ms-3 text-base font-semibold">Transaksi</span>
               </a>
            </li>
            <li>
               <a :href="route('produks.index')" :class="[_route.includes('produks') ? 'active' : '']"
                  class="inline-flex items-center px-5 p-2 w-full text-gray-300 rounded-lg group">
                  <i class="text-lg bi bi-journal-text"></i>
                  <span class="ms-3 text-base font-semibold"> Produk</span>
               </a>
            </li>
         </ul>
      </div>
   </aside>

</template>