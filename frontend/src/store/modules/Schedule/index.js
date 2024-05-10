import api from "@/api/index";

export default {
  state: {
    time: {
      in: null,
      out: null,
    },
  },

  getters: {
    GET_IN: (state) => state.time.in,
    GET_OUT: (state) => state.time.out,
  },

  mutations: {
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
              commit("SET_IN", response.data[0].time_in);
              commit("SET_OUT", response.data[0].time_out);
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
