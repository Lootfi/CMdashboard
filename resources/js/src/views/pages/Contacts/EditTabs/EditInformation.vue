<template>
  <div id="user-edit-tab-info">
    <!-- Picture Row -->
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="flex items-start flex-col sm:flex-row">
          <img :src="'/images/contacts/' + data.picture" class="mr-8 rounded h-24 w-24" />
          <div>
            <p class="text-lg font-medium mb-2 mt-4 sm:mt-0">{{ data.name }}</p>
            <clipper-upload
              class="inline-block px-5 py-3 mx-2 cursor-pointer bg-dark text-white rounded"
              v-model="imgURL"
            >Change Picture</clipper-upload>

            <vs-button type="border" color="danger">Remove Picture</vs-button>
          </div>
        </div>
      </div>
    </div>
    <!-- Picture preview -->
    <div class="flex" style="max-width: 100%;">
      <clipper-basic
        :ratio="1"
        bg-color="black"
        class="flex-grow-3"
        ref="clipper"
        :src="imgURL"
        preview="my-preview"
        :rotate="rotation"
      ></clipper-basic>
      <clipper-preview name="my-preview" class="flex-grow-2 ml-2 my-clipper"></clipper-preview>
    </div>
    <div class="centerx" v-if="imgURL">
      <vs-input-number min="0" max="360" step="90" v-model="rotation" label="Rotation" />
    </div>

    <!-- Content Row -->
    <div class="vx-row">
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Name"
          v-model="data_local.name"
          v-validate="'required|alpha_spaces'"
          name="name"
        />
        <span class="text-danger text-sm" v-show="errors.has('name')">
          {{
          errors.first("name")
          }}
        </span>

        <vs-input
          class="w-full mt-4"
          label="Email"
          v-model="data_local.email"
          type="email"
          v-validate="'required|email'"
          name="email"
        />
        <span class="text-danger text-sm" v-show="errors.has('email')">{{ errors.first("email") }}</span>

        <vs-input
          class="w-full mt-4"
          label="Mobile"
          v-model="data_local.mobile"
          type="tel"
          v-validate="'required'"
          name="mobile"
        />
        <span class="text-danger text-sm" v-show="errors.has('mobile')">{{ errors.first("mobile") }}</span>
      </div>

      <!-- <div class="vx-col md:w-1/2 w-full">
        <div class="mt-4">
          <label class="vs-input--label">Status</label>
          <v-select
            v-model="status_local"
            :clearable="false"
            :options="statusOptions"
            v-validate="'required'"
            name="status"
            :dir="$vs.rtl ? 'rtl' : 'ltr'"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('status')"
          >{{ errors.first('status') }}</span>
        </div>

        <div class="mt-4">
          <label class="vs-input--label">Role</label>
          <v-select
            v-model="role_local"
            :clearable="false"
            :options="roleOptions"
            v-validate="'required'"
            name="role"
            :dir="$vs.rtl ? 'rtl' : 'ltr'"
          />
          <span class="text-danger text-sm" v-show="errors.has('role')">{{ errors.first('role') }}</span>
        </div>

        <vs-input
          class="w-full mt-4"
          label="Company"
          v-model="data_local.company"
          v-validate="'alpha_spaces'"
          name="company"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('company')"
        >{{ errors.first('company') }}</span>
      </div>-->
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
import vSelect from "vue-select";
import Cropper from "cropperjs";

export default {
  components: {
    vSelect,
    Cropper,
  },
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      data_local: JSON.parse(JSON.stringify(this.data)),
      rotation: 0,
      imgURL: "",
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any();
    },
  },
  methods: {
    capitalize(str) {
      return str.slice(0, 1).toUpperCase() + str.slice(1, str.length);
    },
    save_changes() {
      /* eslint-disable */
      if (!this.validateForm) return;

      let slug = this.$route.params.slug;
      let information = this.data_local;

      if (this.imgURL)
        information["picture"] = this.$refs.clipper
          .clip()
          .toDataURL("image/jpeg", 1);
      else information["picture"] = null;

      this.$http
        .post("/api/contacts/" + slug + "/edit", information)
        .then((res) => {
          this.$router.push("/contacts/" + slug);
        })
        .catch((err) => console.log(err.response.data));

      /* eslint-enable */
    },
    reset_data() {
      this.data_local = JSON.parse(JSON.stringify(this.data));
    },
  },
};
</script>
