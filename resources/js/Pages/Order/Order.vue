<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs-fix-scroll/vue3';
import { reactive, defineProps, onMounted, computed, toRefs, watch, inject } from 'vue';

const helpers = inject('helpers');
const Swal = inject('Swal');

   const props = defineProps({
        title: {
            type: String,
        },
        flash: {
            type: Object
        },
        order: {
            type: Object
        },
        is_edit: {
            type: Boolean 
        },
        base_url: {
            type: String
        },
        date: {
            type: String
        }
        
    });

    const { title, order, is_edit, base_url, date } = toRefs(props);
    
      const state = reactive({
        apiUrl : 'api/produk/list_active',
        nama : '',
        no_meja: '',
        datas: [],
        produks: [],
        items: [],
        perPage: 10,
        currentPage: 1,
        filter: "",
        keterangan: "",
        subtotal: 0,
        subtotal_rupiah : "",
        pajak: 0,
        discount : 0,
        total: 0,
        total_rupiah: "",
        code_order: "",
        loading: true,
        showFirstModal: false,
        showSecondModal: false,
        currency_rupiah : 'Rupiah',
        currency_icon : '&#36;',
        bayarValue : 0,
        bayarValue_rupiah: null,
        kembalianValue : 0,
        kembalianValue_rupiah: null,
        btnLoading: false,
        isSave: false,
        order,
        is_edit,
        base_url
      })

      // a computed ref
    const alertVisible = computed(() => {
        return props.flash.message ? true : false
    })

    watch(() => state.items,
    (val) => {
        console.log('item', val);
        let subtotal = countSubTotal(val);
        let pajak = countPajak(subtotal);
        state.subtotal = subtotal;
        state.subtotal_rupiah = helpers.rupiah(subtotal);
        state.pajak = pajak;
        state.pajak_rupiah = helpers.rupiah(pajak);
        state.total = state.subtotal + state.pajak;
        state.total_rupiah = helpers.rupiah(state.total);
    }, {deep: true})

    function countSubTotal(params) {
        if(params.length == 0) return 0;
        return params.reduce((acc, res) => acc + res.price , 0);
    }

    function countPajak(subtotal) {
        return 1/10*subtotal;
    }

    function clearOrder() {
        state.nama = '';
        state.no_meja = '';
        state.items = [];
        state.bayarValue = 0;
        state.keterangan = "";
        state.subtotal = 0;
        state.subtotal_rupiah = "";
        state.pajak = 0;
        state.discount = 0;
        state.total = 0;
        state.total_rupiah = "";
        state.isSave = false;

        let code_order = helpers.codeOrder(6)
        state.code_order = code_order;
        localStorage.setItem('code_order', code_order);
        localStorage.setItem('items', null);
    }

    watch(() => state.filter,
    (val) => {
        // search
        setTimeout(() => {
            state.apiUrl = `api/produk/list_active?name=${val}`;
        }, 500);
    })


     async function getData() {
        // console.log(state.is_edit, state.base_url);
        if (state.is_edit && state.base_url) {
            state.apiUrl = state.base_url + '/' + state.apiUrl;
        }
        const _this = state

        await window.axios({ 
				method: 'get',
				url: _this.apiUrl,
        }).then(response => {
        if (response.status == 200) {
            let produks = response.data.data.map(el => {
                el.price_rupiah = helpers.rupiah(el.price)
                // console.log(el.price_rupiah);
                el.jumlah = 1;
                return el;
            })
            _this.produks = response.data.data
        }
        _this.loading = false;
        })
        .catch(error=>{
          console.log(error);
          _this.loading = false;
        })
        
      }

      function clickBayar() {
        state.bayarValue_rupiah = helpers.rupiah(Number(state.bayarValue));
        state.kembalianValue = state.bayarValue - state.total;
        state.kembalianValue_rupiah = helpers.rupiah(Number(state.kembalianValue));
        state.showFirstModal = false;
        state.showSecondModal = true;
      }

      function saveTransaksi() {
        if (state.is_edit) {
            updateOrder();
        }
        else{
            storeOrder();
        }
      }

      function storeOrder() {
        state.btnLoading = true;
        let data = {
            'produks' : state.items,
            'amount' : state.bayarValue,
            'code' : state.code_order,
            'nama' : state.nama,
            'meja' : state.no_meja
        }
        localStorage.setItem('code_order', JSON.stringify(state.code_order));
        router.post('/orders', data, {
            onBefore: () => confirm('Are you sure you want to save this transaction ?'),
            onSuccess: (page) => {
                state.btnLoading = false;
                state.showSecondModal = false;
                state.isSave = true;
                localStorage.setItem('items', JSON.stringify(state.items));
                Swal.fire({
                    title: 'Success!',
                    text: 'Transaksi berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'Tutup'
                })
            },
            onError: (errors) => {
                console.log(errors);
            },
        })
      }

      function updateOrder() {
        state.btnLoading = true;
        let data = {
            'produks' : state.items,
            'amount' : state.bayarValue,
            'code' : state.order.code,
            'nama' : state.nama,
            'meja' : state.no_meja
        }
        let id = state.order.id;
        localStorage.setItem('code_order', JSON.stringify(state.code_order));
        router.put(`/orders/${id}`, data, {
            onBefore: () => confirm('Are you sure you want to update this transaction ?'),
            onSuccess: (page) => {
                state.btnLoading = false;
                state.showSecondModal = false;
                Swal.fire({
                    title: 'Success!',
                    text: 'Transaksi berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'Tutup'
                })

                // kembali ke list transaksi
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            },
            onError: (errors) => {
                console.log(errors);
            },
        })
      }

      function onClickAdd(event, item) {
        event.preventDefault();
        let index = null;
        let price = item.price * item.jumlah;
        let isAdded = state.items.find((el, i) => {
            if(el.id == item.id){
                index = i;
            }

            return el.id == item.id;
        });

        if (isAdded) {
            state.items[index].jumlah += item.jumlah;
            state.items[index].price += price;
            state.items[index].price_rupiah = helpers.rupiah(state.items[index].price);
        }
        else {
            state.items.push({...item});
            state.items[state.items.length - 1].price = price;
            state.items[state.items.length - 1].price_rupiah = helpers.rupiah(price);
        }
        
      }

      function onClickDeleteItem(event, i) {
        event.preventDefault();

        state.items.splice(i, 1);

      }

      onMounted(()=>{
          window.scrollTo(0, 0);
          getData();
          if (state.is_edit) {
                console.log(state.order);
                state.bayarValue = state.order.payment.amount;
                state.order.order_details.forEach(el => {
                    let id = el.id;
                    el.name = el.produk.name;
                    el.image = el.produk.image;
                    el.price_rupiah = helpers.rupiah(el.price);
                    el.jumlah = el.quantity;
                    el.detail_id = id;
                    el.id = el.produk_id;
                });
                state.items = state.order.order_details;
                state.nama = state.order.nama_customer;
                state.no_meja = state.order.nomor_meja;
          }
          else{
              setTimeout(() => {
                let code_order = JSON.parse(localStorage.getItem('code_order'));
                let items = localStorage.getItem('items');
                if(code_order && code_order != 'null'){
                    state.code_order = code_order;
                    if(items && items != 'null' && items.length > 0){
                        state.items = JSON.parse(items);
                        state.isSave = true;
                    }
                }
                else{
                    state.code_order = helpers.codeOrder(6);
                }
                state.loading = false
            }, 300);

          }
      })

      // a computed ref
    const pages = computed(() => {
       
    })

