<template>
  <div :style="{ direction: $vs.rtl ? 'rtl' : 'ltr' }" class="flex">
    <vx-tooltip text="Modifier">
      <feather-icon
        icon="EditIcon"
        svgClasses="w-5 h-5 hover:text-primary stroke-current"
        @click="$router.push(`/templates/${params.data.alias}/edit`)"
      />
    </vx-tooltip>
    <vx-tooltip text="Supprimer">
      <feather-icon
        icon="TrashIcon"
        svgClasses="h-5 w-5 hover:text-danger cursor-pointer"
        @click="confirmDeleteRecord(params.data.alias)"
      />
    </vx-tooltip>
  </div>
</template>

<script>
export default {
  name: "CellRendererActions",
  methods: {
    confirmDeleteRecord(slug) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirm Delete",
        text: `Vous allez supprimer la template "${this.params.data.name}"`,
        accept: this.deleteRecord,
        acceptText: "Delete",
        parameters: slug,
      });
    },
    deleteRecord(parameters) {
      this.$http
        .post(`/api/templates/${this.params.data.alias}/delete`)
        .then((res) => {
          window.location.reload();
        })
        .catch((err) => {
          this.$vs.notify({
            color: "warning",
            text: "Template pas supprimer!",
          });
        });
    },
  },
};
</script>