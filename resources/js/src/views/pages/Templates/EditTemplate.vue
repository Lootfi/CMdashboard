


<template>
  <div>
    <div class="vx-row">
      <div class="vx-col w-full mb-2">
        <label class="vs-input--label">Nom de template</label>
        <v-select
          taggable
          :dir="$vs.rtl ? 'rtl' : 'ltr'"
          v-model="selectedTemplate"
          :options="nameOptions"
          v-validate="'required'"
        />
      </div>
      <span class="text-danger text-sm" v-show="errors.has('name')">
        {{
        errors.first("name")
        }}
      </span>
    </div>
    <div class="vx-row">
      <div class="vx-col w-full mb-2">
        <vs-input
          class="w-full"
          label-placeholder="Sujet de Template"
          v-model="subject"
          v-validate="'required'"
        />
      </div>
      <span class="text-danger text-sm" v-show="errors.has('subject')">
        {{
        errors.first("subject")
        }}
      </span>
    </div>
    <div class="vx-row">
      <div class="vx-col w-full mb-2">
        <vs-textarea
          v-model="textBody"
          label="Normal Text Body"
          height="200px"
          v-validate="'required'"
        />
      </div>
      <span class="text-danger text-sm" v-show="errors.has('textBody')">
        {{
        errors.first("textBody")
        }}
      </span>
      <div class="vx-col w-full mb-6">
        <vs-textarea
          dark="true"
          v-model="htmlBody"
          label="HTML Body"
          height="500px"
          v-validate="'required'"
        />
      </div>
      <span class="text-danger text-sm" v-show="errors.has('htmlBody')">
        {{
        errors.first("htmlBody")
        }}
      </span>
    </div>
    <div class="vx-row">
      <div class="vx-col w-full">
        <vs-button class="mr-3 mb-2" @click="submitTemplate">Modifier</vs-button>
        <vs-button
          color="warning"
          type="border"
          class="mb-2"
          @click="subject = textBody = htmlBody = '';"
        >Reset</vs-button>
      </div>
    </div>
  </div>
</template>
        
<script>
import vSelect from "vue-select";

export default {
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      // name: "",
      subject: "",
      textBody: "",
      htmlBody: "",
      templates: [],
      selectedTemplate: {},
      nameOptions: [],
    };
  },
  methods: {
    submitTemplate() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.$http
            .post(`/api/templates/${this.selectedTemplate.value}/edit`, {
              subject: this.subject,
              textBody: this.textBody,
              htmlBody: this.htmlBody,
            })
            .then((res) => {
              this.$router.push("/");
            })
            .catch((err) => console.log(err));
        }
      });
    },
  },
  mounted() {
    this.$vs.loading({
      type: "default",
      text: "Patientez s'il vous plait",
    });

    this.$http
      .get(`/api/templates`)
      .then((res) => {
        this.templates = res.data;
        res.data.map((template) => {
          this.nameOptions = [
            ...this.nameOptions,
            { label: template.name, value: template.alias },
          ];
        });
      })
      .catch((error) => {
        console.error(error);
      })
      .then(() => {
        this.$vs.loading.close();
      });
  },
};
</script>

<style>
</style>