<!-- =========================================================================================
  File Name: UserView.vue
  Description: User View page
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
  <div id="page-user-view">
    <vs-alert color="danger" title="User Not Found" :active.sync="user_not_found">
      <span>Contact record with slug: {{ $route.params.slug }} not found.</span>
      <span>
        <span>Check</span>
        <router-link :to="{ name: 'page-user-list' }" class="text-inherit underline">All Users</router-link>
      </span>
    </vs-alert>

    <div id="user-data" v-if="contact">
      <vx-card title="Contact" class="mb-base">
        <!-- Avatar -->
        <div class="vx-row">
          <!-- Avatar Col -->
          <div class="vx-col" id="avatar-col">
            <div class="img-container mb-4">
              <img :src="'/images/contacts/' +contact.picture" class="rounded w-full" />
            </div>
          </div>

          <!-- Information - Col 1 -->
          <div class="vx-col flex-1" id="account-info-col-1">
            <table>
              <tr>
                <td class="font-semibold">Name</td>
                <td>{{ contact.name }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Email</td>
                <td>{{ contact.email }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Mobile</td>
                <td>{{ contact.mobile }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 1 -->

          <!-- Information - Col 2 -->
          <div class="vx-col flex-1" id="account-info-col-2">
            <table>
              <tr>
                <td class="font-semibold">Country</td>
                <td>{{ contact.country }}</td>
              </tr>
              <tr>
                <td class="font-semibold">State</td>
                <td>{{ contact.state }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Adresse</td>
                <td>{{ contact.adresse }}</td>
              </tr>
            </table>
          </div>
          <!-- /Information - Col 2 -->
          <div class="vx-col w-full flex" id="account-manage-buttons">
            <vs-button
              icon-pack="feather"
              icon="icon-edit"
              class="mr-4"
              :to="{
                                name: 'contact-edit',
                                params: { slug: $route.params.slug }
                            }"
            >Edit</vs-button>
            <vs-button
              type="border"
              color="danger"
              icon-pack="feather"
              icon="icon-trash"
              @click="confirmDeleteRecord"
            >Delete</vs-button>
          </div>
        </div>
      </vx-card>

      <div class="vx-row">
        <div class="vx-col lg:w-1/2 w-full">
          <vx-card title="Information" class="mb-base">
            <table>
              <tr>
                <td class="font-semibold">Mobile</td>
                <td>{{ contact.mobile }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Website</td>
                <td>https://www.website.com/</td>
              </tr>
              <!-- 
              <tr>
                <td class="font-semibold">Languages</td>
                <td>{{ user_data.languages_known.join(", ") }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Gender</td>
                <td>{{ user_data.gender }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Contact</td>
                <td>{{ user_data.contact_options.join(", ") }}</td>
              </tr>-->
            </table>
          </vx-card>
        </div>

        <div class="vx-col lg:w-1/2 w-full">
          <vx-card title="Social Links" class="mb-base">
            <table>
              <tr>
                <td class="font-semibold">Twitter</td>
                <td>{{ contact.social.twitter }}</td>
              </tr>
              <tr>
                <td class="font-semibold">LinkedIn</td>
                <td>{{ contact.social.linkedin }}</td>
              </tr>
              <tr>
                <td class="font-semibold">Instagram</td>
                <td>{{ contact.social.instagram }}</td>
              </tr>
            </table>
          </vx-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      contact: null,
      user_not_found: false,
    };
  },
  methods: {
    confirmDeleteRecord() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirm Delete",
        text: `Vous ètes entrain de suprimer "${this.contact.name}"`,
        accept: this.deleteRecord,
        acceptText: "Delete",
      });
    },
    deleteRecord() {
      /* Below two lines are just for demo purpose */
      this.$router.push("/contacts");
      this.showDeleteSuccess();

      /* UnComment below lines for enabling true flow if deleting user */
      // this.$store.dispatch("userManagement/removeRecord", this.user_data.id)
      //   .then(()   => { this.$router.push({name:'app-user-list'}); this.showDeleteSuccess() })
      //   .catch(err => { console.error(err)       })
    },
    showDeleteSuccess() {
      this.$vs.notify({
        color: "success",
        title: "Contact Deleted",
        text: "The selected contact was successfully deleted",
      });
    },
  },
  created() {
    const slug = this.$route.params.slug;
    console.log("SLUG", slug);
    this.$http
      .get("/api/contacts/" + slug, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("jwt")}`,
        },
      })
      .then((res) => {
        console.log("CONTACT", res.data);
        this.contact = res.data;
      })
      .catch((err) => {
        if (err.response.status === 404) {
          this.user_not_found = true;
          return;
        }
        console.error(err.response.data);
      });
  },
};
</script>

<style lang="scss">
#avatar-col {
  width: 10rem;
}

#page-user-view {
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
