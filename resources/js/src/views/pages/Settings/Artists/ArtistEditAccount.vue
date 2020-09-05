<template>
  <div class="my-4">
    <div class="vx-row">
      <div class="vx-col sm:w-1/2 w-full mb-2">
        <vs-input
          class="w-full my-4"
          label="Nom"
          name="name"
          v-model="artistData.name"
          v-validate="'alpha_spaces'"
        />
        <span class="text-danger text-sm" v-show="errors.has('name')">
          {{
          errors.first("name")
          }}
        </span>
      </div>
      <div class="vx-col sm:w-1/2 w-full mb-2">
        <vs-input
          class="w-full my-4"
          label="Email"
          name="email"
          v-model="artistData.email"
          v-validate="'email'"
        />
        <span class="text-danger text-sm" v-show="errors.has('email')">
          {{
          errors.first("email")
          }}
        </span>
      </div>
    </div>

    <div class="vx-row">
      <div class="vx-col sm:w-1/2 w-full mb-2">
        <label class="vs-input--label">Etat</label>
        <v-select
          v-validate="'required'"
          v-model="artistData.StatusName"
          :clearable="false"
          :options="statusOptions"
          name="status"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span class="text-danger text-sm" v-show="errors.has('status')">{{ errors.first("status") }}</span>
      </div>
    </div>
    <!-- <div>
      <div style="max-width: 100%;">
        <div class="my-4">
          <clipper-upload
            class="inline-block p-2 my-2 bg-primary rounded text-white"
            v-model="imgURL"
          >Importer une nouvelle photo</clipper-upload>
          <div class="flex" style="max-width: 100%;">
            <clipper-basic
              :ratio="1"
              bg-color="black"
              class="flex-grow-3"
              ref="clipper"
              :src="imgURL"
              :init-width="100"
              :init-height="100"
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
    </div>-->
    <vs-button @click="activePrompt = true">Changer la photo de client</vs-button>
    <vs-prompt
      title="Changer La photo"
      @cancel="val=''"
      @accept="handlePhotoUpload"
      @close="close"
      :active.sync="activePrompt"
      accept-text="Changer"
      cancel-text="Annuler"
    >
      <div class="con-exemple-prompt">
        <div class="my-4">
          <clipper-upload
            class="inline-block p-2 my-2 bg-primary rounded text-white"
            v-model="imgURL"
          >Importer La photo de client</clipper-upload>
          <div class="flex" style="max-width: 100%;">
            <clipper-basic
              :ratio="1"
              bg-color="black"
              class="flex-grow-3"
              ref="clipper"
              :src="imgURL"
              :init-width="100"
              :init-height="100"
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
    </vs-prompt>
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            class="ml-auto mt-2"
            @click="handleSave"
            :disabled="isSending"
          >Sauvegarder l'artiste</vs-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Cropper from "cropperjs";
import vSelect from "vue-select";
import debounce from "lodash/debounce";

export default {
  props: {
    artistData: { type: Object, required: true },
    imgURL: { type: String, required: true },
  },
  data() {
    return {
      artist_local: this.artistData,
      activePrompt: false,
      isSending: false,
      rotation: 0,
      statusOptions: [
        { label: "Activé", value: "1" },
        { label: "Suspendu", value: "0" },
      ],
    };
  },
  components: {
    vSelect,
  },
  methods: {
    handlePhotoUpload() {
      this.isSending = true;
      const canvas = this.$refs.clipper.clip();
      const ResultAvatar = canvas.toDataURL("image/jpeg", 1);
      this.$http
        .post(`/api/artists/${this.$route.params.slug}/edit`, {
          avatar: ResultAvatar,
        })
        .then((res) => {
          this.isSending = false;
          this.$vs.notify({
            color: "success",
            title: ``,
            text: "Photo de client modifié!",
          });
          window.location.reload();
        })
        .catch((err) => {
          this.isSending = false;
          this.$vs.dialog({
            color: "danger",
            title: ``,
            text: "Erreur lors de la modification",
          });
        });
    },
    handleSave(e) {
      e.preventDefault();
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.isSending = true;
          this.$http
            .post(`/api/artists/${this.$route.params.slug}/edit`, {
              name: this.artistData.name,
              email: this.artistData.email,
              status: this.artistData.StatusName,
            })
            .then((res) => {
              this.isSending = false;
              console.log(res.data);
              this.$router.push(`/clients`);
            })
            .catch((err) => {
              console.log(err);
              this.isSending = false;
              this.$vs.dialog({
                color: "danger",
                title: ``,
                text: "Erreur lors de la modification",
              });
            });
        }
      });
    },
  },
};
</script>

<style>
</style>