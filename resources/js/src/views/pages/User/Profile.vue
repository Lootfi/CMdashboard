<template>
  <div id="profile-page">
    <!-- PROFILE HEADER -->
    <div class="profile-header">
      <div class="relative">
        <div class="cover-container rounded-t-lg">
          <img src="/images/admin/banner.jpg" alt="user-profile-cover" class="responsive block" />
        </div>
        <div class="profile-img-container pointer-events-none">
          <div>
            <vs-avatar class="user-profile-img" :src="activeUserInfo.user.Avatar" size="85px" />
          </div>
          <div class="profile-actions pointer-events-auto flex justify-end">
            <vs-button
              @click="$router.push(`/profile-setting`)"
              icon-pack="feather"
              radius
              icon="icon-settings"
              class="ml-2 lg:ml-4"
            ></vs-button>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end flex-wrap profile-header-nav p-6">
        <div class="block sm:hidden">
          <feather-icon
            @click="isNavOpen = !isNavOpen"
            icon="AlignJustifyIcon"
            v-show="!isNavOpen"
            class="vx-navbar-toggler cursor-pointer"
          />
          <feather-icon
            icon="XIcon"
            v-show="isNavOpen"
            @click="isNavOpen = !isNavOpen"
            class="vx-navbar-toggler cursor-pointer"
          />
        </div>

        <!-- <div
          :class="isNavOpen ? 'block': 'hidden'"
          class="w-full flex-grow sm:flex sm:items-center sm:w-auto"
        >
          <div class="text-sm sm:flex-grow">
            <ul class="sm:flex mt-8 w-full justify-center md:mt-0 md:ml-auto md:w-2/4">
              <li class="p-2">
                <a class="cursor-pointer" href="#">Vue générale</a>
              </li>
            </ul>
          </div>
        </div>-->
      </div>
      <!-- <vx-navbar> -->
      <!-- </vx-navbar> -->
    </div>
    <div class="vx-row">
      <!-- COL 1 -->
      <div class="vx-col w-full lg:w-1/4">
        <vx-card title="About" class="mt-base">
          <!-- USER BIO -->
          <div class="user-bio">
            <p>{{activeUserInfo.user.biography}}</p>
          </div>

          <!-- OTEHR DATA -->
          <div class="mt-5">
            <h6>Nom:</h6>
            <p>{{activeUserInfo.user.full_name}}</p>
          </div>
          <div class="mt-5">
            <h6>Email:</h6>
            <p>{{activeUserInfo.user.email}}</p>
          </div>
          <div class="mt-5">
            <h6>Rédacteur depuis:</h6>
            <p>{{activeUserInfo.user.created_at}}</p>
          </div>
        </vx-card>
      </div>
    </div>
  </div>
</template>
<script >
export default {
  data() {
    return {
      isNavOpen: false,
    };
  },
  computed: {
    activeUserInfo() {
      return this.$store.state.AppActiveUser;
    },
  },
};
</script>
<style lang="scss">
@import "@sass/vuexy/pages/profile.scss";
</style>
