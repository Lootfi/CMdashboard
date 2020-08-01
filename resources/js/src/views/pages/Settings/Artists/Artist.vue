<template>
  <div id="knowledge-base-article">
    <div>
      <div class="vx-col w-full mt-12 md:mt-0">
        <vx-card>
          <!-- Title -->
          <div class="flex justify-between items-center">
            <div>
              <vs-avatar size="150px" :src="artist_data.AvatarLink" />
            </div>
            <div class="article-title mb-6 py-4">
              <h1
                class="text-5xl text-center my-2"
                style="font-family:times new roman"
              >{{ artist_data.name }}</h1>
            </div>
            <div>
              <div class="dropdown-button-container">
                <vs-dropdown>
                  <a class="flex items-center" href="#">
                    Menu
                    <i class="material-icons">expand_more</i>
                  </a>

                  <vs-dropdown-menu>
                    <vs-dropdown-item
                      @click="$router.push(`/artists/${artist_data.slug}/edit`)"
                    >Modifier</vs-dropdown-item>
                  </vs-dropdown-menu>
                </vs-dropdown>
              </div>
            </div>
          </div>
        </vx-card>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      artist_data: {},
      artist_not_found: false,
      user_not_admin: true,
      isDeleting: false,
    };
  },

  mounted() {
    this.$http
      .get(`/api/artists/${this.$route.params.slug}`)
      .then((response) => {
        if (response.data == "Artist not found") {
          this.$router.push("/artists");
        }
        console.log(response.data);
        this.artist_data = response.data;

        if (this.$store.state.AppActiveUser.user.role == "Admin") {
          this.user_not_admin = false;
        }
      })
      .catch(function (error) {
        console.error(error.response.data);
      });
  },
};
</script>

<style lang="scss" scoped>
.list-view-item {
  .item-name,
  .item-description {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .item-name {
    -webkit-line-clamp: 2;
  }

  .item-description {
    -webkit-line-clamp: 5;
  }

  .grid-view-img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    transition: 0.35s;
  }

  &:hover {
    transform: translateY(-5px);
    box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.25);

    .grid-view-img {
      opacity: 0.9;
    }
  }
}
</style>
