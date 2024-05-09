<template>
    <v-app>
      <v-container>
        <v-row justify="center">
          <v-col cols="12" sm="8" md="6">
            <v-card class="calendar-card" elevation="10">
              <v-card-title>Records</v-card-title>
              <v-card-text>
                <v-calendar
                  v-model="selectedDate"
                  color="primary"
                  :events="events"
                  :event-color="getEventColor"
                ></v-calendar>
              </v-card-text>
            </v-card>
          </v-col>
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
        selectedDate: null,
        events: [
          {
            name: "Event 1",
            start: "2024-05-08",
            end: "2024-05-09",
            color: "blue",
          },
          {
            name: "Event 2",
            start: "2024-05-10",
            end: "2024-05-11",
            color: "green",
          },
        ],
      };
    },
    computed: {
      ...mapGetters(["", "", ""]),
    },
    methods: {
      getEventColor(event) {
        return event.color;
      },
    },
    created() {

    },
  };
  </script>
  
  <style scoped>
  .calendar-card {
    max-width: 600px;
    margin: auto;
  }
  </style>
  