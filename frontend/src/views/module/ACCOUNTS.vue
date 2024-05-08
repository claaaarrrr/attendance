<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="6">
        <v-card class="user-info-container" elevation="10">
          <v-card-title>User Information</v-card-title>
          <v-card-text>
            <v-row align="center">
              <v-col>
                <img :src="USER_DETAILS.base64img" alt="Profile Picture" width="150" class="mr-2 profile-picture" />
              </v-col>
            </v-row>
            <v-row align="center">
              <v-col>
                <strong>Name:</strong> {{ USER_DETAILS.name }}
              </v-col>
            </v-row>
            <v-row align="center">
              <v-col>
                <strong>Gender:</strong> {{ USER_DETAILS.gender }}
              </v-col>
            </v-row>
            <v-row align="center">
              <v-col>
                <strong>Email:</strong> {{ USER_DETAILS.email }}
              </v-col>
            </v-row>
            <v-row align="center">
              <v-col>
                <strong>Address:</strong> {{ USER_DETAILS.address }}
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary">Edit Profile</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>


<script>
import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters(['USER_DETAILS']),
  },
  data() {
    return {

    }
  },
};
</script>