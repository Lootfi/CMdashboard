<!-- =========================================================================================
    File Name: Login.vue
    Description: Login Page
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template>
    <div
        class="h-screen flex w-full bg-img vx-row no-gutter items-center justify-center"
        id="page-login"
    >
        <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4">
            <div slot="no-body" class="full-page-bg-color">
                <div class="vx-row no-gutter justify-center items-center">
                    <div class="vx-col hidden lg:block text-center lg:w-1/2">
                        <img
                            src="/images/logos/logo-black.svg"
                            alt="login"
                            class="mx-auto"
                        />
                    </div>

                    <div
                        class="vx-col sm:w-full md:w-full lg:w-1/2 d-theme-dark-bg "
                    >
                        <div
                            class="px-8 pt-8 login-tabs-container flex items-center justify-center"
                        >
                            <div>
                                <div class="vx-card__title mb-5 text-center">
                                    <h4 class="mb-4">Connexion</h4>
                                </div>

                                <div class="mt-4">
                                    <login-jwt></login-jwt>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoginJwt from "./LoginJWT.vue";

export default {
    components: {
        LoginJwt
    }
};
</script>

<style lang="scss">
.login-tabs-container {
    min-height: 505px;

    .con-tab {
        padding-bottom: 14px;
    }

    .con-slot-tabs {
        margin-top: 1rem;
    }
}
</style>
