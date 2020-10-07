<template>
  <div id="data-list-thumb-view" class="data-list-container">
    <!-- add new data right sidebar -->
    <!-- <data-view-sidebar :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" /> -->
    <h3 class="mb-6">Liste des Contacts</h3>
    <vs-table
      ref="table"
      multiple
      v-model="selected"
      pagination
      :max-items="itemsPerPage"
      search
      :data="contacts"
    >
      <div
        slot="header"
        class="flex flex-wrap-reverse items-center flex-grow justify-between"
      >
        <div class="flex flex-wrap-reverse items-center">
          <!-- ADD NEW -->
          <div
            class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base text-primary border border-solid border-primary"
            @click="$router.push('/create-contact').catch(() => {})"
          >
            <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
            <span class="ml-2 text-base text-primary"
              >Ajouter un nouveau Contact</span
            >
          </div>
        </div>

        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div
            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
          >
            <span class="mr-2"
              >{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} -
              {{
                contacts.length - currentPage * itemsPerPage > 0
                  ? currentPage * itemsPerPage
                  : contacts.length
              }}
              of {{ queriedItems }}</span
            >
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
          <vs-dropdown-menu>
            <vs-dropdown-item @click="itemsPerPage = 4">
              <span>4</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage = 10">
              <span>10</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage = 15">
              <span>15</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage = 20">
              <span>20</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>

      <template slot="thead">
        <!-- <vs-th :v-for="field in fields" sort-key="field">{{field}}</vs-th> -->
        <vs-th>Images</vs-th>
        <vs-th sort-key="name">Name</vs-th>
        <vs-th sort-key="email">Email</vs-th>
        <vs-th sort-key="country">Country</vs-th>
        <vs-th>Action</vs-th>
      </template>

      <template slot-scope="{ data }">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td class="img-container">
              <img
                :src="'/images/contacts/' + tr.picture"
                class="product-img"
              />
            </vs-td>

            <vs-td>
              <p class="contact-name font-medium truncate">{{ tr.name }}</p>
            </vs-td>

            <vs-td>
              <p class="contact-email">{{ tr.email }}</p>
            </vs-td>

            <vs-td>
              <p class="contact-country font-medium truncate">
                {{ tr.country }}
              </p>
            </vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon
                icon="InfoIcon"
                svgClasses="w-5 h-5 hover:text-success stroke-current"
                @click="gotoContact(tr.slug)"
              />
              <feather-icon
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click="editData(tr.slug)"
                class="ml-2"
              />
              <feather-icon
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click.stop="confirmDeleteRecord(tr)"
              />
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
// import DataViewSidebar from "../DataViewSidebar.vue";
import moduleDataList from "@/store/data-list/moduleDataList.js";

export default {
  components: {
    // DataViewSidebar,
  },
  data() {
    return {
      selected: [],
      // products: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      contacts: [],
      table: null,
    };
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    },
    // products() {
    //   return this.$store.state.dataList.products;
    // },
    queriedItems() {
      return this.$refs.table
        ? this.$refs.table.queriedResults.length
        : this.contacts.length;
    },
  },
  methods: {
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    deleteData(slug) {
      this.$http
        .post(`/api/contacts/${slug}/delete`)
        .then((res) => {
          this.$router.push("/contacts");
        })
        .catch((err) => {
          this.$vs.notify({
            color: "danger",
            title: "",
            text: "Erreur lors de la suppression",
          });
        });
    },
    confirmDeleteRecord(contact) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirm Delete",
        text: `Vous allez supprimer le contact "${contact.name}"`,
        accept: () => this.deleteRecord(contact.slug),
        acceptText: "Supprimer",
        cancelText: "Annuler",
        parametres: contact.slug,
      });
    },
    deleteRecord(slug) {
      this.$http
        .post(`/api/contacts/${slug}/delete`)
        .then((res) => {
          this.$vs.notify({
            color: "success",
            title: "",
            text: "Contact supprimer!",
          });
          this.contacts = res.data.contacts;
        })
        .catch((err) => {
          this.$vs.notify({
            color: "danger",
            title: "",
            text: "Erreur lors de la suppression",
          });
        });
    },
    editData(slug) {
      // this.sidebarData = JSON.parse(JSON.stringify(this.blankData))
      this.$router.push("/contacts/" + slug + "/edit");
    },
    getOrderStatusColor(status) {
      if (status === "on_hold") return "warning";
      if (status === "delivered") return "success";
      if (status === "canceled") return "danger";
      return "primary";
    },
    getPopularityColor(num) {
      if (num > 90) return "success";
      if (num > 70) return "primary";
      if (num >= 50) return "warning";
      if (num < 50) return "danger";
      return "primary";
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
    },
    gotoContact(slug) {
      this.$router.push("/contacts/" + slug);
    },
  },
  created() {
    let self = this;
    self.$vs.loading({
      type: "default",
      text: "Patientez s'il vous plait",
    });
    this.$http
      .get("/api/contacts", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("jwt")}`,
        },
      })
      .then((res) => {
        self.contacts = res.data;
        self.$vs.loading.close();
      })
      .catch((err) => {
        console.log(err.response.data);
        self.$vs.loading.close();
        self.$vs.dialog({
          color: "danger",
          title: ``,
          text: "Erreur lors de fetch",
        });
      });
  },
  mounted() {
    this.isMounted = true;
  },
};
</script>

<style lang="scss">
#data-list-thumb-view {
  .vs-con-table {
    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
      margin-left: 1.5rem;
      margin-right: 1.5rem;
      > span {
        display: flex;
        flex-grow: 1;
      }

      .vs-table--search {
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          & + i {
            left: 1rem;
          }

          &:focus + i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        td {
          padding: 10px;
          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }
          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }
          &.img-container {
            // width: 1rem;
            // background: #fff;

            span {
              display: flex;
              justify-content: flex-start;
            }

            .product-img {
              height: 110px;
            }
          }
        }
        td.td-check {
          padding: 20px !important;
        }
      }
    }

    .vs-table--thead {
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text {
          text-transform: uppercase;
          font-weight: 600;
        }
      }
      th.td-check {
        padding: 0 15px !important;
      }
      tr {
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}
</style>
