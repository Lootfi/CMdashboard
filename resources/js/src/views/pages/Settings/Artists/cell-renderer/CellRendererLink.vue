<template>
  <div class="flex items-center">
    <vs-avatar :src="photoUrl" class="flex-shrink-0 mr-2" size="40px" @click="$router.push(url)" />
    <router-link :to="url" @click.stop.prevent class="text-inherit hover:text-primary">{{ name }}</router-link>
  </div>
</template>

<script>
export default {
  name: "CellRendererLink",

  computed: {
    name() {
      return this.params.data.name;
    },
    photoUrl() {
      return this.params.data.AvatarLink;
    },
    url() {
      return `/clients/${this.params.data.slug}`;

      // Below line will be for actual product
      // Currently it's commented due to demo purpose - Above url is for demo purpose
      // return "/apps/user/user-view/" + this.params.data.id
    },
  },
};
</script>