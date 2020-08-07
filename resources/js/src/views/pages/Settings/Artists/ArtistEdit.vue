<template>
  <div class="my-4">
    <artist-edit-account :imgURL="imgURL" v-if="artistData" :artistData="artistData" />
    <!-- <div class="vx-row">
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
    </div>-->

    <!-- <div class="vx-row">
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
    </div>-->

    <vs-divider />

    <!-- Password -->
    <vx-card class="mt-base" no-shadow card-border>
      <div class="vx-row">
        <div class="vx-col w-full">
          <div class="flex items-end px-3">
            <feather-icon svgClasses="w-6 h-6" icon="LockIcon" class="mr-2" />
            <span class="font-medium text-lg leading-none">Mot de passe</span>
          </div>
          <vs-divider />
        </div>
      </div>

      <div class="block overflow-x-auto">
        <vs-input
          class="w-full mb-base"
          v-validate="'required|min:3|max:35'"
          label-placeholder="Mot de passe actuelle"
          v-model="old_password"
          name="old_password"
          type="password"
        />
        <span class="text-danger text-sm" v-show="errors.has('old_password')">
          {{
          errors.first("old_password")
          }}
        </span>
        <vs-input
          class="w-full mb-base"
          name="password"
          ref="password"
          v-validate="'required|min:3|max:35'"
          label-placeholder="Nouveau Mot de passe"
          v-model="password"
          type="password"
        />
        <span class="text-danger text-sm" v-show="errors.has('password')">
          {{
          errors.first("password")
          }}
        </span>
        <vs-input
          class="w-full mb-base"
          label-placeholder="Confirmation du nouveau mot de passe"
          v-model="confirm_password"
          name="confirm_password"
          v-validate="'required|confirmed:password'"
          type="password"
        />
        <span class="text-danger text-sm" v-show="errors.has('confirm_password')">
          {{
          errors.first("confirm_password")
          }}
        </span>
      </div>
    </vx-card>

    <!-- Password end -->
    <!-- IMAGE -->
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

    <!-- Image end -->

    <!-- <div class="vx-row">
      <div class="vx-col w-full">
        <div class="mt-8 flex flex-wrap items-center justify-end">
          <vs-button
            class="ml-auto mt-2"
            @click="handleSave"
            :disabled="isSending"
          >Sauvegarder l'artiste</vs-button>
        </div>
      </div>
    </div>-->
  </div>
</template>
<script>
import Cropper from "cropperjs";
import vSelect from "vue-select";
import debounce from "lodash/debounce";
import ArtistEditAccount from "./ArtistEditAccount";
import ArtistEditPassword from "./ArtistEditPassword";

export default {
  components: {
    ArtistEditAccount,
    ArtistEditPassword,
  },
  data() {
    return {
      artistData: {},
      imgURL: "",
      old_password: "",
      password: "",
      confirm_password: "",
    };
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
          this.$router.push("/clients");
        }
        this.$vs.loading.close();
        console.log(response.data);

        this.artistData = response.data;
        // this.name = response.data.name;
        // this.status = response.data.StatusName;
        this.imgURL = response.data.AvatarLink;
      })
      .catch(function (error) {
        console.error(error.response.data);
      });
  },
};
</script>