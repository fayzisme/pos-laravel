<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs-fix-scroll/vue3';
import { reactive, defineProps, onMounted, computed, toRefs, watch, inject } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import { VaFileUpload } from "vuestic-ui";

   const helpers = inject('helpers');

   const props = defineProps({
        title: {
            type: String
        },
        transaction: {
            type: Object
        }
    });

      const { title, transaction } = toRefs(props);

      const detailtransaction = transaction.value;

      const state = reactive({
        loading: true,
        undoDuration: 5000,
        deletedFileMessage: "File exterminated",
        undoButtonText: "Cancel",
        image : [],
        detailtransaction,
        subtotal_rupiah : 0,
        subtotal: 0,
        pajak : 0,
        pajak_rupiah : 0,
        total: 0,
        total_rupiah: 0,
        bayarValue_rupiah: null,
        kembalianValue: 0,
        kembalianValue_rupiah: null,
        error: null
      })

    //   const form = useForm({
    //     id: detailtransaction.id,
    //     name: detailtransaction.name,
    //     description: detailtransaction.description,
    //     image: detailtransaction.image,
    //     barcode: detailtransaction.barcode,
    //     price: detailtransaction.price,
    //     qty:  detailtransaction.qty+'',
    //     status : detailtransaction.status,
    //     _method : "put"
    //   })

    function countSubTotal(params) {
        if(params.length == 0) return 0;
        return params.reduce((acc, res) => acc + res.price , 0);
    }

    function countPajak(subtotal) {
        return 1/10*subtotal;
    }

    onMounted(()=>{
    setTimeout(() => {
        state.loading = false
    }, 3000);

    let subtotal = countSubTotal(state.detailtransaction.order_details);
    let pajak = countPajak(subtotal);
    state.subtotal = subtotal;
    state.subtotal_rupiah = helpers.rupiah(subtotal);
    state.pajak = pajak;
    state.pajak_rupiah = helpers.rupiah(pajak);
    state.total = state.subtotal + state.pajak;
    state.total_rupiah = helpers.rupiah(state.total);
    state.bayarValue_rupiah = helpers.rupiah(state.detailtransaction.payment.amount)
    state.kembalianValue = state.detailtransaction.payment.amount - state.total;
    state.kembalianValue_rupiah = helpers.rupiah(Number(state.kembalianValue));
    })

    function onClickBack() {
        // console.log('masuk');
        router.visit(route('transaksis.index'), {
            preserveState: false,
            preserveScroll: false
        })
      }

      // a computed ref
    const pages = computed(() => {
        return form.perPage && form.perPage !== 0
            ? Math.ceil(form.filtered.length / form.perPage)
            : form.filtered.length;
    })

</script>

<template>
    <Head title="Product" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <div class="row">
            <div class="col">
                <va-card
                square
                outlined
                >
                <va-card-title>Detail Transaksi</va-card-title>
                <va-card-content>
                    <div class="flex flex-col items-start gap-2">
                        <div class="row" style="width: 100%;">
                            <div class="col">
                                <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h3 class="card-title">Data Order</h3>
                                                </div>
                                                <div class="mt-3 col-sm-12">
                                                    <h5>Order {{ state.detailtransaction.code }}</h5>
                                                    <p>{{ state.detailtransaction.tanggal_transaksi }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 mb-3 row">
                                                <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                                                <div class="col-sm-8" style="padding-top: 10px;">
                                                    <h5>{{ state.detailtransaction.nama_customer || state.detailtransaction.user.name }}</h5>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="meja" class="col-sm-4 col-form-label">No. Meja</label>
                                                <div class="col-sm-8" style="padding-top: 10px;">
                                                    <h5>{{ state.detailtransaction.nomor_meja || '-' }}</h5>
                                                </div>
                                            </div>
                                            <ul class="list-group" v-if="state.detailtransaction">
                                                <li v-for="(val, i ) in state.detailtransaction.order_details" class="list-group-item" :key="i">
                                                    <div class="card mb-3" style="max-width: 100%; max-height: 150px;">
                                                        <div class="row g-0">
                                                            <div v-if="val.produk.image" class="col-md-6">
                                                                <img :src="`/storage/${val.produk.image}`" style="height: 8em;" class="img-fluid" :alt="val.name">
                                                            </div>
                                                            <div v-else class="col-md-6 loading-skeleton">
                                                                    <img  src="//placekitten.com/300/200" style="height: 8em;" class="img-fluid rounded-start" alt="...">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card-body">
                                                                    <h6 class="card-title"><strong>{{ val.produk.name }}</strong></h6>
                                                                    <p class="card-text"><small>{{ helpers.rupiah(val.price) }}</small></p>
                                                                    <p class="card-text"><small class="text-muted">{{ val.quantity }}x</small></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div v-if="state.detailtransaction.order_details && state.detailtransaction.order_details.length > 0" class="mt-3 mb-3 row">
                                                <div class="col">
                                                    <VaTextarea
                                                        :style="'width: 100%'"
                                                        v-model="state.detailtransaction.keterangan"
                                                        autosize
                                                        placeholder="Tambahan"
                                                        readonly
                                                    />
                                                </div>
                                            </div>
                                            <div v-if="state.detailtransaction.order_details && state.detailtransaction.order_details.length > 0" class="mt-3 mb-3 row">
                                                <div class="col">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row mb-5">
                                                                <div class="col-md-6">
                                                                    Subtotal
                                                                </div>
                                                                <div class="col-md-6" style="text-align: center;">
                                                                    {{ state.subtotal_rupiah }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-5">
                                                                <div class="col-md-6">
                                                                    Discount
                                                                </div>
                                                                <div class="col-md-6" style="text-align: center;">
                                                                    -
                                                                </div>
                                                            </div>
                                                            <div class="row mb-5">
                                                                <div class="col-md-6">
                                                                    Pajak
                                                                </div>
                                                                <div class="col-md-6" style="text-align: center;">
                                                                    {{ state.pajak_rupiah }}
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row mt-3">
                                                                <div class="col-md-6">
                                                                    <h5>Total :</h5>
                                                                </div>
                                                                <div class="col-md-6" style="text-align: center;">
                                                                    <h5>{{ state.total_rupiah }}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h5>Jumlah Bayar :</h5>
                                                                </div>
                                                                <div class="col-md-6" style="text-align: center;">
                                                                    <h5>{{ state.bayarValue_rupiah }}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h5>Kembalian :</h5>
                                                                </div>
                                                                <div class="col-md-6" style="text-align: center;">
                                                                    <h5>{{ state.kembalianValue_rupiah }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                            </div>
                        </div>                 
                    </div>
                </va-card-content>
                <va-card-actions align="between">
                    <VaButton @click="onClickBack()">Kembali</VaButton>
                </va-card-actions>
                </va-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
  
