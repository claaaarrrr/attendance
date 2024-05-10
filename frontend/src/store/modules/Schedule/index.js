import api from "@/api/index";
import Swal from "sweetalert2";

export default {
  state: {
    time: {
      in: null,
      out: null,
    },
  },

  getters: {
    GET_TIME: (state) => state.time,
    GET_IN: (state) => state.time.in,
    GET_OUT: (state) => state.time.out,
  },

  mutations: {
    SET_TIME: (state, data) => {
      state.time = data;
    },
    SET_IN: (state, data) => {
      state.time.in = data;
    },
    SET_OUT: (state, data) => {
      state.time.out = data;
    },
  },

  actions: {
    getSchedule({ commit }) {
      return new Promise((resolve, reject) => {
        api
          .get("api/getSchedule")
          .then((response) => {
            if ((response.status = 200)) {
              commit("SET_TIME", response.data);
              commit("SET_IN", response.data.time_in);
              commit("SET_OUT", response.data.time_out);
              resolve(response);
            }
          })
          .catch((error) => {
            reject(error);
          });
      });
    },

    updateSchedule({ commit }, params) {
      return new Promise((resolve, reject) => {
        api
          .put("api/updateSchedule", params)
          .then((response) => {
            if (response.status == 200 || 204) {
              resolve(response);
            }
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
};
