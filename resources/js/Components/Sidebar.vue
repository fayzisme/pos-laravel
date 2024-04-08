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

    onMounted(()=>{
      console.log(route().current());
    })
    
</script>

<style scoped>
.inactive {
  display: none;
}
</style>
<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <!-- <img :src="logo" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <span class="brand-text font-weight-light">Point of Sale</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!-- <div class="image">
                <img :src="avatar" class="img-circle elevation-2" alt="User Image">
            </div> -->
            <div class="info" :class="{ inactive: isSidebarCollapsed }">
                <a href="#" class="d-block">{{  $page.props.auth.user.name }}</a>
            </div>
        </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a :href="route('dashboard')" class="nav-link" :class="[(_route == 'dashboard') ? 'active' : '']">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a :href="route('orders.index')" class="nav-link" :class="[_route.includes('orders') ? 'active' : '']">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Order
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a :href="route('produks.index')" class="nav-link" :class="[_route.includes('produks') ? 'active' : '']">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Produk
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a :href="route('transaksis.index')" class="nav-link" :class="[_route.includes('transaksis') ? 'active' : '']">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Transaksi
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  </template>


  