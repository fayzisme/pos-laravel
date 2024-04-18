<script setup>
import { defineProps } from 'vue';
const props = defineProps({
    productName: { type: String, default: "Total sale" },
    latsmonth: { type: String, default: "from last month" },
    percent: { type: String },
    productPrice: { type: String },
});
</script>
<template>
    <div class="card border mb-0">
        <div class="flex gap-3">
            <div class="py-3 pl-2 w-1/5">
                <div class="rounded-full p-2  flex items-center justify-center icon-cart-box">
                    <slot name="icon">
                        <i class="bi bi-tag text-lg"></i>
                    </slot>
                </div>
            </div>
            <div width="32px" class="py-3 w-2/3">
                <div class="text-base font-semibold">{{ productName }}</div>
                <div class="flex">
                    <h5 class="font-medium text-sm text-purple">
                        <span class="text-green">{{ percent }}%</span>
                        {{ latsmonth }}
                    </h5>
                </div>
                <h4 class="text-lg font-bold">{{ productPrice }}</h4>
            </div>
            <div class="py-3 pr-2 w-1/5 grid items-end">
                <div class="rounded-full p-2 iconse">
                    <i class="bi bi-graph-up-arrow  flex items-center justify-center"></i>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.iconse {
    background-color: #FCF5FC;
    width: 2em;
    height: 2em;
    border-radius: 50%;
    text-align: center;
    padding: 6px 0px;
    margin: 0 auto;
}

.icon-cart-box {
    background-color: #FCF5FC;
    width: 2.5em;
    height: 2.5em;
    border-radius: 50%;
    text-align: center;
    padding: 6px 0px;
    margin: 0 auto;
}
.bi::before{
    font-weight: 700 !important;
}
</style>
