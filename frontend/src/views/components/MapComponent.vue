<template>
  <v-container>
    <l-map ref="mapRef" :zoom="zoom" :center="center" style="height: 500px;" @click="MapClick" :options="mapOptions">
      <!-- <l-tile-layer url='https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png'></l-tile-layer> -->
      <l-tile-layer :url="googleStreets.url" :maxZoom="googleStreets.maxZoom"
        :subdomains="googleStreets.subdomains"></l-tile-layer>
      <l-marker v-if="MARKER_LAT_LNG != null" :latLng="MARKER_LAT_LNG" :icon="customIcon"
        :draggable="ParentViewer == 'User'" @dragend="onMarkerDragEnd"></l-marker>
      <l-circle v-if="MARKER_LAT_LNG != null" :lat-lng="MARKER_LAT_LNG" :radius="radius" :color="circleColor"></l-circle>
      <l-marker v-if="ParentViewer == 'User'" v-for="(item, index) in REPORTS" :key="item" @click="MarkerClick(item)"
        :lat-lng="{ lat: item.latitude, lng: item.longitude }" :icon="customIcon2(item)">
        <l-popup style="height: 400px; width: 300px;">
          <div v-if="SELECTED_REPORT != null">
            <v-container>
              <v-row>
                <v-col cols="2">
                  <Icon :data="SELECTED_REPORT.icon" :color="SELECTED_REPORT.color" size="36px"></Icon>
                </v-col>
                <v-col>
                  <h3 :style="{ color: SELECTED_REPORT.color, 'font-size': '28px' }">{{ SELECTED_REPORT.name }}</h3>
                </v-col>
              </v-row>
              <p>{{ SELECTED_REPORT.description }}</p>
              <v-icon>mdi-map-marker</v-icon>
              {{ SELECTED_REPORT.address }}
              <v-card class="mt-2" style="height: 200px; width: 280px; bottom: 1;">
                <v-carousel v-if="SELECTED_REPORT_PICS.length > 0" style="height: 200px; width: 280px;"
                  hide-delimiter-background>
                  <v-carousel-item v-for="(item, index) in SELECTED_REPORT_PICS" :key="index" :src="item.base64img"
                    cover></v-carousel-item>
                </v-carousel>
              </v-card>
            </v-container>
          </div>
        </l-popup>
      </l-marker>
    </l-map>
  </v-container>
</template>
    
<script>
import { defineComponent } from 'vue';
import { LCircle, LMap, LTileLayer, LMarker, LIcon, LTooltip, LPopup } from "@vue-leaflet/vue-leaflet";
import "leaflet/dist/leaflet.css";
import L from 'leaflet';
import { Icon } from 'vue3-icon-picker'
import iconList from '@/assets/iconList.json'
import { mapGetters } from "vuex";

