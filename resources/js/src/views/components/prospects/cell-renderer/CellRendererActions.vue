<template>
  <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
    <feather-icon
      icon="Trash2Icon"
      svgClasses="h-5 w-5 hover:text-danger cursor-pointer"
      @click="confirmDeleteRecord"
    />
  </div>
</template>

<script>
export default {
  name: "CellRendererActions",
  methods: {
    confirmDeleteRecord() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirmer",
        text: `Vous etes entrain de supprimer "${this.params.data.name}"`,
        accept: this.deleteRecord,
        acceptText: "Supprimer",
      });
    },
    deleteRecord() {
      /* Below two lines are just for demo purpose */
      this.showDeleteSuccess();

      /* UnComment below lines for enabling true flow if deleting user */
      // this.$store.dispatch("userManagement/removeRecord", this.params.data.id)
      //   .then(()   => { this.showDeleteSuccess() })
      //   .catch(err => { console.error(err)       })
    },
    showDeleteSuccess() {
      this.$vs.notify({
        color: "success",
        text: "Utilisateur prospect supprimer!",
      });
    },
  },
};
</script>
