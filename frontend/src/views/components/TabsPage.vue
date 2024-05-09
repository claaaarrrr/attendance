<template>
  <v-app>
    <v-app-bar app dark>
      <v-tabs v-model="activeTab" color="primary" class="centered-tabs">
        <v-tab
          v-for="(item, index) in tabs[USER_DETAILS.user_role]"
          :key="index"
          :to="item.route"
        >
          {{ item.label }}
        </v-tab>
      </v-tabs>
      <v-spacer></v-spacer>
      <v-btn @click="submitLogout">Logout</v-btn>
    </v-app-bar>
    <v-main>
      <v-container>
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      activeTab: 1,
      tabs: [
        [
          { label: "USERS", route: "/TABSPAGE/USERS" },
          { label: "RECORDS", route: "/TABSPAGE/RECORDS" },
          { label: "SCHEDULE", route: "/TABSPAGE/SCHEDULE" },
          { label: "SCANNER", route: "/TABSPAGE/SCANNER" },
        ],
        [
          // Define tabs for role2 here
          { label: "ACCOUNT", route: "/TABSPAGE/ACCOUNTS" },
          { label: "RECORDS", route: "/TABSPAGE/RECORDS" },
          { label: "QR CODE", route: "/TABSPAGE/QRCODE" },
        ],
      ],
    };
  },
  methods: {
    submitLogout() {
      this.$store.dispatch("Logout").then((response) => {
        if (response["message"] === "success") {
          sessionStorage.removeItem("attendance-token");
          this.$router.push("/");
        }
      });
    },
  },
  computed: {
    ...mapGetters(["USER_DETAILS"]),
  },
  mounted() {
    this.$store.dispatch("GetUserDetails").then(() => {
      console.log(this.USER_DETAILS);
    });
  },
};
</script>

<style>
.centered-tabs {
  display: flex;
  justify-content: center;
  align-items: center;
}

.fixed-footer {
  position: fixed;
  bottom: 0;
  width: 100%;
}

body {
  overflow-x: hidden;
}
</style>