export default defineComponent({
  components: {
    LCircle,
    LMap,
    LTileLayer,
    LMarker,
    Icon,
    LTooltip, LPopup
  },


  props: {
    ParentViewer: String
  },

  data() {
    return {
      radius: 10, // radius in meters
      circleColor: 'blue',
      markerLatLng: [14.654167279098882, 120.96567392349245],
      center: [14.654167279098882, 120.96567392349245],
      zoom: 16,
      icon: null,
      iconsList: {
        value: iconList // Initialize as an empty array
      },
      mapOptions: {
        zoomControl: false
      },
      googleStreets: {
        url: 'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
      },
    };
  },

  computed: {
    ...mapGetters(["CENTER", "ICON", "MARKER_LAT_LNG", "REPORTS", "IS_ADDING_REPORT", "SELECTED_REPORT", "SELECTED_REPORT_PICS"]),
    customIcon() {
      if (this.icon != null) {
        const iconUrl = `data:image/svg+xml;charset=utf-8,${encodeURIComponent(this.icon.svgCode)}`;
        return new L.Icon({
          iconUrl,
          iconSize: [38, 38], // Size of the icon
          iconAnchor: [12, 24], // Anchor point of the icon
          popupAnchor: [0, -24] // Popup anchor point
        });
      }
    },

  },

  watch: {
    ICON(val) {
      // console.log(val)
      if (val == null) {
        this.icon = null
      }
      else {
        this.icon = this.findIconByName(val.icon);
        this.icon.svgCode = this.changeSvgColor(this.icon.svgCode.toString(), val.color)
      }
    },
    async CENTER(val) {
      // console.log(val)
      const latitude = parseFloat(val[0]);
      const longitude = parseFloat(val[1]);
      this.$refs.mapRef.leafletObject.setView([latitude, longitude], 18);
    },

    MARKER_LAT_LNG(val) {
      this.checkRadius()
    }
  },

  methods: {
    async MarkerClick(item) {
      // this.$store.commit('CENTER', [item.latitude, item.longitude])
      this.$store.commit('SELECTED_REPORT_PICS', [])
      this.$store.commit('SELECTED_REPORT', item)
      const payload = {
        params: {
          report_id: item.id
        }
      }
      await this.$store.dispatch('GetReportPics', payload).then((response) => {
        // console.log(this.SELECTED_REPORT_PICS)
      })
    },
    customIcon2(item) {
      const svg = this.findIconByName(item.icon)
      const coloredSvg = this.changeSvgColor(svg.svgCode.toString(), item.color)
      const iconUrl = `data:image/svg+xml;charset=utf-8,${encodeURIComponent(coloredSvg)}`;
      return new L.Icon({
        iconUrl,
        iconSize: [38, 38], // Size of the icon
        iconAnchor: [12, 24], // Anchor point of the icon
        popupAnchor: [0, -24] // Popup anchor point
      });
    },

    InitWebsocketsPusher() {
      const pusher = window.Pusher;
      const channel = pusher.subscribe('channel-ReportEvent');
      channel.bind('ReportEvent', (response) => {
        // console.log(response)
        if (response.Recipient == 'All') {
          this.GetAllActiveReport()
        }
      });
    },

    MapClick(event) {
      if (this.IS_ADDING_REPORT) {
        this.center = [event.latlng.lat, event.latlng.lng];
        const { lat, lng } = event.latlng;
        this.$store.commit("MARKER_LAT_LNG", [lat, lng])
      }
    },

    changeSvgColor(svg, newColor) {
      return svg.replace(/fill="([^"]*)"/g, `fill="${newColor}"`);
    },

    onMarkerDragEnd(event) {
      // console.log(markerLatLng)
      const markerLatLng = event.target.getLatLng();
      this.$store.commit('MARKER_LAT_LNG', [markerLatLng.lat, markerLatLng.lng])
    },

    findIconByName(name) {
      return this.iconsList.value.find(icon => icon.name == name);
    },

    GetAllActiveReport() {
      this.$store.dispatch('GetAllActiveReports').then((response) => {
        // console.log(this.REPORTS)
        this.checkRadius()
      })
    },

    checkRadius() {
      if (this.MARKER_LAT_LNG != null) {
        const latitude = this.MARKER_LAT_LNG[0];
        const longitude = this.MARKER_LAT_LNG[1];
        //sample val [{latitude:0, longitude:0} ...]
        const reportsLatLng = this.REPORTS.map(report => ({
          ...report,
          latitude: parseFloat(report.latitude),
          longitude: parseFloat(report.longitude)
        }));
        const reportsWithinRadius = reportsLatLng.filter(report => this.isWithinRadius(report, { lat: latitude, lng: longitude }));
        this.$store.commit('NEAR_BY_HAZARDS', reportsWithinRadius)
      }
    },

    isWithinRadius(report, markerLatLng) {
      if (!report.latitude || !report.longitude) {
        return false;
      }
      const R = 6371e3; // Earth's radius in meters
      const φ1 = markerLatLng.lat * Math.PI / 180; // φ, λ in radians
      const φ2 = report.latitude * Math.PI / 180;
      const Δφ = (report.latitude - markerLatLng.lat) * Math.PI / 180;
      const Δλ = (report.longitude - markerLatLng.lng) * Math.PI / 180;

      const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) *
        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

      const d = R * c; // distance in meters
      return d <= this.radius;
    }

  },

  mounted() {
    if (this.ParentViewer == 'User') {
      this.GetAllActiveReport()
      this.InitWebsocketsPusher()
    }
    else if (this.ParentViewer == 'ReportDetailsDialog') {

    }
  }
});
</script>
    
<style>
.leaflet-control-attribution.leaflet-control {
  display: none;
}
</style>