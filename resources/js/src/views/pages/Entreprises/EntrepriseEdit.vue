<template>
  <div id="page-user-view" v-show="entreprise != {}">
    <!-- Content Row -->
    <vs-card>
      <div class="vx-row">
        <div class="vx-col w-full">
          <div class="flex items-start flex-col sm:flex-row">
            <img :src="entreprise.LogoLink" class="mr-8 rounded h-24 w-24" />
            <div>
              <p class="text-lg font-medium mb-2 mt-4 sm:mt-0">
                {{ entreprise.name }}
              </p>
              <clipper-upload
                class="inline-block px-5 py-3 mx-2 cursor-pointer bg-dark text-white rounded"
                v-model="imgURL"
                name="logo"
                v-validate="'required'"
                >Changer la photo</clipper-upload
              >
              <span class="text-danger text-sm" v-show="errors.has('logo')">{{
                errors.first("logo")
              }}</span>
            </div>
          </div>
        </div>
        <div class="flex" style="max-width: 100%">
          <clipper-basic
            :ratio="1"
            bg-color="black"
            class="flex-grow-3"
            :init-width="100"
            :init-height="100"
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
      <div class="vx-row">
        <!-- name -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            label="Nom"
            name="name"
            v-model="entreprise.name"
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
            v-model="entreprise.website"
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
            v-model="entreprise.maison"
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
            v-model="entreprise.phone"
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
            v-model="entreprise.sous_labels"
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
            v-model="entreprise.artists"
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
            v-model="entreprise.genres"
          />
          <span class="text-danger text-sm" v-show="errors.has('genres')">{{
            errors.first("genres")
          }}</span>
        </div>

        <!-- indepndant -->
        <div class="vx-col md:w-1/2 w-full mt-10 mb-5">
          <vs-checkbox v-model="entreprise.indepndant">Indépendant</vs-checkbox>
        </div>

        <!-- address -->
        <div class="vx-col w-full">
          <vs-input
            class="w-full mt-4"
            label="Adresse postale"
            name="address"
            v-model="entreprise.address"
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
            v-model="entreprise.description"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('description')"
            >{{ errors.first("description") }}</span
          >
        </div>
      </div>

      <!-- <div class="vx-row">
        <div class="my-4">
          <clipper-upload
            class="inline-block p-2 my-2 bg-primary rounded text-white"
            v-model="entreprise.imgURL"
            >Importer Le logo de label</clipper-upload
          >
          <div class="flex" style="max-width: 100%">
            <clipper-basic
              :ratio="1"
              class="flex-grow-3"
              ref="clipper"
              :src="imgURL"
              preview="my-preview"
              init-width="100"
              init-height="100"
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
      </div> -->
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
            v-model="entreprise.twitter"
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
            v-model="entreprise.instagram"
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
            v-model="entreprise.facebook"
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
      entreprise: {},
      imgURL: "",
      rotation: 0,
      avatar: "",
      isSending: false,
    };
  },
  mounted() {
    this.$vs.loading({
      type: "corners",
      text: "Patientez s'il vous plait",
    });
    let self = this;

    self.$http
      .get(`/api/entreprises/${self.$route.params.slug}`)
      .then((response) => {
        if (response.data == "Entreprise not found") {
          self.$router.push("/entreprises");
        }
        self.$vs.loading.close();
        let entreprise = response.data;
        entreprise.sous_labels = JSON.parse(response.data.sous_labels);
        entreprise.artists = JSON.parse(entreprise.artists);
        entreprise.genres = JSON.parse(entreprise.genres);
        self.entreprise = entreprise;
        self.imgURL = response.data.LogoLink;
      })
      .catch(function (error) {
        self.$vs.loading.close();
        console.error(error.response.data);
        self.$vs.dialog({
          color: "danger",
          title: ``,
          text: "Erreur",
        });
      });
  },
  methods: {
    handleLabelSubmit(e) {
      e.preventDefault();
      this.$validator.validateAll().then((result) => {
        if (result) {
          let ResultAvatar = "";
          if (!this.imgURL.match("/images/labels")) {
            const canvas = this.$refs.clipper.clip();
            ResultAvatar = canvas.toDataURL("image/jpeg", 1);
          }

          this.isSending = true;
          this.$vs.loading({
            container: "#save-button",
            scale: 0.45,
          });
          let self = this;
          this.$http
            .post(`/api/entreprises/${this.$route.params.slug}/edit`, {
              name: this.entreprise.name,
              website: this.entreprise.website,
              maison: this.entreprise.maison,
              phone: this.entreprise.phone,
              sous_labels: this.entreprise.sous_labels,
              artists: this.entreprise.artists,
              genres: this.entreprise.genres,
              address: this.entreprise.address,
              indepndant: this.entreprise.indepndant,
              description: this.entreprise.description,
              facebook: this.entreprise.facebook,
              instagram: this.entreprise.instagram,
              twitter: this.entreprise.twitter,
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
