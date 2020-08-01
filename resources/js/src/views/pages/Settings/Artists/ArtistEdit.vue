<template>
  <div class="my-4">
    <div class="vx-row">
      <div class="vx-col sm:w-1/2 w-full mb-2">
        <vs-input
          class="w-full my-4"
          label="Nom"
          name="name"
          v-model="name"
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
          v-model="status"
          :clearable="false"
          :options="statusOptions"
          name="status"
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
        />
        <span class="text-danger text-sm" v-show="errors.has('status')">{{ errors.first("status") }}</span>
      </div>
    </div>

    <div>
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
    </div>
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
  data() {
    return {
      name: "",
      avatar: "",
      artistData: {},
      isSending: false,
      status: "",
      imgURL: "",
      rotation: 0,
      statusOptions: [
        { label: "Activé", value: "1" },
        { label: "Suspendu", value: "2" },
      ],
    };
  },
  components: {
    vSelect,
  },
  mounted() {
    this.$vs.loading({
      type: "corners",
      text: "Patientez s'il vous plait",
    });

    this.$http
      .get(`/api/artists/${this.$route.params.slug}`)
      .then((response) => {
        if (response.data == "Artist not found") {
          this.$router.push("/artists");
        }
        this.$vs.loading.close();
        console.log(response.data);

        this.artistData = response.data;
        this.name = response.data.name;
        this.status = response.data.StatusName;
        this.imgURL = response.data.AvatarLink;
      })
      .catch(function (error) {
        console.error(error.response.data);
      });
  },
  methods: {
    handleSave(e) {
      e.preventDefault();
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.isSending = true;
          const canvas = this.$refs.clipper.clip();
          const ResultAvatar = canvas.toDataURL("image/jpeg", 1);
          this.$http
            .post(`/api/artists/${this.$route.params.slug}/edit`, {
              name: this.name,
              avatar: ResultAvatar,
            })
            .then((response) => {
              this.isSending = false;
              this.$router.push(`/artists/${response.data.slug}`);
            })
            .catch(function (error) {
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