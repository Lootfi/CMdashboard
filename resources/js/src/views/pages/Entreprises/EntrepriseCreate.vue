<template>
  <div id="page-user-view">
    <!-- Content Row -->
    <vs-card>
      <div slot="header">
        <h3>Ajouter Un Label</h3>
      </div>
      <div class="vx-row">
        <!-- name -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            label="Nom"
            name="name"
            v-model="name"
            v-validate="'alpha_spaces|required'"
          />
          <span class="text-danger text-sm" v-show="errors.has('name')">{{
            errors.first("name")
          }}</span>
        </div>
        <!-- website -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            v-validate="{
              regex: /(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/,
            }"
            label="Site Web"
            type="text"
            name="website"
            v-model="website"
          />
          <span class="text-danger text-sm" v-show="errors.has('website')">{{
            errors.first("website")
          }}</span>
        </div>
        <!-- maison -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            label="Maison de disque"
            name="maison"
            v-model="maison"
            v-validate="'alpha_spaces|required'"
          />
          <span class="text-danger text-sm" v-show="errors.has('maison')">{{
            errors.first("maison")
          }}</span>
        </div>
        <!-- phone -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            v-validate="{
              regex: /^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/,
            }"
            label="Numéro de téléphone"
            type="text"
            name="phone"
            v-model="phone"
          />
          <span class="text-danger text-sm" v-show="errors.has('phone')">{{
            errors.first("phone")
          }}</span>
        </div>

        <!-- sous_labels -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Divisions / Sous-labels</label>
          <v-select
            multiple
            taggable
            push-tags
            :clearable="false"
            v-model="sous_labels"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('sous_labels')"
            >{{ errors.first("sous_labels") }}</span
          >
        </div>

        <!-- artists -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Artistes signés</label>
          <v-select
            multiple
            taggable
            push-tags
            :clearable="false"
            v-model="artists"
          />
          <span class="text-danger text-sm" v-show="errors.has('artists')">{{
            errors.first("artists")
          }}</span>
        </div>

        <!-- genres -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Genre musicaux</label>
          <v-select
            multiple
            taggable
            push-tags
            :clearable="false"
            v-model="genres"
          />
          <span class="text-danger text-sm" v-show="errors.has('genres')">{{
            errors.first("genres")
          }}</span>
        </div>

        <!-- indepndant -->
        <div class="vx-col md:w-1/2 w-full mt-10 mb-5">
          <vs-checkbox v-model="indepndant">Indépendant</vs-checkbox>
        </div>

        <!-- address -->
        <div class="vx-col w-full">
          <vs-input
            class="w-full mt-4"
            label="Adresse postale"
            name="address"
            v-model="address"
            v-validate="'required'"
          />
          <span class="text-danger text-sm" v-show="errors.has('address')">{{
            errors.first("address")
          }}</span>
        </div>

        <!-- description -->
        <div class="vx-col w-full">
          <vs-textarea
            class="w-full mt-4"
            label="Description"
            name="description"
            v-model="description"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('description')"
            >{{ errors.first("description") }}</span
          >
        </div>
      </div>

      <div class="vx-row">
        <div class="my-4">
          <clipper-upload
            class="inline-block p-2 my-2 bg-primary rounded text-white"
            v-model="imgURL"
            >Importer Le logo de label</clipper-upload
          >
          <div class="flex" style="max-width: 100%">
            <clipper-basic
              :ratio="1"
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
        </div>
      </div>
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
            v-model="twitter"
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
            v-model="instagram"
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
            icon="icon-facebook"
            icon-no-border
            label="facebook"
            v-model="facebook"
            v-validate="{
              regex: /^((https|http):\/\/)?(www\.)?facebook\.com\/[A-z0-9_]+\/?$/,
            }"
            name="facebook"
          />

          <span class="text-danger text-sm" v-show="errors.has('facebook')">{{
            errors.first("facebook")
          }}</span>
        </div>
      </div>

      <!-- End Social -->
    </vs-card>
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            class="ml-auto mt-2"
            id="save-button"
            @click="handleLabelSubmit"
            :disabled="isSending"
            >{{ $t("save") }}</vs-button
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import vSelect from "vue-select";
import Cropper from "cropperjs";
import debounce from "lodash/debounce";

export default {
  components: {
    Cropper,
    vSelect,
  },
  data() {
    return {
      name: "Nom",
      website: "https://www.site.com",
      maison: "Maison",
      phone: "+213699499071",
      sous_labels: ["SL1", "SL2"],
      artists: ["A1", "A2"],
      genres: ["G1", "G2"],
      address: "215 Cité Nom Nom",
      indepndant: false,
      description: "Lorem Ipsum Text",
      facebook: "https://www.facebook.com/page",
      instagram: "https://www.instagram.com/page",
      twitter: "https://twitter.com/page",
      imgURL: "",
      rotation: 0,
      avatar: "",
      isSending: false,
    };
  },
  methods: {
    handleLabelSubmit(e) {
      e.preventDefault();
      this.$validator.validateAll().then((result) => {
        if (result) {
          const canvas = this.$refs.clipper.clip();
          const ResultAvatar = canvas.toDataURL("image/jpeg", 1);
          this.isSending = true;
          this.$vs.loading({
            container: "#save-button",
            scale: 0.45,
          });
          let self = this;
          this.$http
            .post(`/api/entreprises/create`, {
              name: this.name,
              website: this.website,
              maison: this.maison,
              phone: this.phone,
              sous_labels: this.sous_labels,
              artists: this.artists,
              genres: this.genres,
              address: this.address,
              indepndant: this.indepndant,
              description: this.description,
              facebook: this.facebook,
              instagram: this.instagram,
              twitter: this.twitter,
              avatar: ResultAvatar,
            })
            .then((res) => {
              self.isSending = false;
              self.$vs.loading.close("#save-button > .con-vs-loading");
              self.$vs.dialog({
                color: "primary",
                title: ``,
                text: "Editeur crée ! ",
              });
              this.$router.push("/entreprises");
            })
            .catch(function (err) {
              self.isSending = false;
              self.$vs.loading.close("#save-button > .con-vs-loading");
              self.$vs.dialog({
                color: "danger",
                title: ``,
                text: err.response.data,
              });
            });
        }
      });
    },
  },
};
</script>
