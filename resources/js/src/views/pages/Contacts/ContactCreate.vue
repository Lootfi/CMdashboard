<template>
  <div id="page-user-view">
    <!-- Content Row -->
    <vs-card>
      <div slot="header">
        <h3>Créer Un Contact</h3>
      </div>
      <div class="vx-row">
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            label="Nom"
            name="name"
            v-model="name"
            v-validate="{
              alpha,
              required: username == '' && prenom == '',
            }"
          />
          <span class="text-danger text-sm" v-show="errors.has('name')">{{
            errors.first("name")
          }}</span>
        </div>
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            v-validate="{
              alpha,
              required: username == '' && name == '',
            }"
            label="Prénom"
            type="prenom"
            name="prenom"
            v-model="prenom"
          />
          <span class="text-danger text-sm" v-show="errors.has('prenom')">{{
            errors.first("prenom")
          }}</span>
        </div>
      </div>
      <div class="vx-row">
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            v-validate="{
              alpha_num,
              required: name == '' && prenom == '',
            }"
            label="Pseudo"
            name="username"
            v-model="username"
          />
          <span class="text-danger text-sm" v-show="errors.has('username')">{{
            errors.first("username")
          }}</span>
        </div>

        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            ref="email"
            class="w-full mt-4"
            v-validate="{ email, required: mobile == '' }"
            label="Email"
            type="email"
            name="email"
            v-model="email"
          />
          <span class="text-danger text-sm" v-show="errors.has('email')">{{
            errors.first("email")
          }}</span>
        </div>
      </div>

      <div class="vx-row">
        <div class="vx-col md:w-1/2 w-full mt-4">
          <label class="vs-input--label">Pays</label>
          <v-select
            class="py-2"
            :clearable="false"
            :options="countryOptions"
            name="role"
            :dir="$vs.rtl ? 'rtl' : 'ltr'"
            v-model="country"
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
            v-model="state"
            name="state"
          />
        </div>
      </div>
      <!-- </div> -->
      <div class="vx-row">
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            ref="mobile"
            v-validate="{ required: email == '' }"
            class="w-full mt-4"
            label="Mobile"
            v-model="mobile"
            name="mobile"
            type="tel"
          />
          <span class="text-danger text-sm" v-show="errors.has('mobile')">{{
            errors.first("mobile")
          }}</span>
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
            v-model="title"
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
            v-model="description_poste"
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
            v-model="description_media"
          />
          <span
            class="text-danger text-sm"
            v-show="errors.has('description_media')"
            >{{ errors.first("description_media") }}</span
          >
        </div>
      </div>
      <div class="my-4">
        <clipper-upload
          class="inline-block p-2 my-2 bg-primary rounded text-white cursor-pointer"
          v-model="imgURL"
          >Importer La photo de Contact</clipper-upload
        >
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
      </div>
    </vs-card>
    <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            id="save-button"
            class="ml-auto mt-2"
            @click="handleContactSubmit"
            :disabled="isSending"
            >Save Changes</vs-button
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
  props: {
    data: {
      type: Object,
    },
  },
  data() {
    return {
      name: "",
      email: "",
      country: {},
      state: "",
      mobile: "",
      title: "",
      prenom: "",
      username: "",
      imgURL: "",
      rotation: 0,
      adresse: "",
      description_poste: "",
      description_media: "",
      avatar: "",
      isSending: false,
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
      entreprises: [],
      entrepriseOptions: [],
      type: null,
      typeOptions: [],
    };
  },
  mounted() {
    this.$http.get("/api/entreprises").then((res) => {
      let entreprises = [];
      res.data.map((item, index) => {
        entreprises[index] = { label: item.name, value: item.slug };
      });
      this.entrepriseOptions = entreprises;
    });

    this.$http.get("/api/types").then((res) => {
      let types = [];
      res.data.map((item, index) => {
        types[index] = { label: item.name, value: item.id };
      });
      this.typeOptions = types;
    });
  },
  methods: {
    handleContactSubmit(e) {
      e.preventDefault();
      let self = this;
      this.$validator.validateAll().then((result) => {
        if (result) {
          let ResultAvatar = "";
          if (this.imgURL) {
            const canvas = self.$refs.clipper.clip();
            ResultAvatar = canvas.toDataURL("image/jpeg", 1);
          }
          self.isSending = true;
          this.$vs.loading({
            container: "#save-button",
            scale: 0.45,
          });
          this.$http
            .post(`/api/contacts/create`, {
              title: self.title,
              prenom: self.prenom,
              username: self.username,
              name: self.name,
              email: self.email,
              mobile: self.mobile,
              country: self.country,
              state: self.state,
              picture: ResultAvatar,
              entreprises: self.entreprises,
              description_poste: self.description_poste,
              description_media: self.description_media,
              type_id: self.type.value,
            })
            .then((response) => {
              this.$vs.loading.close();
              console.log(response.data);
              self.isSending = false;
              self.$vs.notify({
                color: "success",
                title: ``,
                text: "Contact crée ! ",
              });
              self.$router.push("/contacts");
            })
            .catch(function (error) {
              this.$vs.loading.close();
              console.log(error.response.data);
              self.isSending = false;
              if (error.response.data === "Contact exists") {
                self.$vs.notify({
                  color: "danger",
                  title: ``,
                  text: "Un contact avec le meme E-mail existe déja",
                });
              } else {
                self.$vs.dialog({
                  color: "danger",
                  title: ``,
                  text: "Erreur lors de la création",
                });
              }
            });
        }
      });
    },
  },
};
</script>
