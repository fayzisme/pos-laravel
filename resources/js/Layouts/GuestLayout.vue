<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs-fix-scroll/vue3';
</script>

<template>
    <div class="auth-layout grid grid-cols-12 content-center">
      <div class="flex col-span-12 p-4 justify-center">
        <Link class="justify-center flex" href="/">
            <!-- <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" /> -->
            <h1 style="font-size: 50px; text-align: center;">Point of Sale</h1>
        </Link>
      </div>
  
      <div class="flex justify-center col-span-12 p-4">
        <va-card class="auth-layout__card">
          <va-card-content>
            <va-tabs v-model="tabIndex" center>
              <template #tabs>
                <Link
                    :href="route('login')"
                    >
                    <va-tab name="login">Login</va-tab>
                </Link>
                <Link
                    :href="route('register')"
                    >
                    <va-tab name="register">
                        Register
                    </va-tab>
                </Link>
              </template>
            </va-tabs> 
  
            <va-separator />
  
            <div class="p-3">
                <slot />
            </div>
          </va-card-content>
        </va-card>
      </div>
    </div>
  </template>

<script>
export default {
    name: 'GuestLayout',
    data() {
      return {
        tabIndex: window.location.href.split('/')[3],
        actionUrl : ''
      }
    },
    mounted(){
        console.log(this.tabIndex);
    },
    watch:{
        tabIndex(){
           this.actionUrl = this.tabIndex;
        //    return location.replace(this.actionUrl)
        }
    },
    // computed: {
    //   tabIndex: {
    //     set(tabName) {
    //         this.actionUrl = `{{ url('${tabName}') }}`
    //     },
    //     get() {
    //     //   return location.replace(this.actionUrl)
    //     },
    //   },
    // },
  }
</script>

<style lang="scss">
  .auth-layout {
    min-height: 100vh;
    background-image: linear-gradient(to right, var(--va-background-primary), var(--va-white));

    &__card {
      width: 100%;
      max-width: 600px;
    }
  }
</style>
<!-- <template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template> -->
