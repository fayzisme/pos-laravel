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
            type: String,
        }
    });

    const { title } = toRefs(props);
    
      const state = reactive({
        loading: true,
        undoDuration: 5000,
        deletedFileMessage: "File exterminated",
        undoButtonText: "Cancel",
        image : []
      })

      const form = useForm({
        name: "",
        description: "",
        image: null,
        barcode: "",
        price: "0",
        qty: "0",
        status : '0'
      })

      function submit() {
        // console.log('masuk', form);
        form.post(route('produks.store'), {
            forceFormData: true,
            onError: errors => { console.log(errors)},
            onSuccess: success => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Produk berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'Tutup'
                })
                router.get('/produks', { search: '' }, { replace: true });
            }
        });
      }

      

      const getFileInputValue = (event) => {
        //get the file input value
        const file = event.target.files;
        //assign it to our reactive reference value 
        form.image = file[0] 
        console.log(form.image);
      }

      watch(
        () => state.image,
        (file) => {
            if(state.image.length > 1){
                state.image.splice(0,1);
            }
            form.image = file[0] || null
        }
    )

      onMounted(()=>{
        setTimeout(() => {
            form.loading = false
        }, 5000);
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
                                        <span v-if="form.errors.name" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </div>


                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control" :class="[form.errors.description ? 'is-invalid' : '']"
                                            id="description" placeholder="Description" v-model="form.description"></textarea>
                                        <span v-if="form.errors.description" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
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
                                        <span v-if="form.errors.image" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
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
                                        <span v-if="form.errors.barcode" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
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
                                        <span v-if="form.errors.price" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
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
                                        <span v-if="form.errors.qty" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" :class="[form.errors.status ? 'is-invalid' : '']" v-model="form.status" id="status">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span v-if="form.errors.status" class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
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
                                        <PrimaryButton :class="{ 'opacity-25': form.processing }" style="float: right;" :disabled="form.processing">
                                            <span>Add Data</span> 
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
  
