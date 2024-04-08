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
                  'data': 'nama_customer',
                  'class': 'text-center',
                  'orderable': true
              },
              {
                  'data': 'code',
                  'class': 'text-center',
                  'width' : '100px',
                  'orderable': true
              },
              {
                  'data': 'created_at',
                  'class': 'text-center',
                  'width': '200px',
                  'orderable': true
              },
              {
                  render: (index, row, data, meta) => {
                    return `
                    <a href="#" type="button" id="${data.id}" class="btn btn-outline-info show">
                                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="#" type="button" id="${data.id}" class="btn btn-outline-primary edit">
                                        <i class="fas fa-pen"></i>
                    </a>
                    <a href="#" type="button" id="${data.id}" class="btn btn-outline-danger delete">
                                        <i class="fas fa-trash"></i>
                    </a>`
                  },
                  'width': '200px',
                  'orderable': false
              }
      ];
    
      const state = reactive({
        apiUrl : 'api/transaksi/list',
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
            state.apiUrl = `api/transaksi/list?code=${val}`;
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
                      console.log(_this.table.ajax.json().data);
                      _this.datas = _this.table.ajax.json().data;

                      if (_this.datas.length > 0) {
                        setTimeout(() => {
                           let edits = $("a.edit");
                           let deletes = $("a.delete");
                           let shows = $("a.show");
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

                            $.each(shows, (index, item) => {
                                item.addEventListener('click', (event) => {
                                    event.preventDefault()
                                    onClickShow(item.id);
                                });
                            });
                        }, 100);
                      }
                    })
      }

      function onClickCreate() {
        // console.log('masuk');
        router.visit('transaksis/create', {
            method: 'get',
            preserveState: true,
            preserveScroll: true
        })
      }

      function onClickEdit(id) {
        router.visit(route('transaksis.edit', id), {
            preserveState: true,
            preserveScroll: true,
            replace: false
        })
      }

      function onClickDelete(id) {
        router.visit(route('transaksis.destroy', id), { method: 'delete', onBefore: () => confirm('Are you sure you want to delete this transaksi?') });
      }

      function onClickShow(id) {
        router.visit(route('transaksis.show', id), {
            preserveState: true,
            preserveScroll: true,
            replace: false
        })
      }

      onMounted(()=>{
        window.scrollTo(0, 0);
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
    <Head title="Transaction" />

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
                <va-card-title>Table Transactions</va-card-title>
                <va-card-content>
                    <div class="mb-6">
                        <div class="row">
                            <div class="col-6">
                                <va-input
                                    v-model="state.filter"
                                    class="sm:col-span-2 md:col-span-3"
                                    placeholder="Cari kode transaksi"
                                />
                            </div>
                        </div>
                    </div>
                
                    <table id="myTableProduk" class="table table-bordered table-hover">
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Kode Transaksi</th>
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
