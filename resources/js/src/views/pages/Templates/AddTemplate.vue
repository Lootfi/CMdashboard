


<template>
  <div>
    <div class="vx-row">
      <div class="vx-col w-full mb-2">
        <vs-input
          class="w-full"
          label-placeholder="Nom de Template"
          v-model="name"
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
        <vs-button class="mr-3 mb-2" @click="submitTemplate">Ajouter</vs-button>
        <vs-button
          color="warning"
          type="border"
          class="mb-2"
          @click="name = subject = textBody = htmlBody = '';"
        >Reset</vs-button>
      </div>
    </div>
  </div>
</template>
        
<script>
export default {
  data() {
    return {
      name: "",
      subject: "",
      textBody: "",
      htmlBody: "",
    };
  },
  methods: {
    submitTemplate() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.$http
            .post(`/api/templates/create`, {
              name: this.name,
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
};
</script>

<style>
</style>