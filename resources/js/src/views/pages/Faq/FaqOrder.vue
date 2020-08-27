<template>
  <div>
    <vs-list>
      <vs-list-header title="Ordre des FAQs" color="primary"></vs-list-header>

      <draggable :list="faqs_local" :move="checkMove">
        <transition-group>
          <vs-list-item
            class="list-item"
            v-for="listItem in faqs_local"
            :key="listItem.question"
            :title="listItem.question"
          >
            <vs-avatar slot="avatar" :text="(listItem.order + 1).toFixed()" />
          </vs-list-item>
        </transition-group>
      </draggable>
    </vs-list>

    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button class="ml-auto mt-2" @click="save_changes">Sauvegarder le changement</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import draggable from "vuedraggable";

export default {
  props: {
    data: {
      type: Array,
      required: true,
    },
    changeOrder: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      faqs_local: this.data,
    };
  },
  components: {
    draggable,
  },
  methods: {
    checkMove: function (e) {
      console.log(this.faqs_local);
    },
    save_changes: function () {
      this.$http
        .post("/api/faqs", { faqs: this.faqs_local })
        .then((res) => window.location.reload());
    },
  },
};
</script>
<style>
.list-item {
  transition: all 1s;
}
</style>
        