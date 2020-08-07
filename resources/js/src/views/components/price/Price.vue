<template>
  <div v-if="$acl.check('Admin')">
    <vx-card v-if="price && !changing">
      <h3 class="mb-2">Prix de service</h3>
      <h5>{{ price }}€</h5>
      <vs-button
        class="mt-4 mr-2 shadow-lg"
        type="gradient"
        color="#7367F0"
        gradient-color-secondary="#CE9FFC"
        @click="changePrice"
      >Changer le prix</vs-button>
    </vx-card>
    <vx-card v-if="changing">
      <div class="vx-row">
        <div class="vx-col md:w-1/2 w-full">
          <vs-input
            class="w-full mt-4"
            label="Prix de service"
            name="price"
            v-model="price"
            v-validate="'numeric|min_value:1|required'"
          />
          <span class="text-danger text-sm" v-show="errors.has('price')">
            {{
            errors.first("price")
            }}
          </span>
        </div>
      </div>
      <div class="vx-row">
        <div class="vx-col w-full">
          <div class="mt-8 flex flex-wrap items-center justify-end">
            <vs-button class="ml-auto mt-2" @click="handlePriceSubmit">Sauvegarder le nouveau prix</vs-button>
          </div>
        </div>
      </div>
    </vx-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      price: null,
      changing: false,
    };
  },
  mounted() {
    this.$http
      .get("/api/price")
      .then((res) => {
        this.price = res.data;
      })
      .catch((err) => {
        console.log(err.response.data);
        this.price = null;
      });
  },
  methods: {
    changePrice() {
      this.changing = !this.changing;
    },
    handlePriceSubmit() {
      this.$http
        .post("/api/price-change", { price: this.price })
        .then((res) => {
          this.$vs.dialog({
            color: "primary",
            title: ``,
            text: "Le prix a été mise à jour!",
          });
          this.price = res.data;
          this.changing = false;
          this.$router.push({ name: "dashboard" });
        })
        .catch((err) => {
          console.log(err.response.data);
        });
    },
  },
};
</script>

<style>
</style>