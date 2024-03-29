<template>
  <div id="data-list-thumb-view" class="data-list-container">
    <!-- add new data right sidebar -->
    <sidebar-data
      :isSidebarActive="addNewDataSidebar"
      @closeSidebar="toggleDataSidebar"
      :data="sidebarData"
      :edit="edit"
      :create="create"
    />

    <faq-order v-if="changingOrder" :data="faqs" :changeOrder="changeOrder" />

    <vs-table
      v-if="!changingOrder"
      ref="table"
      multiple
      v-model="selected"
      pagination
      :max-items="itemsPerPage"
      search
      :data="faqs"
    >
      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
        <div class="flex flex-wrap-reverse items-center">
          <!-- ACTION - DROPDOWN -->
          <vs-dropdown vs-trigger-click class="cursor-pointer mr-4 mb-4">
            <div
              class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32"
            >
              <span class="mr-2">Actions</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu>
              <vs-dropdown-item @click="deleteSelected">
                <span class="flex items-center">
                  <feather-icon icon="TrashIcon" svgClasses="h-4 w-4" class="mr-2" />
                  <span>Supprimer</span>
                </span>
              </vs-dropdown-item>
            </vs-dropdown-menu>
          </vs-dropdown>

          <!-- ADD NEW -->
          <div
            class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base text-primary border border-solid border-primary"
            @click="addNewData"
          >
            <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
            <span class="ml-2 text-base text-primary">Ajouter une nouvelle question</span>
          </div>
          <!-- Change Order -->
          <div
            class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base text-primary border border-solid border-primary"
            @click="changeOrder"
          >
            <feather-icon icon="ListIcon" svgClasses="h-4 w-4" />
            <span class="ml-2 text-base text-primary">Changer l'ordre des questions</span>
          </div>
        </div>

        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div
            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
          >
            <span class="mr-2">
              {{
              currentPage * itemsPerPage - (itemsPerPage - 1)
              }}
              -
              {{
              faqs.length - currentPage * itemsPerPage > 0
              ? currentPage * itemsPerPage
              : faqs.length
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
        <!-- <vs-th :v-for="field in fields" sort-key="field">{{field}}</vs-th> -->
        <vs-th sort-key="question">Question</vs-th>
        <vs-th>Actions</vs-th>
      </template>

      <template slot-scope="{ data }">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <p class="type-name font-medium truncate">{{ tr.question }}</p>
            </vs-td>

            <vs-td class="whitespace-no-wrap">
              <feather-icon
                icon="EditIcon"
                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                @click.stop="editData(tr)"
                class="ml-2"
              />
              <feather-icon
                icon="TrashIcon"
                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                class="ml-2"
                @click.stop="deleteData(tr.id)"
              />
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
import SidebarData from "./SidebarData.vue";
import FaqOrder from "./FaqOrder.vue";
export default {
  components: {
    SidebarData,
    FaqOrder,
  },
  data() {
    return {
      selected: [],
      // products: [],
      itemsPerPage: 4,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      faqs: [],
      table: null,
      changingOrder: false,
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
        : this.faqs.length;
    },
  },
  methods: {
    changeOrder() {
      this.changingOrder = true;
    },
    addNewData() {
      this.sidebarData = {};
      this.toggleDataSidebar(true);
    },
    deleteData(id) {
      this.$http.post("/api/faqs/" + id + "/delete").catch((err) => {
        console.error(err);
        self.$vs.dialog({
          color: "danger",
          title: ``,
          text: "Erreur lors de suppression ",
        });
      });
      window.location.reload();
    },
    editData(data) {
      // this.sidebarData = JSON.parse(JSON.stringify(this.blankData))
      this.sidebarData = data;
      this.toggleDataSidebar(true);
    },
    toggleDataSidebar(val = false) {
      // if (!val) window.location.reload();
      this.addNewDataSidebar = val;
    },
    deleteSelected() {
      if (this.selected.length === 0) {
        this.$vs.notify({
          color: "danger",
          text: "Pas de questions séléctionées",
        });
      } else {
        this.$http
          .post("/api/faqs/delete-selected", { faqs: this.selected })
          .then((res) => {
            this.faqs = res.data;
          });
      }
    },
    edit(obj) {
      this.$http
        .post("/api/faqs/" + obj.id + "/edit", obj)
        .then((res) => {
          this.faqs = res.data;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    create(obj) {
      delete obj.id;
      this.$http
        .post("/api/faqs/create", obj)
        .then((res) => {
          this.faqs = res.data;
        })
        .catch((err) => {
          console.error(err);
        });
    },
  },
  created() {
    let self = this;
    this.$vs.loading({
      type: "default",
      text: "Patientez s'il vous plait",
    });
    this.$http
      .get("/api/faqs")
      .then((res) => {
        self.faqs = res.data;
      })
      .catch((err) => {
        console.log(err.response.data);
        self.$vs.dialog({
          color: "danger",
          title: ``,
          text: "Erreur lors de fetch",
        });
      })
      .then(() => self.$vs.loading.close());
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
