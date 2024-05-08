<template>
  <v-app>
    <h1>QR CODE</h1>
    <qrcode-vue
      :value="this.USER_DETAILS.hashed_user_id"
      :size="300"
      level="H"
    />
  </v-app>
</template>
  
<script>
import { mapGetters } from "vuex";
import moment from "moment";
import QrcodeVue from "qrcode.vue";

export default {
  data() {
    return {};
  },
  computed: {
    ...mapGetters(["USER_DETAILS", "", ""]),
  },
  components: {
    QrcodeVue,
  },
  created() {
    console.log(this.USER_DETAILS);
  },
};
</script>
  
<style scoped>
/* Add your styling if needed */
</style>
  