<template>
  <div id="data-list-thumb-view" class="data-list-container">
    <vs-table
      ref="table"
      multiple
      v-model="selected"
      pagination
      :max-items="itemsPerPage"
      search
      :data="articles"
    >
      <div
        slot="header"
        style="flex:0 0 auto;"
        class="flex flex-wrap-reverse items-center justify-between"
      >
        <div class="flex flex-wrap-reverse items-center">
          <!-- ADD NEW -->
          <div
            class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center text-lg font-medium text-base text-primary border border-solid border-primary"
            @click="$router.push('/create-article')"
          >
            <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
            <span class="ml-2 text-base text-primary">Ajouter un article</span>
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
                products.length - currentPage * itemsPerPage > 0
                  ? currentPage * itemsPerPage
                  : products.length
              }}
              de {{ queriedItems }}</span
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
        <vs-th>Image</vs-th>
        <vs-th sort-key="name">Nom</vs-th>
        <vs-th sort-key="category">Categorie</vs-th>
        <vs-th sort-key="popularity">Ecrit par</vs-th>
        <vs-th sort-key="order_status">Ecrit le</vs-th>
        <vs-th>Actions</vs-th>
      </template>

      <template slot-scope="{ data }">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td class="img-container">
              <img
                class="product-img w-full"
                v-if="tr.image"
                :src="`${tr.Avatar}`"
                @click.stop="$router.push(`/articles/${tr.tag}`)"
              />
            </vs-td>

            <vs-td>
              <p
                class="product-name font-medium truncate"
                @click.stop="$router.push(`/articles/${tr.tag}`)"
              >
                {{ tr.titre }}
              </p>
            </vs-td>

            <vs-td>
              <p class="product-category">{{ tr.Category.nom }}</p>
            </vs-td>

            <vs-td>
              <p class="product-name font-medium truncate">
                {{ tr.Creator.Full_Name }}
              </p>
            </vs-td>

           <vs-td>
              <vx-tooltip :text="tr.created_at">
              <p class="product-name font-medium truncate">{{ tr.CreatedAtAgo }}</p>
            </vx-tooltip>
            </vs-td>
            <vs-td class="whitespace-no-wrap">
              <div  class="flex px-2">
              <vx-tooltip text="Modifier">
                <feather-icon
                  v-if="isJsonArticle(tr)"
                  icon="EditIcon"
                  svgClasses="w-5 h-5 hover:text-primary stroke-current"
                  @click="$router.push(`/articles/${tr.tag}/edit`)"
                />
              </vx-tooltip>
                <vx-tooltip text="Supprimer">
                <feather-icon
                  @click.stop="openDelete(tr.tag)"
                  icon="TrashIcon"
                  svgClasses="w-5 h-5 hover:text-danger stroke-current"
                  class="ml-2"
                />
              </vx-tooltip>
                <vx-tooltip text="Créer instant article">
                <feather-icon
                  v-if="isJsonArticle(tr)"
                  @click.stop="exportMarkup(tr.tag)"
                  icon="FacebookIcon"
                  svgClasses="w-5 h-5 hover:text-primary
              </div>
            </vs-td>y stroke-current"
                  class="ml-2"
                />
              </vx-tooltip>
            </div>
          </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selected: [],
      itemsPerPage: 10,
      isMounted: false,
      addNewDataSidebar: false,
      sidebarData: {},
      activeConfirm: false,
      articles: [],
    };
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    },
    products() {
      return this.articles;
    },
    queriedItems() {
      return this.$refs.table
        ? this.$refs.table.queriedResults.length
        : this.articles.length;
    },
    activeUserInfo() {
      return this.$store.state.AppActiveUser;
    },
  },
  created() {
    this.$http
      .get("/api/user/my-article", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("jwt")}`,
        },
      })
      .then((response) => {
        this.articles = response.data;
        this.isMounted = true;
      })
      .catch((error) => {});
  },

  methods: {
    openDelete(tag) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Suppression`,
        text: "Etes vous sur de vouloir supprimer le present article",
        accept: this.handleDelete,
        parameters: tag,
      });
    },

    isJsonArticle(row) {
      if (row.ContenuFormat.type == "raw") {
        return false;
      }

      return true;
    },
    handleDelete(parameters) {
      this.$store.dispatch("dataList/removeItem", parameters).catch((err) => {
        console.error(err);
      });
    },
    exportMarkup(tag) {
      this.$http.get(`/api/${tag}/exportToFacebookArticle`).then((response) => {
        console.log(response);
      });
    },
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