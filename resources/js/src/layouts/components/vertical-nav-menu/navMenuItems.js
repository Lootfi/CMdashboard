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
        url: "/",
        name: "Tableau de bord",
        slug: "dashboard",
        icon: "HomeIcon",
        i18n: "Dashboard",
        admin: false,
        meta: "all"
    },

    {
        url: null,
        name: "Contacts",
        slug: "contacts",
        icon: "HeadphonesIcon",
        i18n: "Contacts",
        admin: false,
        meta: "editor",
        submenu: [
            {
                url: "/contacts",
                name: "Liste des contacts",
                slug: "listecontacts",
                i18n: "Contacts"
                // admin: true,
                // editor: true,
                // commercial: false
            },
            {
                url: "/create-contact",
                name: "Ajouter un Contact",
                slug: "ajouter-contact",
                i18n: "Contacts"
            },
            {
                url: "/types",
                name: "Types des Contacts",
                slug: "contact-types",
                i18n: "Types"
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
        meta: "admin",
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
        name: "Clients",
        slug: "clients",
        icon: "MusicIcon",
        i18n: "clients",
        admin: false,
        meta: "commercial",
        submenu: [
            {
                url: "/clients",
                name: "Liste des clients",
                slug: "listeclients",
                i18n: "Clients"
            }
        ]
    },
    {
        url: null,
        name: "Emails Templates",
        slug: "emails",
        icon: "MailIcon",
        i18n: "emails",
        admin: true,
        meta: "admin",
        submenu: [
            {
                url: "/templates",
                name: "Liste des Templates",
                slug: "templates-liste",
                i18n: "Liste Templates"
            },
            {
                url: "/templates/add",
                name: "Ajouter Template",
                slug: "ajouter-template",
                i18n: "Ajouter Template"
            }
        ]
    },
    {
        url: null,
        name: "Entreprises",
        slug: "entreprises",
        icon: "BoxIcon",
        i18n: "entreprises",
        admin: true,
        meta: "admin",
        submenu: [
            {
                url: "/entreprises",
                name: "Liste des Labels",
                slug: "entreprises",
                i18n: "Liste Labels"
            },
            {
                url: "/create-entreprise",
                name: "Ajouter un Label",
                slug: "entreprises",
                i18n: "Ajouter Label"
            }
        ]
    },
    {
        url: null,
        name: "Paramètres",
        slug: "service",
        icon: "SettingsIcon",
        i18n: "service",
        admin: true,
        meta: "admin",
        submenu: [
            {
                url: "/service/prix",
                name: "Modifier Prix",
                slug: "modifier-prix",
                i18n: "Modifier Prix"
            },
            {
                url: "/service/faq",
                name: "Questions Fréquentes",
                slug: "modifier-faq",
                i18n: "Modifier FAQ"
            }
        ]
    }
];
