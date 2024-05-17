<template>
  <v-app>
    <div :style="{
      height: '100%',
      width: '100%',
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'start',
      alignItems: 'center'
    }">
      <h1>QR CODE</h1>
      <qrcode-vue :value="this.USER_DETAILS.hashed_user_id" :size="300" level="H" />
    </div>
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
  mounted() {
  }
};
</script>

<style scoped>
/* Add your styling if needed */
</style>