<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs-fix-scroll/vue3';
import { reactive, defineProps, onMounted, computed, toRefs, watch } from 'vue';

   const props = defineProps({
        title: {
            type: String,
        },
        flash: {
            type: Object
        }
    });

    const { title } = toRefs(props);

    const columns = [
              {
                  'data': 'DT_RowIndex',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  'data': 'name',
                  'class': 'text-center',
                  'width' : '200px',
                  'orderable': true
              },
              {
                  'data': 'image',
                  'class': 'text-center',
                  'width': '150px',
                  render: (index,row,data,meta) => {
                    return data.image ? `<img class="product-img" style="width: 100%;" src="/storage/${data.image}" alt="img_product">` : '<div>-</div>'
                  }
              },
              {
                  'data': 'barcode',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  'data': 'price',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  'data': 'qty',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  render: (index,row,data,meta) =>{
                    return `
                        <span class="right badge badge-${ data.status ? 'success' : 'danger' }">${data.status ? 'Active' : 'Inactive'}</span>
                    `
                  },
                //   'data': 'status',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  'data': 'created_at',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  render: (index, row, data, meta) => {
                    return `
                    <a href="#" type="button" id="${data.id}" class="btn btn-outline-primary edit">
                                        <i class="fas fa-pen"></i>
                    </a>
                    <a href="#" type="button" id="${data.id}" class="btn btn-outline-danger delete">
                                        <i class="fas fa-trash"></i>
                    </a>`
                  },
                  'width': '150px',
                  'orderable': false
              }
      ];
    
      const state = reactive({
        apiUrl : 'api/produk/list',
        datas: [],
        columns,
        perPage: 10,
        currentPage: 1,
        filter: "",
        loading: true
      })

      // a computed ref
    const alertVisible = computed(() => {
        return props.flash.message ? true : false
    })

    watch(() => state.filter,
    (val) => {
        // search
        setTimeout(() => {
            state.apiUrl = `api/produk/list?name=${val}`;
            state.table.ajax.url(state.apiUrl).load()
        }, 500);
    })

      function getData() {
        const _this = state
       _this.table =  $('#myTableProduk').DataTable({
                    "lengthChange": false,
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    ajax: {
                        type: "get",
                        url:  _this.apiUrl,
                        'processing': true,
                        "language": {
                            "processing": "<span class='fa-stack fa-lg'>\n\
                                                <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                        </span>&emsp;Processing ...",
                        },
                        error: function (xhr, error, thrown) {
                            console.log('Kesalahan AJAX:', error);
                            console.log('Detail Kesalahan:', thrown);
                            // Tindakan yang sesuai, misalnya menampilkan pesan kesalahan kepada pengguna
                        }
                    },
                    columns:  _this.columns
                    }).on('xhr', function () {
                      // console.log(_this.table.ajax.json().data);
                      _this.datas = _this.table.ajax.json().data;

                      if (_this.datas.length > 0) {
                        setTimeout(() => {
                           let edits = $("a.edit");
                           let deletes = $("a.delete");
                            $.each(edits, (index, item) => {
                                item.addEventListener('click', (event) => {
                                    event.preventDefault()
                                    onClickEdit(item.id);
                                });
                            });

                            $.each(deletes, (index, item) => {
                                item.addEventListener('click', (event) => {
                                    event.preventDefault()
                                    onClickDelete(item.id);
                                });
                            });
                        }, 100);
                      }
                    })
      }

      function onClickCreate() {
        // console.log('masuk');
        router.visit('produks/create', {
            method: 'get',
            preserveState: true,
            preserveScroll: true
        })
      }

      function onClickEdit(id) {
        router.visit(route('produks.edit', id), {
            preserveState: true,
            preserveScroll: true
        })
      }

      function onClickDelete(id) {
        router.visit(route('produks.destroy', id), { method: 'delete', onBefore: () => confirm('Are you sure you want to delete this product?') });
      }

      onMounted(()=>{
        getData();
        setTimeout(() => {
            state.loading = false
        }, 5000);
      })

      // a computed ref
    const pages = computed(() => {
        return state.perPage && state.perPage !== 0
            ? Math.ceil(state.filtered.length / state.perPage)
            : state.filtered.length;
    })

</script>

<template >
    <Head title="Product" />

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
                <va-card-title>Table Products</va-card-title>
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
                            <div class="col-6">
                                <va-button
                                    style="float: right; color: white;"
                                    @click="onClickCreate"
                                >
                                    Add Product
                                </va-button>
                            </div>
                        </div>
                    </div>
                
                    <table id="myTableProduk" class="table table-bordered table-hover">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                    </table>
                </va-card-content>
                </va-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
// $("#edit").on( "click", function() {
//   alert( "Handler for `click` called." );
// } );
</script>