</script>

<template >
    <Head title="Order" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>
        <div class="row"><div class="col">
            <VaAlert
                        v-model="alertVisible"
                        color="success"
                        closeable
                        :class="props.flash.class"
                    >
                        <template #close>
                        close
                        </template>
                        {{ props.flash.message }}
                    </VaAlert>
        </div></div>
        <div class="row">
            <div class="col">
                <va-card
                square
                outlined
                >
                <va-card-title>List Produk</va-card-title>
                <va-card-content>
                    <div class="mb-6">
                        <div class="row">
                            <div class="col-6">
                                <va-input
                                    v-model="state.filter"
                                    class="sm:col-span-2 md:col-span-3"
                                    placeholder="Cari nama produk"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row">
                                <div v-for="(item, key) in state.produks" class="col-sm-4" :key="key">
                                    <div class="card thumb-wrapper" aria-hidden="true" >
                                        <!-- <span class="wish-icon"><i class="fa fa-heart-o"></i></span> -->
                                        <div class="img-box">
                                            <div v-if="item.image">
                                                <img  :src="`/storage/${item.image}`" class="img-fluid" :alt="item.name">
                                            </div>
                                            <div v-else class="loading-skeleton">
                                                <img src="//placekitten.com/300/200" class="img-fluid" alt="...">
                                            </div>
                                                                            
                                        </div>
                                        <div class="thumb-content">
                                            <h4>{{ item.name }}</h4>									
                                            <h4 class="item-price">
                                                <!-- <strike>$400.00</strike>  -->
                                                Harga : 
                                                <b>{{ item.price_rupiah }}</b>
                                            </h4>
                                            <!-- Quantity -->
                                            <div class="row mt-2 mb-2" style="max-width: 100%">
                                                <div class="col-3" style="padding-top: 25px;">
                                                    <VaButton
                                                    size="small"
                                                    round
                                                    @click="item.jumlah--"
                                                    :disabled="item.jumlah == 1"
                                                    >
                                                    <i class="fas fa-minus"></i>
                                                    </VaButton>
                                                </div>
                                                <div class="col-6">
                                                        <VaInput
                                                        v-model="item.jumlah"
                                                        :max="item.qty"
                                                        min="1"
                                                        label="Quantity"
                                                        type="number"
                                                        />
                                                </div>
                                                <div class="col-3" style="padding-top: 25px;">
                                                    <VaButton
                                                    size="small"
                                                    round
                                                    @click="item.jumlah++"
                                                    :disabled="item.jumlah == item.qty"
                                                    >
                                                    <i class="fas fa-plus"></i>
                                                    </VaButton>
                                                </div>
                                            </div>
                                            <!-- Quantity -->
                                            <div class="row">
                                                <div class="col">
                                                    <a href="#" class="btn btn-primary" @click="onClickAdd($event, item)">Add to Cart</a>
                                                </div>
                                            </div>
                                        </div>						
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- create -->
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col">
                                    <div class="card" style="min-height: 400px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h3 class="card-title">Current Order</h3>
                                                </div>
                                                <div class="mt-3 col-sm-12">
                                                    <h5>Order #{{ !is_edit ? state.code_order : state.order.code }}</h5>
                                                    <p>{{ !is_edit ? date : state.order.tanggal_transaksi }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 mb-3 row">
                                                <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                                                <div class="col-sm-8">
                                                <input type="text" class="form-control" id="nama" v-model="state.nama">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="meja" class="col-sm-4 col-form-label">No. Meja</label>
                                                <div class="col-sm-8">
                                                <input type="text" class="form-control" id="meja" v-model="state.no_meja">
                                                </div>
                                            </div>
                                            <ul class="list-group">
                                                <li v-for="(val, i ) in state.items" class="list-group-item" :key="i">
                                                    <div class="card mb-3" style="max-width: 440px;">
                                                        <div class="row g-0">
                                                            <div v-if="val.image" class="col-md-6">
                                                                <img :src="`/storage/${val.image}`" style="height: 100%;" class="img-fluid" :alt="val.name">
                                                            </div>
                                                            <div v-else class="col-md-6 loading-skeleton">
                                                                    <img  src="//placekitten.com/300/200" style="height: 100%;" class="img-fluid rounded-start" alt="...">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card-body">
                                                                    <h6 class="card-title"><strong>{{ val.name }}</strong></h6>
                                                                    <p class="card-text"><small>{{ val.price_rupiah }}</small></p>
                                                                    <p class="card-text"><small class="text-muted">{{ val.jumlah }}x</small></p>
                                                                    <a style="float: right;" @click="onClickDeleteItem($event,i)" href="#" type="button" class="btn btn-outline-danger delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div v-if="state.items.length > 0" class="mt-3 mb-3 row">
                                                <div class="col">
                                                    <VaTextarea
                                                        :style="'width: 100%'"
                                                        v-model="state.keterangan"
                                                        autosize
                                                        placeholder="Tambahan"
                                                    />
                                                </div>
                                            </div>
                                            <div v-if="state.items.length > 0" class="mt-3 mb-3 row">
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
                                                        </div>
                                                        <div class="card-footer" style="text-align: center">
                                                            <va-button
                                                                v-if="state.isSave"
                                                                style="color: white;"
                                                                @click="clearOrder()"
                                                            >
                                                                Clear
                                                            </va-button>
                                                            <va-button
                                                                v-else
                                                                style="color: white;"
                                                                @click="state.showFirstModal = !state.showFirstModal"
                                                            >
                                                                Bayar
                                                            </va-button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- update -->
                    </div>
                   
                </va-card-content>
                </va-card>

                <VaModal
                    ref="modal"
                    v-slot="{ hide }"
                    v-model="state.showFirstModal"
                    hide-default-actions
                    close-button
                >
                    <div class="flex flex-col items-start gap-2">
                        <h3 class="va-h3">
                            Nominal Pembayaran
                        </h3>

                        <div class="row" style="width: 100%;">
                            <div class="col-12">
                                <VaInput
                                v-model="state.bayarValue"
                                placeholder="0.00"
                                style="width: 100%;"
                                type="number"
                                >
                                <template #prependInner>
                                    <span v-html="state.currency_icon" />
                                </template>
                                <template #appendInner>
                                    <span>{{ state.currency_rupiah }}</span>
                                </template>
                                </VaInput>  
                            </div>
                        </div>                 
                    </div>

                    <div class="flex justify-end mt-2 gap-2">
                    <VaButton
                        preset="secondary"
                        color="secondary"
                        @click="hide()"
                    >
                        Cancel
                    </VaButton>
                    <VaButton
                        @click="clickBayar()"
                    >
                        Bayar
                    </VaButton>
                    </div>
                </VaModal>

                <VaModal
                    ref="modal"
                    v-slot="{ hide }"
                    v-model="state.showSecondModal"
                    hide-default-actions
                    close-button
                >
                    <div class="flex flex-col items-start gap-2">
                        <h3 class="va-h3">
                            Detail Transaksi
                        </h3>

                        <div class="row" style="width: 100%;">
                            <div class="col">
                                <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h3 class="card-title">Current Order</h3>
                                                </div>
                                                <div class="mt-3 col-sm-12">
                                                    <h5>Order #{{ !is_edit ? state.code_order : state.order.code }}</h5>
                                                    <p>{{ !is_edit ? date : state.order.tanggal_transaksi }}</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 mb-3 row">
                                                <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                                                <div class="col-sm-8" style="padding-top: 10px;">
                                                    <h5>{{ state.nama }}</h5>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="meja" class="col-sm-4 col-form-label">No. Meja</label>
                                                <div class="col-sm-8" style="padding-top: 10px;">
                                                    <h5>{{ state.no_meja }}</h5>
                                                </div>
                                            </div>
                                            <ul class="list-group">
                                                <li v-for="(val, i ) in state.items" class="list-group-item" :key="i">
                                                    <div class="card mb-3" style="max-width: 100%;">
                                                        <div class="row g-0">
                                                            <div v-if="val.image" class="col-md-6">
                                                                <img :src="`/storage/${val.image}`" style="height: 100%;" class="img-fluid" :alt="val.name">
                                                            </div>
                                                            <div v-else class="col-md-6 loading-skeleton">
                                                                    <img  src="//placekitten.com/300/200" style="height: 100%;" class="img-fluid rounded-start" alt="...">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card-body">
                                                                    <h6 class="card-title"><strong>{{ val.name }}</strong></h6>
                                                                    <p class="card-text"><small>{{ val.price_rupiah }}</small></p>
                                                                    <p class="card-text"><small class="text-muted">{{ val.jumlah }}x</small></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div v-if="state.items.length > 0" class="mt-3 mb-3 row">
                                                <div class="col">
                                                    <VaTextarea
                                                        :style="'width: 100%'"
                                                        v-model="state.keterangan"
                                                        autosize
                                                        placeholder="Tambahan"
                                                        readonly
                                                    />
                                                </div>
                                            </div>
                                            <div v-if="state.items.length > 0" class="mt-3 mb-3 row">
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

                    <div class="flex justify-end mt-2 gap-2">
                    <VaButton
                        preset="secondary"
                        color="secondary"
                        @click="hide()"
                    >
                        Cancel
                    </VaButton>
                    <VaButton
                        @click="saveTransaksi()"
                        :disabled = "state.btnLoading"
                    >
                        <span v-if="!state.btnLoading">
                            {{ !is_edit ? 'Simpan Transaksi' : 'Update Transaksi' }}
                        </span>
                        <div v-else class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </VaButton>
                    </div>
                </VaModal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
$(document).ready(function(){
	$(".wish-icon i").click(function(){
		$(this).toggleClass("fa-heart fa-heart-o");
	});
});	
</script>
