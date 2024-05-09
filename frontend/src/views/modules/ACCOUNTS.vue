<template>
  <v-container>
    <v-card class="user-info-container" elevation="10">
      <v-card-title>User Information</v-card-title>
      <v-card-text>
        <v-row>
          <v-col>
            <img :src="USER_DETAILS.base64img" alt="Profile Picture" width="150" class="mr-2 profile-picture" />
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Name:</strong> {{ USER_DETAILS.name }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Gender:</strong> {{ USER_DETAILS.gender }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Email:</strong> {{ USER_DETAILS.email }}
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <strong>Address:</strong> {{ USER_DETAILS.address }}
          </v-col>
        </v-row>
        <!-- Edit Button -->
        <v-row>
          <v-col>
            <v-btn color="primary" @click="editMode = true; formData = { ...USER_DETAILS }">Edit</v-btn>
          </v-col>
        </v-row>
        <!-- End of Edit Button -->
      </v-card-text>
    </v-card>

    <!-- Edit Profile Dialog -->
    <v-dialog v-model="editMode" max-width="500px">
      <v-card>
        <v-card-title>Edit Profile</v-card-title>
        <v-card-text>
          <v-row>
            <v-col>
              <img :src="USER_DETAILS.base64img" alt="Profile Picture" width="150" class="mr-2 profile-picture" />
              <v-file-input v-model="formData.profile_pic" label="Profile Picture"></v-file-input>
            </v-col>
          </v-row>
          <v-form @submit.prevent="updateUser()">
            <v-row>
              <v-col>
                <v-text-field v-model="formData.first_name" label="First Name"></v-text-field>
              </v-col>
              <v-col>
                <v-text-field v-model="formData.middle_name" label="Middle Name"></v-text-field>
              </v-col>
              <v-col>
                <v-text-field v-model="formData.last_name" label="Last Name"></v-text-field>
              </v-col>
            </v-row>
            <v-text-field v-model="formData.gender" label="Gender"></v-text-field>
            <v-text-field v-model="formData.email" label="Email"></v-text-field>
            <v-text-field v-model="formData.address" label="Address"></v-text-field>

            <v-row>
              <v-btn type="submit" color="success">Update</v-btn>
              <v-spacer></v-spacer>
              <v-btn color="error" @click="editMode = false">Cancel</v-btn>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- End of Edit Profile Dialog -->
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters(['USER_DETAILS', 'GET_NUMBER']),
  },
  data() {
    return {
      editMode: false,
      formData: {
        first_name: '',
        middle_name: '',
        last_name: '',
        gender: '',
        email: '',
        address: '',
        profile_pic: null
      }
    }
  },
  methods: {
    updateUser() {
      const forms = {
        first_name: this.formData.first_name,
        middle_name: this.formData.middle_name,
        last_name: this.formData.last_name,
        gender: this.formData.gender,
        email: this.formData.email,
        address: this.formData.address,
      };
      console.log(forms);
      this.$store.dispatch('UpdateUserDetails', forms).then(() => {
        this.$swal.fire({
          title: "Update Success",
          text: "Account changes has been made!",
          icon: "success",
        });
        this.editMode = false;
      });
    },
  },
};
</script>
