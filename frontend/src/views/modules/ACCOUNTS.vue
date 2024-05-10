<template>
  <v-container>
    <v-card class="user-info-container d-flex flex-column justify-center align-center" elevation="10">
      <v-card-title class="d-flex justify-center"><strong>User Information</strong></v-card-title>
      <v-card-text class="d-flex flex-column justify-center align-center">
        <v-row>
          <!-- <v-col class="d-flex justify-center">
            <img :src="USER_DETAILS.base64img" alt="Profile Picture" width="150" class="mr-2 profile-picture" />
          </v-col> -->
        </v-row>
        <v-row class="d-flex justify-center">
          <v-col>
            <strong>Name:</strong> {{ USER_DETAILS.name }}
          </v-col>
        </v-row>
        <v-row class="d-flex justify-center">
          <v-col>
            <strong>Suffix:</strong> {{ USER_DETAILS.suffix }}
          </v-col>
        </v-row>
        <v-row class="d-flex justify-center">
          <v-col>
            <strong>Gender:</strong> {{ USER_DETAILS.gender }}
          </v-col>
        </v-row>
        <v-row class="d-flex justify-center">
          <v-col>
            <strong>Email:</strong> {{ USER_DETAILS.email }}
          </v-col>
        </v-row>
        <v-row class="d-flex justify-center">
          <v-col>
            <strong>Address:</strong> {{ USER_DETAILS.address }}
          </v-col>
        </v-row>
        <v-row class="d-flex justify-center">
          <v-col>
            <v-btn color="primary" @click="editMode = true; formData = { ...USER_DETAILS }">Edit Profile</v-btn>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-dialog v-model="editMode" max-width="500px">
      <v-card>
        <v-card-title>Edit Profile</v-card-title>
        <v-card-text>
          <v-row>
            <v-col class="d-flex justify-center">
              <!-- <img :src="USER_DETAILS.base64img" alt="Profile Picture" width="150" class="mr-2 profile-picture" /> -->
              <!-- <v-file-input v-model="formData.profile_pic" label="Profile Picture"></v-file-input> -->
            </v-col>
          </v-row>
          <v-form @submit.prevent="updateUser()">
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="formData.first_name" label="First Name"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="formData.middle_name" label="Middle Name"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="formData.last_name" label="Last Name"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="formData.suffix" label="Suffix"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-select v-model="formData.gender" label="Gender" :items="['Female', 'Male', 'Others']"></v-select>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="formData.email" label="Email"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="formData.address" label="Address"></v-text-field>
              </v-col>
              <!-- <v-col cols="12">
                <v-text-field v-model="formData.username" label="Username"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="formData.password" label="Password" type="password"></v-text-field>
              </v-col> -->
              <v-card-actions>
                <v-col cols="6">
                  <v-btn type="submit" color="success">Update</v-btn>
                </v-col>
                <v-col cols="6">
                  <v-btn color="error" @click="editMode = false">Cancel</v-btn>
                </v-col>
              </v-card-actions>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  computed: {
    ...mapGetters(['USER_DETAILS']),
    maskedPassword() {
      return '*******';
    }
  },
  data() {
    return {
      editMode: false,
      formData: {
        first_name: '',
        middle_name: '',
        last_name: '',
        suffix: '',
        gender: '',
        email: '',
        address: '',
        profile_pic: null,
        username: '',
        password: '',
      }
    }
  },
  methods: {
    ...mapActions(['UpdateUserDetails']),
    updateUser() {
      const forms = {
        first_name: this.formData.first_name,
        middle_name: this.formData.middle_name,
        last_name: this.formData.last_name,
        suffix: this.formData.suffix,
        gender: this.formData.gender,
        email: this.formData.email,
        address: this.formData.address,
        username: this.formData.username,
        password: this.formData.password,
      };
      this.$store.dispatch('UpdateUserDetails', forms).then(() => {
        this.$swal.fire({
          title: "Update Success",
          text: "User details updated successfully",
          icon: "success",
        });
        this.editMode = false;
      });
    },
  },
};
</script>
