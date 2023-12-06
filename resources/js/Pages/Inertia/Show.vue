<script setup>
import { Inertia } from '@inertiajs/inertia';

defineProps({
  id: String,
  blog: Object,
});

// const deleteConfirm = id => {
//   // console.log(id);
//   Inertia.delete(`/inertia/${id}`, {
//     onBefore: () => confirm('本当に削除しますか？')
//   })
// }

const deleteConfirm = id => {
  // console.log(id);
  Inertia.delete(route('inertia.delete', id), {
    onBefore: () => confirm('本当に削除しますか？')
  })
}

</script>
<template>
  ID:{{ id }}<br>
  タイトル:{{ blog.title }}<br>
  本文:{{ blog.content }}<br>
  <button @click="deleteConfirm(blog.id)">削除</button>
</template>