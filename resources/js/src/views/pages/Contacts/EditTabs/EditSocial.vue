<!-- =========================================================================================
  File Name: UserEditTabSocial.vue
  Description: User Edit Social Tab content
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="user-edit-tab-info">
    <div class="vx-row">
      <!-- Col 1 -->
      <div class="vx-col w-full md:w-1/2">
        <vs-input
          class="w-full"
          v-model="data_local.social.twitter"
          icon-pack="feather"
          icon="icon-twitter"
          label="Twitter"
          icon-no-border
          v-validate="'url:require_protocol'"
          name="twitter"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('twitter')"
        >{{ errors.first('twitter') }}</span>

        <vs-input
          class="w-full mt-4"
          v-model="data_local.social.instagram"
          icon-pack="feather"
          icon="icon-instagram"
          label="Instagram"
          icon-no-border
          v-validate="'url:require_protocol'"
          name="instagram"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('instagram')"
        >{{ errors.first('instagram') }}</span>

        <vs-input
          class="w-full mt-4"
          v-model="data_local.social.linkedin"
          icon-pack="feather"
          icon="icon-linkedin"
          label="LinkedIn"
          icon-no-border
          v-validate="'url:require_protocol'"
          name="linkedin"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('linkedin')"
        >{{ errors.first('linkedin') }}</span>
      </div>

      <!-- Col 2 -->
      <div class="vx-col w-full md:w-1/2"></div>
    </div>

    <!-- Save & Reset Button -->
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            class="ml-auto mt-2"
            @click="save_changes"
            :disabled="!validateForm"
          >Save Changes</vs-button>
          <vs-button class="ml-4 mt-2" type="border" color="warning" @click="reset_data">Reset</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      data_local: JSON.parse(JSON.stringify(this.data)),
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any();
    },
  },
  methods: {
    save_changes() {
      /* eslint-disable */
      if (!this.validateForm) return;

      let slug = this.$route.params.slug;
      let information = this.data_local;
      information["picture"] = null;
      information = { ...information, ...information.social };

      this.$http
        .post("/api/contacts/" + slug + "/edit", information)
        .then((res) => {
          this.$router.push("/contacts/" + slug);
        })
        .catch((err) => console.log(err.response.data));

      /* eslint-enable */
    },
    reset_data() {
      this.data_local = Object.assign({}, this.data);
    },
  },
};
</script>
