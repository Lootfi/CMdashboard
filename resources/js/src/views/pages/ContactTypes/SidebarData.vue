<template>
  <vs-sidebar
    click-not-close
    position-right
    parent="body"
    default-index="1"
    color="primary"
    class="add-new-data-sidebar items-no-padding"
    spacer
    v-model="isSidebarActiveLocal"
  >
    <div class="mt-6 flex items-center justify-between px-6">
      <h4>{{ Object.entries(this.data).length === 0 ? "AJOUTER UN NOUVEAU" : "MODIFIER" }} TYPE</h4>
      <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
    </div>
    <vs-divider class="mb-0"></vs-divider>

    <component
      :is="scrollbarTag"
      class="scroll-area--data-list-add-new"
      :settings="settings"
      :key="$vs.rtl"
    >
      <div class="p-6">
        <!-- NAME -->
        <vs-input
          label="Nom"
          v-model="dataName"
          class="mt-5 w-full"
          name="name"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>
      </div>
    </component>

    <div class="flex flex-wrap items-center p-6" slot="footer">
      <vs-button class="mr-6" @click="submitData" :disabled="!isFormValid">{{$t('save')}}</vs-button>
      <vs-button type="border" color="danger" @click="isSidebarActiveLocal = false">Annuler</vs-button>
    </div>
  </vs-sidebar>
</template>

<script>
import VuePerfectScrollbar from "vue-perfect-scrollbar";

export default {
  props: {
    isSidebarActive: {
      type: Boolean,
      required: true,
    },
    data: {
      type: Object,
      default: () => {},
    },
  },
  components: {
    VuePerfectScrollbar,
  },
  data() {
    return {
      dataId: null,
      dataName: "",
      dataSlug: "",
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
    };
  },
  watch: {
    isSidebarActive(val) {
      if (!val) return;
      if (Object.entries(this.data).length === 0) {
        this.initValues();
        this.$validator.reset();
      } else {
        const { id, name, slug } = JSON.parse(JSON.stringify(this.data));
        this.dataId = id;
        this.dataName = name;
        this.dataSlug = slug;
        this.initValues();
      }
      // Object.entries(this.data).length === 0 ? this.initValues() : { this.dataId, this.dataName, this.dataCategory, this.dataOrder_status, this.dataPrice } = JSON.parse(JSON.stringify(this.data))
    },
  },
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive;
      },
      set(val) {
        if (!val) {
          this.$emit("closeSidebar");
          // this.$validator.reset()
          // this.initValues()
        }
      },
    },
    isFormValid() {
      return !this.errors.any() && this.dataName != "";
    },
    scrollbarTag() {
      return this.$store.getters.scrollbarTag;
    },
  },
  methods: {
    initValues() {
      if (this.data.id) return;
      this.dataId = null;
      this.dataName = "";
      this.dataSlug = null;
    },
    submitData() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          const obj = {
            id: this.dataId,
            name: this.dataName,
            slug: this.dataSlug,
          };

          if (this.dataId !== null && this.dataId >= 0) {
            this.$http
              .post("/api/types/" + obj.slug + "/edit", obj)
              .catch((err) => {
                console.error(err);
              });
          } else {
            delete obj.id;
            this.$http.post("/api/types/create", obj).catch((err) => {
              console.error(err);
            });
          }

          this.$emit("closeSidebar");
          this.initValues();
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.add-new-data-sidebar {
  ::v-deep .vs-sidebar--background {
    z-index: 52010;
  }

  ::v-deep .vs-sidebar {
    z-index: 52010;
    width: 400px;
    max-width: 90vw;

    .img-upload {
      margin-top: 2rem;

      .con-img-upload {
        padding: 0;
      }

      .con-input-upload {
        width: 100%;
        margin: 0;
      }
    }
  }
}

.scroll-area--data-list-add-new {
  // height: calc(var(--vh, 1vh) * 100 - 4.3rem);
  height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 82px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style>
