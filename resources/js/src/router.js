/*=========================================================================================
  File Name: router.js
  Description: Routes for vue-router. Lazy loading is enabled.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

import Vue from "vue";
import Router from "vue-router";
import Profile from "./views/pages/User/Profile.vue";
import ProfileSetting from "./views/pages/User/ProfileSettings/ProfileSetting.vue";
import axios from "./axios.js";

Vue.use(Router);

const router = new Router({
    mode: "history",
    base: "/",
    scrollBehavior() {
        return { x: 0, y: 0 };
    },

    routes: [
        {
            // =============================================================================
            // MAIN LAYOUT ROUTES
            // =============================================================================
            path: "",
            component: () => import("./layouts/main/Main.vue"),
            children: [
                // =============================================================================
                // Theme Routes
                // ============================================================================
                {
                    path: "/dashboard",
                    name: "dashboard",
                    component: () => import("@/views/pages/Dashboard.vue"),
                    meta: {
                        requiresAuth: true
                    }
                },
                {
                    path: "/profile-setting",
                    name: "profile-setting",
                    component: ProfileSetting,
                    meta: {
                        requiresAuth: true
                    }
                },
                {
                    path: "/editors",
                    name: "editors",
                    component: () =>
                        import("@/views/pages/Editors/EditorIndex.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/editors/:slug",
                    name: "editor",
                    component: () => import("@/views/pages/Editors/Editor.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/editors/:slug/edit",
                    name: "editor-edit",
                    component: () =>
                        import(
                            "@/views/pages/Editors/editor-edit/EditorEdit.vue"
                        ),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/create-editor",
                    name: "editor-create",
                    component: () =>
                        import("@/views/pages/Editors/EditorCreate.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/settings/artists",
                    name: "artists",
                    component: () =>
                        import(
                            "@/views/pages/Settings/Artists/ArtistIndex.vue"
                        ),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/artists/:slug",
                    name: "artist",
                    component: () =>
                        import("@/views/pages/Settings/Artists/Artist.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/artists/:slug/edit",
                    name: "artist-edit",
                    component: () =>
                        import("@/views/pages/Settings/Artists/ArtistEdit.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },

                // CONTACTS
                {
                    path: "/create-contact",
                    name: "contact-create",
                    component: () =>
                        import("@/views/pages/Contacts/ContactCreate.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: false
                    }
                },
                {
                    path: "/contacts/:slug/edit",
                    name: "contact-edit",
                    component: () =>
                        import("@/views/pages/Contacts/ContactEdit.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: false
                    }
                },
                {
                    path: "/contacts",
                    name: "contacts",
                    component: () =>
                        import("@/views/pages/Contacts/ContactsIndex.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: false
                    }
                },
                {
                    path: "/contacts/:slug",
                    name: "contact",
                    component: () =>
                        import("@/views/pages/Contacts/Contact.vue"),
                    meta: {
                        requiresAuth: true,
                        requiresAdmin: false
                    }
                }
            ]
        },
        {
            path: "",
            component: () => import("@/layouts/full-page/FullPage.vue"),
            children: [
                {
                    path: "/login",
                    name: "page-login",
                    component: () => import("@/views/pages/Auth/Login.vue"),
                    meta: {
                        guest: true
                    }
                }
            ]
        },
        // Redirect to 404 page, if no match found
        {
            path: "*",
            redirect: "/pages/error-404"
        }
    ]
});

router.beforeEach((to, from, next) => {
    let requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    let requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);
    let guest = to.matched.some(record => record.meta.guest);

    if (window.location.pathname == "/")
        window.location.pathname = "/dashboard";
    // if (to == "/") next("/dashboard");

    // if (
    //     (!localStorage.getItem("jwt") || !localStorage.getItem("user")) &&
    //     !requiresAuth
    // ) {
    //     //if there are no token or user in storage and the route doesn't require auth
    //     next({
    //         path: "/login",
    //         params: { nextUrl: to.fullPath }
    //     });
    // } else {
    //     // check if jwt and user verify and match database here
    //     // if they don't match do this
    //     localStorage.removeItem("jwt");
    //     localStorage.removeItem("user");
    //     next({
    //         path: "/login",
    //         params: { nextUrl: to.fullPath }
    //     });

    //     //if they do match do this
    //     let user = JSON.parse(localStorage.getItem("user"));
    // }

    if (requiresAuth) {
        if (localStorage.getItem("jwt") == null) {
            next({
                name: "page-login",
                params: { nextUrl: to.fullPath }
            });
        } else {
            //we need to check the "jwt" and "user" before using them

            let user = JSON.parse(localStorage.getItem("user"));
            if (requiresAdmin) {
                if (user.role == "Admin") {
                    next();
                } else {
                    next("/dashboard");
                }
            } else {
                next();
            }
        }
    } else if (guest) {
        if (localStorage.getItem("jwt") == null) {
            next();
        } else {
            next("/dashboard");
        }
    } else {
        next();
    }
});

router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById("loading-bg");
    if (appLoading) {
        appLoading.style.display = "none";
    }
});

export default router;
