<script setup>

</script>
<template>
    <div class="sticky top-0 ml-64 bg-white p-8 text-white z-50 flex justify-between">
        <div class="w-1/6">
            <form action="#" class="search">
                <button class="search__button">
                    <div class="search__icon">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 20 20">
                            <title>magnifying-glass</title>
                            <path
                                d="M17.545 15.467l-3.779-3.779c0.57-0.935 0.898-2.035 0.898-3.21 0-3.417-2.961-6.377-6.378-6.377s-6.186 2.769-6.186 6.186c0 3.416 2.961 6.377 6.377 6.377 1.137 0 2.2-0.309 3.115-0.844l3.799 3.801c0.372 0.371 0.975 0.371 1.346 0l0.943-0.943c0.371-0.371 0.236-0.84-0.135-1.211zM4.004 8.287c0-2.366 1.917-4.283 4.282-4.283s4.474 2.107 4.474 4.474c0 2.365-1.918 4.283-4.283 4.283s-4.473-2.109-4.473-4.474z">
                            </path>
                        </svg>
                    </div>
                </button>
                <input type="text" class="search__input" placeholder="Search...">
            </form>
        </div>
        <div class="inline-flex justify-end items-center">
            <ul class="inline-flex border-b-2">
                <a href="">
                    <li class="px-3 py-1 text-base font-semibold">
                        Kemarin
                    </li>
                </a>
                <a href="">
                    <li class="px-3 py-1 text-base font-semibold active" >
                        Hari ini
                    </li>
                </a>
                <a href="">
                    <li class="px-3 py-1 text-base font-semibold">
                        Minggu ini
                    </li>
                </a>
                <a href="">
                    <li class="px-3 py-1 text-base font-semibold">
                        Bulan
                    </li>
                </a>
                <a href="">
                    <li class="px-3 py-1 text-base font-semibold">
                        Tahun
                    </li>
                </a>
            </ul>
        </div>
    </div>

</template>
<style scoped>
.actives{
    border-bottom: 3px solid #bc06f3;
}
li {
    border-bottom: 2.5px solid #bc06f336;
}
.search {
  flex: 0 0 90%;
  display: flex;
  align-items: center;
}

.search__input {
  font-family: "Open Sans", sans-serif;
  font-size: 17px;
  color: #333333;
  background-color: #f4f2f2;
  border: none;
  padding: 10px 72px 10px 42px;
  border-radius: 1000px;
  width: 90%;
  margin-left: -34px;
  margin-right: -66px;
  transition: all 0.2s;
}

.search__input:focus {
  outline: none;
  width: 91%;
  background-color: #f0eeee;
}

.search__input::-webkit-input-placeholder {
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 5px;
  font-weight: 500;
  color: #999999;
}

.search__button {
  border: none;
  background-color: transparent;
  z-index: 999;
  cursor: pointer;
}

.search__button:focus {
  outline: none;
}

.search__button:active {
  transform: translateY(2px);
}

.search__icon {
  height: 20px;
  width: 20px;
  fill: #999999;
}


</style>