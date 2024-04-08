<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs-fix-scroll/vue3';
import { reactive, defineProps, onMounted, computed, toRefs, watch, inject } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import { VaFileUpload } from "vuestic-ui";

    const Swal = inject('Swal');

   const props = defineProps({
        title: {
            type: String
        },
        produk: {
            type: Object
        }
    });

      const { title, produk } = toRefs(props);

      const detailProduk = produk.value;

      const state = reactive({
        loading: true,
        undoDuration: 5000,
        deletedFileMessage: "File exterminated",
        undoButtonText: "Cancel",
        image : [],
        error: null
      })

      const form = useForm({
        id: detailProduk.id,
        name: detailProduk.name,
        description: detailProduk.description,
        image: detailProduk.image,
        barcode: detailProduk.barcode,
        price: detailProduk.price,
        qty:  detailProduk.qty+'',
        status : detailProduk.status,
        _method : "put"
      })

      function submit() {
        console.log('masuk', form);
        state.loading = true;
        form.post(route('produks.update', form.id), {
            forceFormData: true,
            onSuccess: success => {
                state.loading = false
                Swal.fire({
                    title: 'Success!',
                    text: 'Produk berhasil diedit',
                    icon: 'success',
                    confirmButtonText: 'Tutup'
                })
                router.get('/produks', { search: '' }, { replace: true });
            },
            onError: errors => { 
                state.error = errors;
                console.log(state.error)
            }
        });
      }

      watch(
        () => state.image,
        (file) => {
            if(state.image.length > 1){
                state.image.splice(0,1);
            }
            form.image = file[0] || null
            console.log("cek",form.image)
        }
    )

      onMounted(()=>{
        setTimeout(() => {
            form.loading = false
        }, 5000);

        axios.get(`/storage/${detailProduk.image}`, { responseType: 'blob' }).then((response) => {
            // Create a File object from the Blob
            let file = new File([response.data], 'image.jpg', { type: 'image/jpeg' });
            state.image.splice(0,1,file);
            form.image = file || null
        });
      })

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
                <va-card-title>Form Create</va-card-title>
                <va-card-content>
                    <div class="row">
                        <div class="col">
                            <form @submit.prevent="submit">
                                    <div class="form-group">
                                        <label for="name">Nama Produk</label>
                                        <TextInput
                                            id="name"
                                            type="text"
                                            class="form-control mt-1 block w-full"
                                            :class="[form.errors.name ? 'is-invalid' : '']"
                                            v-model="form.name"
                                            required
                                            autofocus
                                            placeholder="Name"
                                        />
                                        <span v-if="state.error && state.error.name" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.name }}</strong>
                                        </span>
                                    </div>


                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control" :class="[form.errors.description ? 'is-invalid' : '']"
                                            id="description" placeholder="Description" v-model="form.description"></textarea>
                                        <span v-if="state.error && state.error.description" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.description }}</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <div class="custom-file">
                                            <!-- <input type="file" class="form-control custom-file-input" :class="[form.errors.image ? 'is-invalid' : '']" name="image" id="image" @change="getFileInputValue">
                                            <label class="custom-file-label" for="image">Choose file</label> -->
                                            <va-file-upload
                                                v-model="state.image"
                                                undo
                                                type="gallery"
                                                :undo-duration="state.undoDuration"
                                                :undo-button-text="state.undoButtonText"
                                                :deleted-file-message="state.deletedFileMessage"
                                            />
                                        </div>
                                        <span v-if="state.error && state.error.image" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.image }}</strong>
                                        </span>
                                    </div>

                                    <div class="form-group" :style="{ marginTop: state.image.length > 0 ? '45px' : '' }">
                                        <label for="barcode">Barcode</label>
                                        <!-- <input type="text" name="barcode" class="form-control" :class="[form.errors.barcode ? 'is-invalid' : '']" id="barcode" placeholder="barcode" v-model="form.barcode"> -->
                                        <TextInput
                                            id="barcode"
                                            type="text"
                                            class="form-control mt-1 block w-full"
                                            :class="[form.errors.barcode ? 'is-invalid' : '']"
                                            v-model="form.barcode"
                                            autofocus
                                            placeholder="Barcode"
                                        />
                                        <span v-if="state.error && state.error.barcode" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.barcode }}</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <TextInput
                                            id="price"
                                            type="number"
                                            class="form-control mt-1 block w-full"
                                            :class="[form.errors.price ? 'is-invalid' : '']"
                                            v-model="form.price"
                                            required
                                            autofocus
                                            placeholder="Harga (IDR)"
                                        />
                                        <span v-if="state.error && state.error.price" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.price }}</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                            <TextInput
                                            id="name"
                                            type="number"
                                            class="form-control mt-1 block w-full"
                                            :class="[form.errors.qty ? 'is-invalid' : '']"
                                            v-model="form.qty"
                                            required
                                            autofocus
                                            placeholder="Quantity"
                                        />
                                        <span v-if="state.error && state.error.qty" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.qty }}</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" :class="[form.errors.status ? 'is-invalid' : '']" v-model="form.status" id="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span v-if="state.error && state.error.status" class="invalid-feedback" role="alert">
                                            <strong>{{ state.error.status }}</strong>
                                        </span>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6"> 
                                        <Link :href="route('produks.index')">
                                            <button class="btn btn-outline-info"
                                                style="float: left;"
                                            >
                                                Back
                                            </button>
                                        </Link>
                                        </div>
                                        <div class="col-6">
                                        <PrimaryButton :loading="form.processing" :class="{ 'opacity-25': form.processing }" style="float: right;" :disabled="form.processing">
                                            <span>Edit Data</span> 
                                        </PrimaryButton>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </va-card-content>
                </va-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
  
