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
          <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first("name") }}</span>
        </div>
        <!-- website -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            v-validate="{regex: /(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/ }"
            label="Site Web"
            type="text"
            name="website"
            v-model="website"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('website')"
          >{{ errors.first("website") }}</span>
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
          <span
            class="text-danger text-sm"
            v-show="errors.has('maison')"
          >{{ errors.first("maison") }}</span>
        </div>
        <!-- phone -->
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            v-validate="{regex: /^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/}"
            label="Numéro de téléphone"
            type="text"
            name="phone"
            v-model="phone"
          />
          <span class="text-danger text-sm" v-show="errors.has('phone')">{{ errors.first("phone") }}</span>
        </div>

        <!-- sous_labels -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Divisions / Sous-labels</label>
          <v-select multiple taggable push-tags :clearable="false" v-model="sous_labels" />
          <span
            class="text-danger text-sm"
            v-show="errors.has('sous_labels')"
          >{{ errors.first("sous_labels") }}</span>
        </div>

        <!-- artists -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Artistes signés</label>
          <v-select multiple taggable push-tags :clearable="false" v-model="artists" />
          <span
            class="text-danger text-sm"
            v-show="errors.has('artists')"
          >{{ errors.first("artists") }}</span>
        </div>

        <!-- genres -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Genre musicaux</label>
          <v-select multiple taggable push-tags :clearable="false" v-model="genres" />
          <span
            class="text-danger text-sm"
            v-show="errors.has('genres')"
          >{{ errors.first("genres") }}</span>
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
          <span
            class="text-danger text-sm"
            v-show="errors.has('address')"
          >{{ errors.first("address") }}</span>
        </div>

        <!-- indepndant -->
        <div class="vx-col md:w-1/2 w-full mt-4">
          <vs-checkbox v-model="indepndant">Indépendant</vs-checkbox>
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
          >{{ errors.first("description") }}</span>
        </div>
      </div>

      <div class="vx-row">
        <div class="my-4">
          <clipper-upload
            class="inline-block p-2 my-2 bg-primary rounded text-white"
            v-model="imgURL"
          >Importer Le logo de label</clipper-upload>
          <div class="flex" style="max-width: 100%;">
            <clipper-basic
              :ratio="1"
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
            v-validate="{regex: /^((https|http):\/\/)?(www\.)?twitter\.com\/@?[A-z0-9_]+\/?$/}"
            name="twitter"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('twitter')"
          >{{ errors.first('twitter') }}</span>

          <vs-input
            class="w-full mt-4"
            icon-pack="feather"
            icon="icon-instagram"
            icon-no-border
            label="instagram"
            v-model="instagram"
            v-validate="{regex: /^((https|http):\/\/)?(www\.)?instagram\.com\/[A-z0-9_]+\/?$/}"
            name="instagram"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('instagram')"
          >{{ errors.first('instagram') }}</span>

          <vs-input
            class="w-full mt-4"
            icon-pack="feather"
            icon="icon-facebook"
            icon-no-border
            label="facebook"
            v-model="facebook"
            v-validate="{regex: /^((https|http):\/\/)?(www\.)?facebook\.com\/[A-z0-9_]+\/?$/}"
            name="facebook"
          />

          <span
            class="text-danger text-sm"
            v-show="errors.has('facebook')"
          >{{ errors.first('facebook') }}</span>
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
            @click="handleAccountSubmit"
            :disabled="isSending"
          >{{ $t("save") }}</vs-button>
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
      name: "",
      website: "",
      maison: "",
      phone: "",
      sous_labels: [],
      artists: [],
      genres: [],
      address: "",
      indepndant: true,
      description: "",
      imgURL: "",
      rotation: 0,
      avatar: "",
      isSending: false,
    };
  },

  methods: {
    handleAccountSubmit(e) {
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
            .post(`/api/editors/create`, {
              username: this.username,
              full_name: this.name,
              email: this.email,
              status: this.status,
              role: this.role,
              password: this.password,
              avatar: ResultAvatar,
            })
            .then((response) => {
              self.isSending = false;
              console.log(response.data);
              self.$vs.loading.close("#save-button > .con-vs-loading");
              self.$vs.dialog({
                color: "primary",
                title: ``,
                text: "Editeur crée ! ",
              });
              this.$router.push("/editors");
            })
            .catch(function (err) {
              console.log(err.response.data);
              self.isSending = false;
              self.$vs.loading.close("#save-button > .con-vs-loading");
              self.$vs.dialog({
                color: "danger",
                title: ``,
                text: "Erreur lors de la création",
              });
            });
        }
      });
    },
  },
};
</script>
