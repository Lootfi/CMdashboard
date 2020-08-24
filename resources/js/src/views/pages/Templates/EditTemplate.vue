


<template>
  <div v-if="template">
    <div class="vx-row">
      <div class="vx-col w-full mb-2">
        <h3 class="mb-6">Modifier Template "{{template.name}}"</h3>
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
          v-model="template.subject"
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
          v-model="template.textBody"
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
          v-model="template.htmlBody"
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
export default {
  data() {
    return {
      subject: "",
      textBody: "",
      htmlBody: "",
      template: {},
    };
  },
  methods: {
    submitTemplate() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.$http
            .post(`/api/templates/${this.$route.params.alias}/edit`, {
              ...this.template,
            })
            .then((res) => {
              this.$router.push("/templates");
            })
            .catch((err) => {
              this.$vs.notify({
                color: "warning",
                text: "Erreur dans la modification",
              });
              console.log(err);
            });
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
      .get(`/api/templates/${this.$route.params.alias}`)
      .then((res) => {
        this.template = res.data;
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