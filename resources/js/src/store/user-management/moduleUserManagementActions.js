/*=========================================================================================
  File Name: moduleCalendarActions.js
  Description: Calendar Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

import axios from "@/axios.js";

export default {
    // addItem({ commit }, item) {
    //   return new Promise((resolve, reject) => {
    //     axios.post("/api/data-list/products/", {item: item})
    //       .then((response) => {
    //         commit('ADD_ITEM', Object.assign(item, {id: response.data.id}))
    //         resolve(response)
    //       })
    //       .catch((error) => { reject(error) })
    //   })
    // },
    fetchUsers({ commit }) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/editors")
                .then(response => {
                    commit("SET_USERS", response.data);
                    resolve(response);
                })
                .catch(error => {
                    console.log(error.response.data);
                    reject(error);
                });
        });
    },
    fetchUser() {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/user`)
                .then(response => {
                    commit("SET_USER", response.data);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    removeRecord({ commit }, userSlug) {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/editors/${userSlug}/delete`)
                .then(response => {
                    commit("REMOVE_ITEM", userSlug);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }
};
