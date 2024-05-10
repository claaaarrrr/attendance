<template>
  <v-app>
    <v-container class="d-flex justify-center">
      <v-row>
        <h1>Schedule</h1>
      </v-row>
      <v-row>
        <v-card>
          <v-card-title>
            Time Scheduling
          </v-card-title>
          <v-divider></v-divider>
          <v-card-subtitle>
          </v-card-subtitle>
          <v-card-text>
            <v-row>
              <v-col cols="6">
                <label for="">Time In</label>
                {{ GET_TIME.in }} AM
              </v-col>
              <v-col cols="6">
                <label for="">Time Out</label>
                {{ GET_TIME.out }} PM
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn size="small" color="primary" @click="updateTime(GET_TIME.id)">
              <v-icon icon="mdi-pencil"></v-icon>
              Update Time
            </v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>

        <v-dialog max-width="750" v-model="clockdialog" persistent>
          <v-card>
            <v-card-title>
              <v-row class="ma-2">
                Update Time
                <v-spacer></v-spacer>
                <v-btn size="small" icon elevation="0" @click="closeDialog">
                  <v-icon icon="mdi-close" color="red"></v-icon>
                </v-btn>
              </v-row>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-subtitle></v-card-subtitle>
            <v-card-text>
              <v-row justify="space-around">
                <v-col cols="6">
                  <label for="">Time In</label>
                  <VTimePicker v-model="timeIn" format="24hr"></VTimePicker>
                </v-col>
                <v-col cols="6">
                  <label for="">Time Out</label>
                  <VTimePicker v-model="timeOut" format="24hr"></VTimePicker>
                </v-col>
              </v-row>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn size="small" color="success" @click="confirmSchedule">Confirm</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
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
      sched_id: null,
      clockdialog: false,
      timeIn: null,
      timeOut: null,
    };
  },
  computed: {
    ...mapGetters(["GET_TIME", "", ""]),
  },
  methods: {
    confirmSchedule() {
      const payload = {
        id: this.sched_id,
        time_in: this.timeIn,
        time_out: this.timeOut
      }
      console.log(this.timeIn);
      this.$store.dispatch('updateSchedule', payload).then((response) => {
        this.$swal.fire({
          text: response.data.message,
          icon: response.data.icon,
          title: response.data.title,
        });
        this.fetchSched();
        this.closeDialog();
      });
    },
    updateTime(id) {
      this.sched_id = id;
      this.clockdialog = true;
    },
    closeDialog() {
      this.clockdialog = false;
      this.sched_id = null;
      this.timeIn = '00:00';
      this.timeOut = '00:00';
    },
    fetchSched() {
      this.$store.dispatch('getSchedule');
    }

  },
  created() {
    this.fetchSched();
  },
};
</script>

<style scoped>
/* Add your styling if needed */
</style>
