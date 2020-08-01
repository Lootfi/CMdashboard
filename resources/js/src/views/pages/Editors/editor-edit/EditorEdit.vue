<!-- =========================================================================================
  File Name: UserEdit.vue
  Description: User Edit Page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="page-user-edit">
    <vs-alert color="danger" title="User Not Found" :active.sync="user_not_found">
      <span>Editeur not found</span>
      <span>
        <span>Check</span>
        <router-link :to="{ name: 'editors' }" class="text-inherit underline">Editeurs</router-link>
      </span>
    </vs-alert>

    <vx-card v-if="user_data">
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs v-model="activeTab" class="tab-action-btn-fill-conatiner">
          <vs-tab label="Account" icon-pack="feather" icon="icon-user">
            <div class="tab-text">
              <editor-edit-tab-account class="mt-4" :data="user_data" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>
  </div>
</template>

<script>
import EditorEditTabAccount from "./EditorEditTabAccount.vue";

export default {
  components: {
    EditorEditTabAccount,
  },
  data() {
    return {
      user_data: null,
      user_not_found: false,

      /*
        This property is created for fetching latest data from API when tab is changed

        Please check it's watcher
      */
      activeTab: 1,
    };
  },
  mounted() {
    this.fetch_user_data(this.$route.params.slug);
  },
  methods: {
    fetch_user_data(slug) {
      this.$http
        .get(`/api/editors/${slug}`)
        .then((res) => {
          this.user_data = res.data;
        })
        .catch(function (error) {
          this.user_not_found = true;
        });
    },
  },
};
</script>