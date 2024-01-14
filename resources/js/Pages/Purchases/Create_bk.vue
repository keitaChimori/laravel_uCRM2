<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getToday } from '@/common';
import { computed, onMounted, reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';


// Purchaseコントローラーから受け取り
const props = defineProps({
  'customers': Array,
  'items': Array,
})

onMounted(() => {
  form.date = getToday(),
    props.items.forEach(item => {
      itemList.value.push({
        id: item.id,
        name: item.name,
        price: item.price,
        quantity: 0
      });
    });
});

const itemList = ref([]);

const quantity = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

// 合計金額の計算
const totalPrice = computed(() => {
  let total = 0;
  itemList.value.forEach(item => {
    total += item.price * item.quantity
  })
  return total;
})

// Purchaseコントローラーへ購入情報送る
const form = reactive({
  date: null,
  customer_id: null,
  status: true,
  items: []
})

// 登録処理実行
const storePurchase = () => {
  itemList.value.forEach(item => {
    if (item.quantity > 0) {
      form.items.push({
        id: item.id,
        quantity: item.quantity
      })
    }
  })
  Inertia.post(route('purchases.store', form));
}

</script>
<template>
  <form @submit.prevent="storePurchase">
    日付<br>
    <input type="date" name="date" v-model="form.date"><br>
    会員名<br>
    <select name="customer" v-model="form.customer_id">
      <option v-for="customer in customers" :value="customer.id" :key="customer.id">
        {{ customer.id }} : {{ customer.name }}
      </option>
    </select><br>
    商品・サービス<br>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <td>商品名</td>
          <td>金額</td>
          <td>数量</td>
          <td>小計</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in itemList">
          <th>{{ item.id }}</th>
          <th>{{ item.name }}</th>
          <th>{{ item.price.toLocaleString() }}円</th>
          <th>
            <select name="quantity" v-model="item.quantity">
              <option v-for="q in quantity" :value="q" :key="q">{{ q }}</option>
            </select>
          </th>
          <th>
            {{ (item.quantity * item.price).toLocaleString() }}円
          </th>
        </tr>
      </tbody>
      <br>
      合計：{{ totalPrice.toLocaleString() }}円<br>
      <button>登録する</button>
    </table>
  </form>
</template>