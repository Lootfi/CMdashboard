<template>
  <div>
    <h1 class="mb-4">Tableau de bord</h1>
    <price />
    <vs-divider />
    <prospects />
  </div>
</template>

<script>
import Price from "@/views/components/price/Price";
import Prospects from "@/views/components/prospects/Prospects";
export default {
  components: {
    Price,
    Prospects,
  },
};
</script>