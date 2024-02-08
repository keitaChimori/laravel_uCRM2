<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed, onMounted, reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import dayjs from 'dayjs';

// Purchaseコントローラーから受け取り
const props = defineProps({
  'order': Object,
  'items': Array,
})


onMounted(() => {
  console.log(props.order);
  console.log(props.items);
})


</script>

<template>
  <Head title="購入画面 詳細画面" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">購入画面 詳細画面</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="storePurchase">
                <div class="container px-5 py-8 mx-auto">
                  <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="date" class="leading-7 text-sm text-gray-600">購入日</label>
                          <div id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ dayjs(props.order.created_at).format('YYYY/MM/DD') }}
                          </div>
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="customer" class="leading-7 text-sm text-gray-600">会員名</label>
                          <div id="customer" name="customer" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ props.order.customer_name }}
                          </div>
                        </div>
                      </div>

                      <div class="w-full mx-auto overflow-auto mt-8">
                        <table class="table-auto w-full text-left whitespace-no-wrap">
                          <thead>
                            <tr>
                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                商品ID</th>
                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                商品名</th>
                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                商品金額</th>
                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                数量</th>
                              <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                小計</th>
                              <th class="w-10 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br">
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="item in props.items" :key="item.id">
                              <td class="border-b-2 border-grat-200 px-4 py-3">
                                <Link :href="route('items.show', { item: item.item_id })" class="text-blue-400" target="_blank">{{ item.item_id}}
                                </Link>
                              </td>
                              <td class="border-b-2 border-grat-200 px-4 py-3">{{ item.item_name }}</td>
                              <td class="border-b-2 border-grat-200 px-4 py-3">{{ (item.item_price).toLocaleString() }}円</td>
                              <td class="border-b-2 border-grat-200 px-4 py-3">{{ item.quantity }}</td>
                              <td class="border-b-2 border-grat-200 px-4 py-3">{{ (item.subtotal).toLocaleString() }}円</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="p-2 w-full">
                        <div>
                          <label for="price" class="leading-7 text-sm text-gray-600">合計金額</label><br>
                          <div class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ props.order.total }}円
                          </div>
                        </div>
                      </div>

                      <div class="p-2 w-full">
                        <div>
                          <label for="status" class="leading-7 text-sm text-gray-600">ステータス</label><br>
                          <div v-if="props.order.status == true" class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out text-green-500">
                            発注
                          </div>
                          <div v-else class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out text-red-500">
                            キャンセル
                          </div>
                        </div>
                      </div>

                      <div v-if="props.order.status == false" class="p-2 w-full">
                        <div>
                          <label for="price" class="leading-7 text-sm text-gray-600">キャンセル日</label><br>
                          <div class="w-full bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ dayjs(props.order.update_at).format('YYYY/MM/DD') }}
                          </div>
                        </div>
                      </div>

                      <div v-if="props.order.status == true" class="p-2 w-full">
                        <Link as="button" :href="route('purchases.edit', { purchase: props.order.id })"
                        class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">編集する</Link>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
