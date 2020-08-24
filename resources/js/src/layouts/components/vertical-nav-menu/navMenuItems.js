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
                i18n: "Contacts",
                admin: true,
                editor: true,
                commercial: false
            },
            {
                url: "/create-contact",
                name: "Ajouter un Contact",
                slug: "ajouter-contact",
                i18n: "Contacts",
                admin: true,
                editor: true,
                commercial: false
            },
            {
                url: "/types",
                name: "Types des Contacts",
                slug: "contact-types",
                i18n: "Types",
                admin: true,
                editor: false,
                commercial: false
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
        name: "Clients",
        slug: "clients",
        icon: "MusicIcon",
        i18n: "clients",
        admin: false,
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
        submenu: [
            {
                url: "/templates/add",
                name: "Ajouter Template",
                slug: "ajouter-template",
                i18n: "Ajouter Template"
            },
            {
                url: "/templates/modifier",
                name: "Modifier Template",
                slug: "modifier-template",
                i18n: "Modifier Template"
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
        submenu: [
            {
                url: "/entreprises/add",
                name: "Ajouter Entreprise",
                slug: "ajouter-entreprise",
                i18n: "Ajouter Entreprise"
            },
            {
                url: "/entreprises",
                name: "Liste des Entreprise",
                slug: "entreprises",
                i18n: "Modifier Entreprise"
            }
        ]
    }
];
