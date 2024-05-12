<template>
  <v-container>
    <v-card class="user-info-container d-flex flex-column justify-center align-center" elevation="10">
      <v-card-title class="d-flex justify-center"><strong>User Information</strong></v-card-title>
      <v-card-text class="d-flex flex-column justify-center align-center">
        <v-row>
          <v-col class="d-flex justify-center">
            <v-avatar size="200" color="black" rounded="0">
              <img :src="USER_DETAILS.profile_pic_path" alt="Profile Picture" width="190" height="190"
                class="profile-picture" />
            </v-avatar>
          </v-col>
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

    <v-dialog v-model="editMode" max-width="500px" persistent>
      <v-card>
        <v-card-title>Edit Profile</v-card-title>
        <v-form @submit.prevent="updateUser()">
          <v-card-text>
            <v-row>
              <v-col class="d-flex justify-center">
                <!-- <img :src="USER_DETAILS.base64img" alt="Profile Picture" w idth="150" class="mr-2 profile-picture" /> -->
                <v-file-input v-model="formData.profile_pic" label="Profile Picture" accept="image/jpeg, image/png"
                  @change="handleFileChange"></v-file-input>
              </v-col>
            </v-row>
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
                <v-text-field v-model="formData.suffix" label="Suffix" append-inner-icon="mdi-close"
                  @click:append-inner="clearSuffix"></v-text-field>
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
          </v-card-text>
        </v-form>
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
    clearSuffix() {
      this.editMode = false;
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "This will clear the suffix. Are you sure you want to proceed?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, clear it!",
          cancelButtonText: "No, cancel!",
          reverseButtons: true
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.$store.dispatch('clearSuffix').then((response) => {
              if (response.status == 200) {
                this.$swal.fire({
                  text: "Suffix is now set to null.",
                  title: "Removed",
                  icon: "success"
                });
              }
            });
          } else {
            this.editMode = true;
          }
        });
    },
    updateUser() {
      const toPass = new FormData();

      toPass.append('first_name', this.formData.first_name);
      toPass.append('middle_name', this.formData.middle_name);
      toPass.append('last_name', this.formData.last_name);
      toPass.append('suffix', this.formData.suffix);
      toPass.append('gender', this.formData.gender);
      toPass.append('email', this.formData.email);
      if (this.formData.profile_pic) {
        toPass.append('profile_pic', this.formData.profile_pic);
      }
      toPass.append('address', this.formData.address);
      toPass.append('username', this.formData.username);

      this.$store.dispatch('UpdateUserDetails', toPass).then(() => {
        this.$swal.fire({
          title: "Update Success",
          text: "User details updated successfully",
          icon: "success",
        });
        this.editMode = false;
      });
    },

    handleFileChange(event) {
      this.formData.profile_pic = event.target.files[0];
    }
  },
};
</script>
