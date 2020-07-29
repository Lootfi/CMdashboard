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
    <vs-alert color="danger" title="Contact Not Found" :active.sync="contact_not_found">
      <span>Contact record with slug: {{ $route.params.slug }} not found.</span>
      <span>
        <span>Check</span>
        <router-link :to="{name:'contacts'}" class="text-inherit underline">All Contacts</router-link>
      </span>
    </vs-alert>

    <vx-card v-if="contact">
      <div slot="no-body" class="tabs-container px-6 pt-6">
        <vs-tabs v-model="activeTab" class="tab-action-btn-fill-conatiner">
          <vs-tab label="Information" icon-pack="feather" icon="icon-info">
            <div class="tab-text">
              <edit-information class="mt-4" :data="contact" />
            </div>
          </vs-tab>
          <vs-tab label="Social" icon-pack="feather" icon="icon-share-2">
            <div class="tab-text">
              <edit-social class="mt-4" :data="contact" />
            </div>
          </vs-tab>
        </vs-tabs>
      </div>
    </vx-card>
  </div>
</template>

<script>
import EditInformation from "./EditTabs/EditInformation.vue";
import EditSocial from "./EditTabs/EditSocial.vue";
export default {
  components: {
    EditInformation,
    EditSocial,
  },
  data() {
    return {
      contact: null,
      contact_not_found: false,

      /*
        This property is created for fetching latest data from API when tab is changed

        Please check it's watcher
      */
      activeTab: 0,
    };
  },
  watch: {
    activeTab() {
      this.fetch_contact_data(this.$route.params.slug);
    },
  },
  methods: {
    fetch_contact_data(slug) {
      this.$http
        .get("/api/contacts/" + slug)
        .then((res) => {
          console.log(res.data);
          if (res.data.social == null) {
            res.data.social = { twitter: "", linkedin: "", instagram: "" };
          }
          this.contact = res.data;
        })
        .catch((err) => {
          if (err.response.status === 404) {
            this.contact_not_found = true;
            return;
          }
          console.error(err.response.data);
        });
    },
  },
  created() {
    this.fetch_contact_data(this.$route.params.slug);
  },
};
</script>
