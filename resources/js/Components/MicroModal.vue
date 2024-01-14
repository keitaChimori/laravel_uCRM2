<script setup>
import axios from 'axios';
import { ref, reactive, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

// onMounted(() => {
//   axios.get('/api/user')
//   .then(res => {
//     console.log(res)
//   })
// })

const search = ref(''); // 検索キーワード
const customers = reactive({}); // 顧客検索結果一覧
const searchCustomers = async () => {
  try {
    await axios.get(`/api/searchCustomers/?search=${search.value}`)
      .then(res => {
        // console.log(res.data);
        customers.value = res.data;
        // console.log(customers.value.data);
      })
    toggleStatus(); // モーダル表示
  } catch (e) {
    console.log(e.message);
  }
}

// モーダル表示
const isShow = ref(false);
const toggleStatus = () => {
  isShow.value = !isShow.value;
}

const emit = defineEmits(['update:customerId']);
const setCustomer = e => {
  search.value = e.name;
  emit('update:customerId', e.id);
  toggleStatus();
}

</script>
<template>
  <!-- 会員名入力フォーム -->
  <input type="text" name="customer" v-model="search" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
  <button @click="searchCustomers" type="button" data-micromodal-trigger="modal-1" class="flex mx-auto text-white bg-teal-500 border-0 py-2 px-8 focus:outline-none hover:bg-teal-600 rounded text-lg mt-2">検索する</button>

  <div v-show="isShow" class="modal" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-2/3" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">顧客名検索結果</h2>
          <button type="button" @click="toggleStatus" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <div v-if="customers.value" class="lg:w-2/3 w-full mx-auto overflow-auto">
            <table class="table-auto w-full text-left whitespace-no-wrap">
              <thead>
                <tr>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">ID</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">氏名</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カナ</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">電話番号</th>
                  <th class="w-10 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="customer in customers.value.data" key="customer.id">
                  <td class="border-b-2 border-grat-200 px-4 py-3">
                    <button @click="setCustomer({id:customer.id, name:customer.name})" class="text-blue-400">{{ customer.id }}</button>
                    <!-- <Link :href="route('customers.show', { customer: customer.id })" class="text-blue-400">{{ customer.id }}</Link> -->
                  </td>
                  <td class="border-b-2 border-grat-200 px-4 py-3">{{ customer.name }}</td>
                  <td class="border-b-2 border-grat-200 px-4 py-3">{{ customer.kana }}</td>
                  <td class="border-b-2 border-grat-200 px-4 py-3">{{ customer.tel }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
        <footer class="modal__footer text-center">
          <button type="button" @click="toggleStatus" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">閉じる</button>
        </footer>
      </div>
    </div>
  </div>
</template>