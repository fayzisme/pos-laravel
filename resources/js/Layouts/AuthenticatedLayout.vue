<script setup>
    import Navbar from '@/Components/Navbar.vue';
    import Sidebar from '@/Components/Sidebar.vue';
</script>
<template>
    <div>     
        <Navbar></Navbar>
        <Sidebar></Sidebar>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- <div class="content-header bg-white">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1 class="m-0"><slot name="header"></slot></h1>
                </div>
            </div>
            </div>
        </div> -->
        <!-- /.content-header -->
    
        <!-- Main content -->
        <section class="content bg-white">
            <div class="p-2 md:px-6 md:py-9">
                <main>
                    <slot />
                </main>
            </div>
        </section>
        <!-- /.content -->
        </div>
    </div>
</template>
