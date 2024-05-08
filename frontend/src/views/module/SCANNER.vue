<!-- <template>
  <v-app>
    <h1>Scanner</h1>
    <qrcode-reader @decode="onDecode" @locate="onLocate"></qrcode-reader>
  </v-app>
</template>
  
<script>
import { mapGetters } from "vuex";
import moment from "moment";
import { QrcodeReader } from 'vue-qrcode-reader'

export default {
  data() {
    return {};
  },
  computed: {
    ...mapGetters(["", "", ""]),
  },
  methods: {
    onDecode(content) {
      console.log(content)
    },

    onLocate(points) {
      // ...
    },
    async onInit(promise) {
      // show loading indicator

      try {
        await promise;

        // successfully initialized
      } catch (error) {
        if (error.name === "NotAllowedError") {
          // user denied camera access permisson
        } else if (error.name === "NotFoundError") {
          // no suitable camera device installed
        } else if (error.name === "NotSupportedError") {
          // page is not served over HTTPS (or localhost)
        } else {
          // browser is probably lacking features (WebRTC, Canvas)
        }
      } finally {
        // hide loading indicator
      }
    },
  },

  components: {
    "qrcode-reader": QrcodeReader,
  },
  mounted() {
    this.onInit()
  },
};
</script>
  
<style scoped>
/* Add your styling if needed */
</style>
   -->