<!-- =========================================================================================
  File Name: DataListListView.vue
  Description: Data List - List View
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->
<template>
  <div id="data-list-list-view" class="data-list-container">
    <!-- <data-view-sidebar :isSidebarActive="addNewDataSidebar" @closeSidebar="toggleDataSidebar" :data="sidebarData" /> -->
    <h3 class="mb-6">Nouveaux Clients</h3>
    <vs-table
      ref="table"
      multiple
      v-model="selected"
      pagination
      :max-items="itemsPerPage"
      :data="prospects"
    >
      <div
        slot="header"
        class="flex flex-wrap-reverse items-center flex-grow justify-between"
      >
        <!-- ITEMS PER PAGE -->
        <vs-dropdown
          vs-trigger-click
          class="cursor-pointer mb-4 mr-4 items-per-page-handler"
        >
          <div
            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
          >
            <span class="mr-2">
              {{ currentPage * itemsPerPage - (itemsPerPage - 1) }}
              -
              {{
                prospects.length - currentPage * itemsPerPage > 0
                  ? currentPage * itemsPerPage
                  : prospects.length
              }}
              of {{ queriedItems }}
            </span>
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
        <vs-th sort-key="email">Email</vs-th>
        <vs-th sort-key="created_at">Crée lee</vs-th>
        <vs-th>Status</vs-th>
        <vs-th>Action</vs-th>
      </template>

      <template slot-scope="{ data }">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in prospects">
            <vs-td>
              <p class="product-name font-medium truncate">{{ tr.email }}</p>
            </vs-td>
            <vs-td>
              <p class="product-name font-medium truncate">
                {{ tr.created_at }}
              </p>
            </vs-td>
            <vs-td>
              <p
                v-if="tr.payment_authorized"
                class="product-name font-medium truncate"
              >
                en Periode d'essai
              </p>
              <p v-else class="product-name font-medium truncate">
                Paiement non finalisé
              </p>
            </vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon
                v-if="tr.payment_authorized && tr.can_capture"
                icon="CreditCardIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="capturePayment(tr.id)"
              />
              <feather-icon
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click.stop="deleteData(tr)"
              />
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
// import DataViewSidebar from './DataViewSidebar.vue'

export default {
  // components: {
  //   DataViewSidebar
  // },
  data() {
    return {
      selected: [],
      prospects: [],
      itemsPerPage: 4,
      isMounted: false,

      // Data Sidebar
      addNewDataSidebar: false,
      sidebarData: {},
    };
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    },
    queriedItems() {
      return this.$refs.table
        ? this.$refs.table.queriedResults.length
        : this.prospects.length;
    },
  },
  methods: {
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    deleteData(tr) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirmation",
        text: `Vous allez supprimer le compte d'utilisateur "${tr.name}", et arrêter leur paiement.`,
        accept: this.deleteRecord,
        acceptText: "Supprimer",
        cancelText: "Annuler",
        parameters: tr.slug,
      });
    },
    deleteRecord(slug) {
      // this.$store.dispatch("dataList/removeItem", slug).catch((err) => {
      //   console.error(err);
      // });
      this.$http
        .post(`/api/artists/${slug}/delete`, { return_prospects: true })
        .then((res) => {
          this.prospects = res.data;
        })
        .catch((err) => {
          this.$vs.notify({
            color: "danger",
            title: "",
            text: "Erreur lors de la suppression",
          });
        });
    },
    capturePayment(id) {
      this.$http.post(`/api/front/payment`, { id: id });
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
    },
  },
  created() {
    this.$http
      .get("/api/potential-clients")
      .then((res) => {
        this.prospects = res.data;
      })
      .catch((err) => console.log(err.response.data));
  },
  mounted() {
    this.isMounted = true;
  },
};
</script>

<style lang="scss">
#data-list-list-view {
  .vs-con-table {
    /*
      Below media-queries is fix for responsiveness of action buttons
      Note: If you change action buttons or layout of this page, Please remove below style
    */
    @media (max-width: 689px) {
      .vs-table--search {
        margin-left: 0;
        max-width: unset;
        width: 100%;

        .vs-table--search-input {
          width: 100%;
        }
      }
    }

    @media (max-width: 461px) {
      .items-per-page-handler {
        display: none;
      }
    }

    @media (max-width: 341px) {
      .data-list-btn-container {
        width: 100%;

        .dd-actions,
        .btn-add-new {
          width: 100%;
          margin-right: 0 !important;
        }
      }
    }

    .product-name {
      max-width: 23rem;
    }

    .vs-table--header {
      display: flex;
      flex-wrap: wrap;
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
          padding: 20px;
          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }
          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
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
