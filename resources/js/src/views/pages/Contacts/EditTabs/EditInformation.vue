<template>
  <div id="user-edit-tab-info">
    <!-- Picture Row -->
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="flex items-start flex-col sm:flex-row">
          <img
            :src="'/images/contacts/' + data.picture"
            class="mr-8 rounded h-24 w-24"
          />
          <div>
            <p class="text-lg font-medium mb-2 mt-4 sm:mt-0">{{ data.name }}</p>
            <clipper-upload
              class="inline-block px-5 py-3 mx-2 cursor-pointer bg-dark text-white rounded"
              v-model="imgURL"
              >Changer la photo</clipper-upload
            >

            <vs-button type="border" color="danger"
              >Supprimer la photo</vs-button
            >
          </div>
        </div>
      </div>
    </div>
    <!-- Picture preview -->
    <div class="flex" style="max-width: 100%">
      <clipper-basic
        :ratio="1"
        bg-color="black"
        class="flex-grow-3"
        ref="clipper"
        :src="imgURL"
        preview="my-preview"
        :rotate="rotation"
      ></clipper-basic>
      <clipper-preview
        name="my-preview"
        class="flex-grow-2 ml-2 my-clipper"
      ></clipper-preview>
    </div>
    <div class="centerx" v-if="imgURL">
      <vs-input-number
        min="0"
        max="360"
        step="90"
        v-model="rotation"
        label="Rotation"
      />
    </div>

    <!-- Content Row -->
    <div class="vx-row">
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Nom"
          v-model="data_local.name"
          v-validate="'required|alpha_spaces'"
          name="name"
        />
        <span class="text-danger text-sm" v-show="errors.has('name')">
          {{ errors.first("name") }}
        </span>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          v-validate="'alpha'"
          label="Prénom"
          type="prenom"
          v-model="data_local.prenom"
        />
        <span class="text-danger text-sm" v-show="errors.has('prenom')">{{
          errors.first("prenom")
        }}</span>
      </div>

      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          v-validate="'alpha_num'"
          label="Pseudo"
          name="username"
          v-model="data_local.username"
        />
        <span class="text-danger text-sm" v-show="errors.has('username')">{{
          errors.first("username")
        }}</span>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Email"
          v-model="data_local.email"
          type="email"
          v-validate="'required_if:mobile,|email'"
          name="email"
        />
        <span class="text-danger text-sm" v-show="errors.has('email')">{{
          errors.first("email")
        }}</span>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Mobile"
          v-model="data_local.mobile"
          type="tel"
          v-validate="'required_if:email,'"
          name="mobile"
        />
        <span class="text-danger text-sm" v-show="errors.has('mobile')">{{
          errors.first("mobile")
        }}</span>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <label class="vs-input--label">Pays</label>
        <v-select
          class="py-2"
          :clearable="false"
          :options="countryOptions"
          name="role"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
          v-model="data_local.country"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('country')">{{
          errors.first("country")
        }}</span>
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          label="Ville"
          v-model="data_local.state"
          name="state"
          v-validate="'alpha_space'"
        />
      </div>
      <div class="vx-col md:w-1/2 w-full mt-4" v-show="typeOptions !== []">
        <label class="vs-input--label">Type</label>
        <v-select
          :clearable="false"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
          v-model="type"
          :options="typeOptions"
        />
      </div>
      <div class="vx-col md:w-1/2 w-full">
        <vs-input
          class="w-full mt-4"
          v-validate="'alpha_spaces|required'"
          label="Titre de travail"
          name="title"
          v-model="data_local.title"
        />
        <span class="text-danger text-sm" v-show="errors.has('title')">{{
          errors.first("title")
        }}</span>
      </div>
      <div
        class="vx-col md:w-1/2 w-full mt-4"
        v-show="entrepriseOptions !== []"
      >
        <label class="vs-input--label">Entreprise(s)</label>
        <v-select
          multiple
          taggable
          push-tags
          :clearable="false"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
          v-model="entreprises"
          :options="entrepriseOptions"
        />
      </div>

      <div class="vx-col w-full">
        <vs-textarea
          class="w-full mt-4"
          label="Descriptif du poste"
          name="description_poste"
          v-model="data_local.description_poste"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('description_poste')"
          >{{ errors.first("description_poste") }}</span
        >
      </div>
      <div class="vx-col w-full">
        <vs-textarea
          class="w-full mt-4"
          label="Descriptif du media"
          name="description_media"
          v-model="data_local.description_media"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('description_media')"
          >{{ errors.first("description_media") }}</span
        >
      </div>
    </div>

    <v-divider style="padding: 20px 0" />

    <!-- Social -->

    <div class="vx-row">
      <!-- Col 1 -->
      <div class="vx-col w-full md:w-1/2">
        <vs-input
          class="w-full mt-4"
          icon-pack="feather"
          icon="icon-twitter"
          icon-no-border
          label="Twitter"
          v-model="data_local.social.twitter"
          v-validate="{
            regex: /^((https|http):\/\/)?(www\.)?twitter\.com\/@?[A-z0-9_]+\/?$/,
          }"
          name="twitter"
        />
        <span class="text-danger text-sm" v-show="errors.has('twitter')">{{
          errors.first("twitter")
        }}</span>

        <vs-input
          class="w-full mt-4"
          icon-pack="feather"
          icon="icon-instagram"
          icon-no-border
          label="instagram"
          v-model="data_local.social.instagram"
          v-validate="{
            regex: /^((https|http):\/\/)?(www\.)?instagram\.com\/[A-z0-9_]+\/?$/,
          }"
          name="instagram"
        />
        <span class="text-danger text-sm" v-show="errors.has('instagram')">{{
          errors.first("instagram")
        }}</span>

        <vs-input
          class="w-full mt-4"
          icon-pack="feather"
          icon="icon-linkedin"
          icon-no-border
          label="Linkedin"
          v-model="data_local.social.linkedin"
          v-validate="{
            regex: /^((https|http):\/\/)?(www\.)?linkedin\.com\/(in\/|company\/)[A-z0-9-_\.]+\/?$/,
          }"
          name="linkedin"
        />

        <span class="text-danger text-sm" v-show="errors.has('linkedin')">{{
          errors.first("linkedin")
        }}</span>
      </div>

      <!-- Col 2 -->
      <div class="vx-col w-full md:w-1/2"></div>
    </div>

    <!-- End Social -->

    <!-- Save & Reset Button -->
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            class="ml-auto mt-2"
            @click="save_changes"
            :disabled="!validateForm"
            >Save Changes</vs-button
          >
          <vs-button
            class="ml-4 mt-2"
            type="border"
            color="warning"
            @click="reset_data"
            >Reset</vs-button
          >
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
      countryOptions: [
        { label: "France", value: "France" },
        { label: "Belgique", value: "Belgique" },
        { label: "Suisse", value: "Suisse" },
        { label: "Etats-Unis", value: "Etats-Unis" },
        { label: "Maroc", value: "Maroc" },
        { label: "Canada", value: "Canada" },
        { label: "Espagne", value: "Espagne" },
        { label: "Allemagne", value: "Allemagne" },
      ],
      entrepriseOptions: [],
      entreprises: [],
      type: "",
      typeOptions: [],
    };
  },
  computed: {
    validateForm() {
      return !this.errors.any();
    },
  },
  mounted() {
    this.$http.get("/api/entreprises").then((res) => {
      let entreprises = [];
      res.data.map((item, index) => {
        entreprises[index] = { label: item.name, value: item.slug };
      });
      this.entrepriseOptions = entreprises;
    });

    //contact's own entreprises
    let entreprises = [];
    this.data_local.entreprises.map((item, index) => {
      entreprises[index] = { label: item.name, value: item.slug };
    });
    this.entreprises = entreprises;

    this.$http.get("/api/types").then((res) => {
      let types = [];
      res.data.map((item, index) => {
        types[index] = { label: item.name, value: item.id };
      });
      this.typeOptions = types;
    });
    this.type = {
      label: this.data_local.type.name,
      value: this.data_local.type.id,
    };
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
      information = {
        ...information,
        ...information.social,
        entreprises: this.entreprises,
        type_id: this.type.value,
      };
      information.social = null;
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
