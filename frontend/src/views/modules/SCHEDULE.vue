<template>
  <v-app>
    <v-container>
      <v-row>
        <h1>Schedule</h1>
      </v-row>
      <v-row>
        <v-data-table class="elevation-1" :headers="headers" :items-per-page="1">
        </v-data-table>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>

import { mapGetters } from "vuex";
import moment from 'moment';

export default {
  data() {
    return {
      headers: [
        { title: "Time In", sortable: false },
        { title: "Time Out", sortable: false },
        { title: "Actions", sortable: false },
      ]
    };
  },
  computed: {
    ...mapGetters(["", "", ""]),
  },
  methods: {




  },
  created() {

  },
};
</script>

<style scoped>
/* Add your styling if needed */
</style>
