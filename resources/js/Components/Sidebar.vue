<script setup>
import { onMounted, ref, computed, watch } from 'vue';
import { Link } from '@inertiajs-fix-scroll/vue3';
const logo = window._asset + `assets/dist/img/AdminLTELogo.png`;
const avatar = ref(window._asset + `assets/dist/img/user2-160x160.jpg`);
import DropdownLink from '@/Components/DropdownLink.vue';
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
      <div class="h-full px-3 py-4 overflow-y-auto grid justify-between">
         <ul class="font-medium mx-6 flex flex-col gap-4 nonactive ">
            <li class="flex flex-col items-center gap-2 ">
               <a href="" class="w-20 h-20 rounded-full overflow-hidden">
                  <img class="w-full h-full object-cover"
                     src="https://studio.mrngroup.co/storage/app/media/Prambors/Editorial%203/Elon%20Musk-20230512141615.webp?tr=w-600"
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
               <a :href="route('profile.edit')">
                  <button type="button" class="btn btn-outline-secondary py-2 border-gray-200 text-sm">
                     Open Profile
                  </button>
               </a>
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
         <ul class="space-y-2 font-medium mx-6 flex flex-col gap-4 nonactive mt-auto">
            <li>
               <a 
                  class="inline-flex items-center px-5 p-2 w-full text-gray-300 rounded-lg group">
                  <i class="text-lg bi bi-gear"></i>
                  <span class="ms-3 text-base font-semibold">Setting</span>
               </a>
            </li>
            <li>
               <DropdownLink :href="route('logout')" method="post" as="button"
                  class="inline-flex items-center px-5 p-2 w-full text-gray-300 rounded-lg group text-danger">
                  <i class="text-lg bi bi-box-arrow-right"></i>
                  <span class="ms-3 text-base font-semibold"> logout</span>
               </DropdownLink>
            </li>
         </ul>
      </div>
   </aside>

</template>