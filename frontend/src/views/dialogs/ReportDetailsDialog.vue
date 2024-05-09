<template>
    <v-dialog v-model="dialog" persistent>
        <v-container>
            <v-card>
                <v-row>
                    <v-col cols="6" style=" transform: translate(3%,5%);">
                        <v-row style=" transform: translate(5%,5%);">
                            <v-col cols="1">
                                <Icon :data="SELECTED_REPORT.icon" :color="SELECTED_REPORT.color" size="36px"></Icon>
                            </v-col>
                            <v-col>
                                <h3 :style="{ color: SELECTED_REPORT.color, 'font-size': '28px' }">{{
                                    SELECTED_REPORT.name }}
                                </h3>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" style=" transform: translate(5%,5%);">
                                <p>{{ SELECTED_REPORT.description }}</p>
                            </v-col>
                            <v-col cols="12" style=" transform: translate(3.5%,5%);">
                                <v-icon>mdi-map-marker</v-icon>
                                {{ SELECTED_REPORT.address }}
                            </v-col>
                        </v-row>
                        <v-carousel v-if="SELECTED_REPORT_PICS.length > 0" hide-delimiter-background
                            style="height: 300px; transform: translate(0%,20%);">
                            <v-carousel-item v-for="(item, index) in SELECTED_REPORT_PICS" :key="index" height="300px"
                                :src="item.base64img"></v-carousel-item>
                        </v-carousel>
                    </v-col>
                    <v-col cols="6">
                        <MapComponent :ParentViewer="'ReportDetailsDialog'" />
                    </v-col>
                </v-row>
                <v-row style="transform: translate(70%,-10%);">
                    <v-col cols="11">
                        <template v-if="!SELECTED_REPORT.isLoading">
                            <v-btn @click="approve()">Approve</v-btn>
                            <v-btn @click="disapprove()">Disapprove</v-btn>
                            <v-btn @click="closeModal">Close</v-btn>
                        </template>
                        <template v-else>
                            <v-btn loading></v-btn>
                        </template>
                    </v-col>
                </v-row>
            </v-card>
        </v-container>
    </v-dialog>
</template>
<script lang="ts" setup>
import MapComponent from "@/views/components/MapComponent.vue"
import { computed, ref, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { Icon } from 'vue3-icon-picker'
import Swal from 'sweetalert2';

const store = useStore();
const dialog = ref(true);
const SELECTED_REPORT = computed(() => store.getters.SELECTED_REPORT);
const SELECTED_REPORT_PICS = computed(() => store.getters.SELECTED_REPORT_PICS);
const REPORTS = computed(() => store.getters.REPORTS);
const MARKER_LAT_LNG = computed(() => store.getters.MARKER_LAT_LNG);


const closeModal = () => {
    dialog.value = false;
    store.commit('SELECTED_REPORT', null)
    store.commit('SELECTED_REPORT_PICS', [])
}

const approve = () => {
    const item = SELECTED_REPORT.value
    SELECTED_REPORT.value.isLoading = true
    const payload = { report_id: item.id, status: 'active' }
    store.dispatch('UpdateReportStatus', payload).then((response) => {
        if (response == 'success') {
            SELECTED_REPORT.value.isLoading = false
            closeModal()
            Swal.fire({
                title: "Success!",
                text: "Report approve successfully!",
                icon: "success",
            });
        }
    })
}

const disapprove = () => {
    const item = SELECTED_REPORT.value
    SELECTED_REPORT.value.isLoading = true
    const payload = { report_id: item.id, status: 'disapproved' }
    store.dispatch('UpdateReportStatus', payload).then((response) => {
        if (response == 'success') {
            SELECTED_REPORT.value.isLoading = false
            closeModal()
            Swal.fire({
                title: "Success!",
                text: "Report approve successfully!",
                icon: "success",
            });
        }
    })
}

onMounted(async () => {
    // console.log(SELECTED_REPORT.value)
    store.commit('MARKER_LAT_LNG', [SELECTED_REPORT.value.latitude, SELECTED_REPORT.value.longitude])
    store.commit('ICON', null)
    await new Promise((resolve) => setTimeout(resolve, 200));
    store.commit('ICON', SELECTED_REPORT.value)
    store.commit('CENTER', MARKER_LAT_LNG.value)
    const payload = {
        params: {
            report_id: SELECTED_REPORT.value.id
        }
    }
    store.dispatch('GetReportPics', payload).then((response) => {
        // console.log(SELECTED_REPORT_PICS.value)
    })

})



</script>                                  