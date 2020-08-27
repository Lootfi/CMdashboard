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
                      @click="
                                                $router.push(
                                                    `/clients/${artist_data.slug}/edit`
                                                )
                                            "
                    >Modifier</vs-dropdown-item>
                  </vs-dropdown-menu>
                </vs-dropdown>
              </div>
            </div>
          </div>
          <div class="vx-row">
            <div class="vx-col flex-1" id="account-info-col-1">
              <table>
                <tr>
                  <td class="font-semibold">Email</td>
                  <td>{{ artist_data.email }}</td>
                </tr>

                <tr>
                  <td class="font-semibold">Status</td>
                  <td>{{ artist_data.StatusName.label }}</td>
                </tr>
              </table>
            </div>
            <!-- /Information - Col 1 -->

            <!-- Information - Col 2 -->
            <div class="vx-col flex-1" id="account-info-col-2">
              <table>
                <tr>
                  <td class="font-semibold">Méthode de Paiement</td>
                  <td>{{ ' ' +artist_data.PaymentMethodName }}</td>
                </tr>
                <tr>
                  <td class="font-semibold">Paiement</td>
                  <td v-if="artist_data.payment_confirmed">Capturé</td>
                  <td v-else-if="artist_data.payment_authorized">Authorizé (Période d'essai)</td>
                  <td v-else>Pas encore</td>
                </tr>
              </table>
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

table {
  td {
    vertical-align: top;
    min-width: 140px;
    padding-bottom: 0.8rem;
    word-break: break-all;
  }

  &:not(.permissions-table) {
    td {
      @media screen and (max-width: 370px) {
        display: block;
      }
    }
  }
}

// #account-info-col-1 {
//   // flex-grow: 1;
//   width: 30rem !important;
//   @media screen and (min-width:1200px) {
//     & {
//       flex-grow: unset !important;
//     }
//   }
// }

@media screen and (min-width: 1201px) and (max-width: 1211px),
  only screen and (min-width: 636px) and (max-width: 991px) {
  #account-info-col-1 {
    width: calc(100% - 12rem) !important;
  }

  // #account-manage-buttons {
  //   width: 12rem !important;
  //   flex-direction: column;

  //   > button {
  //     margin-right: 0 !important;
  //     margin-bottom: 1rem;
  //   }
  // }
}
</style>
