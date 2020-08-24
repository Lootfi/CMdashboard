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
import axios from "@/axios.js";

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
            path: "/",
            component: () => import("./layouts/main/Main.vue"),
            children: [
                // =============================================================================
                // Theme Routes
                // ============================================================================
                {
                    path: "/",
                    name: "dashboard",
                    component: () => import("@/views/pages/Dashboard.vue"),
                    meta: {
                        rule: "all",
                        requiresAuth: true
                    }
                },
                {
                    path: "/profile-setting",
                    name: "profile-setting",
                    component: ProfileSetting,
                    meta: {
                        rule: "all",
                        requiresAuth: true
                    }
                },
                {
                    path: "/profile",
                    name: "profile",
                    component: Profile,
                    meta: {
                        rule: "all",
                        requiresAuth: true
                    }
                },
                {
                    path: "/editors",
                    name: "editors",
                    component: () =>
                        import("@/views/pages/Editors/EditorIndex.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/editors/:slug",
                    name: "editor",
                    component: () => import("@/views/pages/Editors/Editor.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresEditor: true
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
                        rule: "Admin",
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
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },

                //ARTISTS
                {
                    path: "/clients",
                    name: "clients",
                    component: () =>
                        import(
                            "@/views/pages/Settings/Artists/ArtistIndex.vue"
                        ),
                    meta: {
                        rule: "Commercial",
                        requiresAuth: true,
                        requiresAdmin: true,
                        requiresCommercial: true
                    }
                },
                {
                    path: "/clients/:slug",
                    name: "client",
                    component: () =>
                        import("@/views/pages/Settings/Artists/Artist.vue"),
                    meta: {
                        rule: "Commercial",
                        requiresAuth: true,
                        requiresAdmin: true,
                        requiresCommercial: true
                    }
                },
                {
                    path: "/clients/:slug/edit",
                    name: "client-edit",
                    component: () =>
                        import("@/views/pages/Settings/Artists/ArtistEdit.vue"),
                    meta: {
                        rule: "Admin",
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
                        rule: "Editor"
                        // requiresAuth: true,
                        // requiresAdmin: true,
                        // requiresEditor: true
                    }
                },
                {
                    path: "/contacts/:slug/edit",
                    name: "contact-edit",
                    component: () =>
                        import("@/views/pages/Contacts/ContactEdit.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresAdmin: true,
                        requiresEditor: true
                    }
                },
                {
                    path: "/contacts",
                    name: "contacts",
                    component: () =>
                        import("@/views/pages/Contacts/ContactsIndex.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresAdmin: true,
                        requiresEditor: true
                    }
                },
                {
                    path: "/contacts/:slug",
                    name: "contact",
                    component: () =>
                        import("@/views/pages/Contacts/Contact.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresEditor: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/create-type",
                    name: "contact-type-create",
                    component: () =>
                        import("@/views/pages/ContactTypes/TypeCreate.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/types/:slug/edit",
                    name: "contact-contact-edit",
                    component: () =>
                        import("@/views/pages/ContactTypes/TypeEdit.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/types",
                    name: "types",
                    component: () =>
                        import("@/views/pages/ContactTypes/TypesIndex.vue"),
                    meta: {
                        rule: "Editor",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/templates/:alias/edit",
                    name: "modifier-template",
                    component: () =>
                        import("@/views/pages/Templates/EditTemplate.vue"),
                    meta: {
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/templates/add",
                    name: "add-template",
                    component: () =>
                        import("@/views/pages/Templates/AddTemplate.vue"),
                    meta: {
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/templates",
                    name: "liste-templates",
                    component: () =>
                        import("@/views/pages/Templates/TemplateIndex.vue"),
                    meta: {
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/entreprises",
                    name: "entreprises",
                    component: () =>
                        import("@/views/pages/Entreprises/EntripriseIndex.vue"),
                    meta: {
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/entreprises/add",
                    name: "add-entreprise",
                    component: () =>
                        import("@/views/pages/Entreprises/AddEntriprise.vue"),
                    meta: {
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                },
                {
                    path: "/service/prix",
                    name: "modifier-prix",
                    component: () =>
                        import("@/views/components/price/Price.vue"),
                    meta: {
                        rule: "Admin",
                        requiresAuth: true,
                        requiresAdmin: true
                    }
                }
            ]
        },
        {
            path: "",
            component: () => import("@/layouts/full-page/FullPage.vue"),
            children: [
                {
                    path: "/",
                    name: "page-login",
                    component: () => import("@/views/pages/Auth/Login.vue"),
                    meta: {
                        guest: true,
                        rule: "guest"
                    }
                }
            ]
        },
        {
            path: "/404",
            name: "page-error-404",
            component: () => import("@/views/pages/Error404.vue"),
            meta: {
                rule: "all"
            }
        }
        // Redirect to 404 page, if no match found
        // {
        //     path: "*",
        //     // rule: "all",
        //     redirect: "/404"
        // }
    ]
});

// router.beforeEach((to, from, next) => {
//     const auth = to.matched.some(record => record.meta.requiresAuth);
//     const admin = to.matched.some(record => record.meta.requiresAdmin);
//     const editor = to.matched.some(record => record.meta.requiresEditor);
//     const commercial = to.matched.some(
//         record => record.meta.requiresCommercial
//     );
//     const activated = to.matched.some(record => record.meta.activated);
//     if (auth) {
//         if (localStorage.getItem("jwt") == null) {
//             next({
//                 name: "page-login",
//                 params: { nextUrl: to.fullPath }
//             });
//         } else {
//             let role = JSON.parse(localStorage.getItem("user")).role;
//             if (!admin && !editor && !commercial && role != "") {
//                 next();
//             } else if (admin || editor || commercial) {
//                 if (
//                     (admin && role == "Admin") ||
//                     (editor && role == "Editor") ||
//                     (commercial && role == "Commercial")
//                 ) {
//                     next();
//                 } else {
//                     next({ name: "dashboard" });
//                 }
//             } else {
//                 if (
//                     activated &&
//                     localStorage.getItem("user").StatusName == "Suspendu"
//                 ) {
//                     next({ name: "dashboard" });
//                 } else {
//                     next();
//                 }
//             }
//         }
//     } else if (to.matched.some(record => record.meta.guest)) {
//         if (localStorage.getItem("jwt") == null) {
//             next();
//         } else {
//             next({ name: "dashboard" });
//         }
//     } else {
//         next();
//     }
// });

router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById("loading-bg");
    if (appLoading) {
        appLoading.style.display = "none";
    }
});

export default router;
