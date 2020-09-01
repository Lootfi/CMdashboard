<template>
  <div>
    <h3 class="mb-2">Liste des Templates</h3>
    <div>
      <div id="page-user-list">
        <div class="vx-card p-6">
          <div class="flex flex-wrap items-center">
            <!-- ITEMS PER PAGE -->
            <div class="flex-grow">
              <vs-dropdown vs-trigger-click class="cursor-pointer">
                <div
                  class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
                >
                  <span
                    class="mr-2"
                  >{{ currentPage * paginationPageSize - (paginationPageSize - 1) }} - {{ templates.length - currentPage * paginationPageSize > 0 ? currentPage * paginationPageSize : templates.length }} of {{ templates.length }}</span>
                  <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
                </div>
                <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
                <vs-dropdown-menu>
                  <vs-dropdown-item @click="gridApi.paginationSetPageSize(10)">
                    <span>10</span>
                  </vs-dropdown-item>
                  <vs-dropdown-item @click="gridApi.paginationSetPageSize(20)">
                    <span>20</span>
                  </vs-dropdown-item>
                  <vs-dropdown-item @click="gridApi.paginationSetPageSize(25)">
                    <span>25</span>
                  </vs-dropdown-item>
                  <vs-dropdown-item @click="gridApi.paginationSetPageSize(30)">
                    <span>30</span>
                  </vs-dropdown-item>
                </vs-dropdown-menu>
              </vs-dropdown>
            </div>

            <!-- TABLE ACTION COL-2: SEARCH & EXPORT AS CSV -->
            <vs-input
              class="sm:mr-4 mr-0 sm:w-auto w-full sm:order-normal order-3 sm:mt-0 mt-4"
              v-model="searchQuery"
              @input="updateSearchQuery"
              placeholder="Search..."
            />
            <vs-button
              icon-pack="feather"
              icon="icon-refresh-ccw"
              class="mb-4 md:mb-0"
              :disabled="refreshing"
              @click="refreshTemplates()"
            >Rafraîchir</vs-button>
          </div>

          <!-- AgGrid Table -->
          <ag-grid-vue
            ref="agGridTable"
            :components="components"
            :gridOptions="gridOptions"
            class="ag-theme-material w-100 my-4 ag-grid-table"
            :columnDefs="columnDefs"
            :defaultColDef="defaultColDef"
            :rowData="templates"
            rowSelection="multiple"
            colResizeDefault="shift"
            :animateRows="true"
            :floatingFilter="true"
            :pagination="true"
            :paginationPageSize="paginationPageSize"
            :suppressPaginationPanel="true"
            :enableRtl="$vs.rtl"
          ></ag-grid-vue>

          <vs-pagination :total="totalPages" :max="7" v-model="currentPage" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { AgGridVue } from "ag-grid-vue";
import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
import vSelect from "vue-select";

// Cell Renderer
import CellRendererActions from "./cell-renderer/CellRendererActions.vue";

export default {
  components: {
    AgGridVue,
    vSelect,
    CellRendererActions,
  },
  data() {
    return {
      templates: [],
      refreshing: false,
      searchQuery: "",

      // AgGrid
      gridApi: null,
      gridOptions: {},
      defaultColDef: {
        sortable: true,
        resizable: true,
        suppressMenu: true,
      },
      columnDefs: [
        {
          headerName: "ID",
          field: "id",
          width: 200,
          filter: true,
          checkboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          headerCheckboxSelection: true,
        },
        {
          headerName: "Nom",
          field: "name",
          filter: true,
          width: 300,
        },
        {
          headerName: "Sujet",
          field: "subject",
          filter: true,
          width: 400,
        },
        {
          headerName: "Actions",
          field: "transactions",
          width: 150,
          cellRendererFramework: "CellRendererActions",
        },
      ],
      components: {
        CellRendererActions,
      },
    };
  },
  computed: {
    paginationPageSize() {
      if (this.gridApi) return this.gridApi.paginationGetPageSize();
      else return 10;
    },
    totalPages() {
      if (this.gridApi) return this.gridApi.paginationGetTotalPages();
      else return 0;
    },
    currentPage: {
      get() {
        if (this.gridApi) return this.gridApi.paginationGetCurrentPage() + 1;
        else return 1;
      },
      set(val) {
        this.gridApi.paginationGoToPage(val - 1);
      },
    },
  },
  mounted() {
    this.gridApi = this.gridOptions.api;

    this.$http
      .get("/api/templates")
      .then((res) => {
        this.templates = res.data;
      })
      .catch((err) => console.log(err.response.data));
  },
  methods: {
    updateSearchQuery(val) {
      this.gridApi.setQuickFilter(val);
    },
    refreshTemplates() {
      this.refreshing = true;
      this.$http
        .get("/api/templates/refresh-templates")
        .then((res) => {
          this.refreshing = false;
          this.templates = res.data;
        })
        .catch((err) => {
          this.refreshing = false;
          this.$vs.notify({
            color: "danger",
            text: "Erreur lors de l'actualisation",
          });
        });
    },
  },
};
</script>

<style lang="scss">
#page-user-list {
  .user-list-filters {
    .vs__actions {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-58%);
    }
  }
}
</style>