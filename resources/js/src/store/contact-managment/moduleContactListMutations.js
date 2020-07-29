/*=========================================================================================
  File Name: moduleCalendarMutations.js
  Description: Calendar Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

export default {
    ADD_ITEM(state, item) {
        state.contacts.unshift(item);
    },
    SET_CONTACTS(state, contacts) {
        state.contacts = contacts;
    },
    UPDATE_CONTACT(state, contact) {
        const contactIndex = state.contacts.findIndex(p => p.id === contact.id);
        Object.assign(state.contacts[contactIndex], contact);
    },
    REMOVE_ITEM(state, itemSlug) {
        const ItemIndex = state.contacts.findIndex(p => p.tag === itemSlug);
        state.contacts.splice(ItemIndex, 1);
    }
};
