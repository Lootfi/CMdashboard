/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

export default [
    {
        url: "/dashboard",
        name: "Dashboard",
        slug: "dashboard",
        icon: "HomeIcon",
        i18n: "Dashboard",
        admin: false
    },
    {
        url: "/types",
        name: "Types des Contacts",
        slug: "contact-types",
        icon: "TagIcon",
        i18n: "Types",
        admin: false
    },
    {
        url: null,
        name: "Contacts",
        slug: "contacts",
        icon: "HeadphonesIcon",
        i18n: "Contacts",
        admin: false,
        submenu: [
            {
                url: "/contacts",
                name: "Liste des contacts",
                slug: "listecontacts",
                i18n: "Contacts"
            },
            {
                url: "/create-contact",
                name: "Ajouter un Contact",
                slug: "ajouter-contact",
                i18n: "Contacts"
            }
        ]
    },
    {
        url: null,
        name: "Editeurs",
        slug: "users",
        icon: "UserIcon",
        i18n: "Editors",
        admin: true,
        submenu: [
            {
                url: "/editors",
                name: "Liste des editeurs",
                slug: "listeediteurs",
                i18n: "Editors"
            },
            {
                url: "/create-editor",
                name: "Ajouter un editeur",
                slug: "ajouter-editeur",
                i18n: "Editors"
            }
        ]
    },
    {
        url: null,
        name: "Artistes",
        slug: "artists",
        icon: "MusicIcon",
        i18n: "artists",
        admin: true,
        submenu: [
            {
                url: "/artists",
                name: "Liste des artistes",
                slug: "listeartists",
                i18n: "Artistes"
            }
        ]
    }
];
