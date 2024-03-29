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
    fetchArtists({ commit }) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/artists")
                .then(response => {
                    commit("SET_ARTISTS", response.data);
                    resolve(response);
                })
                .catch(error => {
                    console.log(error.response.data);
                    reject(error);
                });
        });
    },
    fetchArtist(context, artistSlug) {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/artists/${artistSlug}`)
                .then(response => {
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    removeItem({ commit }, artistSlug) {
        return new Promise((resolve, reject) => {
            axios
                .delete(`/api/artists/${artistSlug}/delete`)
                .then(response => {
                    commit("REMOVE_RECORD", artistSlug);
                    resolve(response);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }
};
