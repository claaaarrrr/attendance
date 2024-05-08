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
            <v-row align="center" justify="center">
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
            <v-btn color="primary" @click="editProfile">Edit Profile</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-model="editProfileDialogVisible" max-width="600">
      <v-card>
        <v-card-title>Edit Profile</v-card-title>
        <v-card-text>
          <v-form ref="profileForm">
            <img v-if="editedUserDetails.base64img" :src="editedUserDetails.base64img" alt="Current Profile Picture" width="150" class="mr-2 profile-picture" />
            <v-file-input
              label="Upload New Profile Picture"
              accept="image/jpeg,image/png,image/jpg"
              @change="handleFileUpload"
            ></v-file-input>
            <v-text-field label="Name" v-model="editedUserDetails.name"></v-text-field>
            <v-text-field label="Gender" v-model="editedUserDetails.gender"></v-text-field>
            <v-text-field label="Email" v-model="editedUserDetails.email"></v-text-field>
            <v-text-field label="Address" v-model="editedUserDetails.address"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" @click="saveProfile">Save</v-btn>
          <v-btn color="red darken-1" @click="cancelEdit">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      editProfileDialogVisible: false,
      editedUserDetails: {
        base64img: '',
        name: '',
        gender: '',
        email: '',
        address: '',
      },
    }
  },
  methods: {
    editProfile() {
      this.editedUserDetails = { ...this.USER_DETAILS };
      this.editProfileDialogVisible = true;
    },
    handleFileUpload(files) {
      const file = files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.editedUserDetails.base64img = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    saveProfile() {
      console.log('Edited User Details:', this.editedUserDetails);
      this.editProfileDialogVisible = false;
    },
    cancelEdit() {
      this.editProfileDialogVisible = false;
    }
  }
};
</script>
