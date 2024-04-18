<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs-fix-scroll/vue3';
import HorizontalCard from '@/Components/HorizontalCart.vue';
import ChartArea from "@/ui/ChartArea/ChartArea.vue";
import BarChart from "@/ui/BarChart/BarChart.vue";
import PieChart from "@/ui/PieChart/PieChart.vue";
</script>
<script>
export default {
    components: {
        PieChart
    },
    data() {
        return {
            PaymentSeries: [300, 200],
            PaymentLabels: ['Food', 'Drink'],
            PaymentColors: ['#A0129D', '#00829B'],

            IncomeSeries: [300, 200, 150],
            IncomeLabels: ['Cash', 'QRIS', 'Other'],
            IncomeColors: ['#00829B', '#A0129D', 'gray'],

            topDishes: [
                { name: 'Mendoan', order: 200, image: 'https://akcdn.detik.net.id/visual/2019/05/15/aeec3484-03ee-4a13-acc1-78e680fe1e40_43.jpeg?w=720&q=90' },
                { name: 'Bakso', order: 180, image: 'https://akcdn.detik.net.id/visual/2019/05/15/aeec3484-03ee-4a13-acc1-78e680fe1e40_43.jpeg?w=720&q=90' },
                { name: 'Nasi Goreng', order: 150, image: 'https://akcdn.detik.net.id/visual/2019/05/15/aeec3484-03ee-4a13-acc1-78e680fe1e40_43.jpeg?w=720&q=90' },
                { name: 'Sate', order: 130, image: 'https://akcdn.detik.net.id/visual/2019/05/15/aeec3484-03ee-4a13-acc1-78e680fe1e40_43.jpeg?w=720&q=90' },

            ]
        }
    }
}
</script>
<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-6 h-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="flex gap-6">
                    <HorizontalCard productPrice="Rp200.000" percent="12">
                    </HorizontalCard>
                    <HorizontalCard productName="Total Revenue" productPrice="Rp200.000" percent="15">
                        <template #icon>
                            <i class="bi bi-coin text-lg"></i>
                        </template>
                    </HorizontalCard>
                    <HorizontalCard productName="Total Order" productPrice="Rp200.000" percent="13">
                        <template #icon>
                            <i class="bi bi-journal-text text-secondary text-lg"></i>
                        </template>
                    </HorizontalCard>
                    <HorizontalCard productName="cancelled" productPrice="Rp200.000" percent="13">
                        <template #icon>
                            <i class="bi bi-x-circle text-lg text-red"></i>
                        </template>
                    </HorizontalCard>
                </div>
                <div class="row ">
                    <div class="col-6">
                        <div class="card p-2 py-3 mb-0  mr-1">
                            <div class="py-2 px-2 flex justify-between ">
                                <h2>Daily Sales</h2>
                                <!-- <select style="width: 8px;" class="py-1 pr-0  rounded-sm opacity-50"
                                    aria-label=".form-select-sm example">
                                    <option selected>minggu ini</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select> -->
                            </div>
                            <ChartArea />
                        </div>
                    </div>
                    <div class="col-6 ">
                        <div class="card  p-2 py-3 ml-1">
                            <div class="py-2 px-2 flex justify-between ">
                                <h2>Weekly Sales</h2>
                                <!-- <select style="width: 8px;" class="py-1 pr-0  rounded-sm opacity-50"
                                        aria-label=".form-select-sm example">
                                        <option selected>minggu ini</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select> -->
                            </div>
                            <BarChart />
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-4">
                        <div class="card p-2 ">
                            <div class="py-4 px-2 flex justify-between ">
                                <h2>Menu Terlaris</h2>
                                <select style="width: 8px;" class="py-1 pr-0  rounded-sm opacity-50"
                                    aria-label=".form-select-sm example">
                                    <option selected>minggu ini</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="px-2 flex justify-between opacity-50">
                                <h2>Dishes</h2>
                                <h2 class="text-end">order</h2>
                            </div>
                            <div class="px-2 pt-3 pb-2">
                                <div class="px-2 py-3" v-for="(item, index) in topDishes.slice(0, 3)" :key="index">
                                    <div class="flex justify-between items-center">
                                        <div class="inline-flex items-center gap-2">
                                            <div class="w-12">
                                                <img class="w-10 h-10 rounded-full object-cover" :src="item.image"
                                                    :alt="item.name">
                                            </div>
                                            <h2>{{ item.name }}</h2>
                                        </div>
                                        <h2 class="text-end">{{ item.order }}</h2>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-4 ">
                        <div class="card p-2 ">
                            <div class="p-4 flex justify-between ">
                                <h2>Total Income</h2>
                                <select style="width: 8px;" class="py-1 pr-0  rounded-sm opacity-50"
                                    aria-label=".form-select-sm example">
                                    <option selected>Hari Ini</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <PieChart :series="PaymentSeries" :labels="PaymentLabels" :colors="PaymentColors" />
                        </div>
                    </div>
                    <div class="col-4 ">
                        <div class="card p-2 ">
                            <div class="p-4 flex justify-between ">
                                <h2>Metode Pembayaran</h2>
                                <select class="py-1 pr-0  rounded-sm opacity-50" aria-label=".form-select-sm example">
                                    <option selected>Hari Ini</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <PieChart :series="IncomeSeries" :labels="IncomeLabels" :colors="IncomeColors" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
