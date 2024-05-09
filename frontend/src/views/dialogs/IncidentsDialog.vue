<template>
    <v-dialog v-model="dialog" persistent width="900" height="600" z-index="0">
        <v-container>
            <v-card height="600">
                <v-card-title>
                    <v-icon color="gray" class="float-right" @click="closeModal">mdi-close-circle</v-icon>
                    <span class="headline">Incidents Icons</span>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="6">
                            <center>
                                <Icon :data="icon" :size="120" :color="iconColor"></Icon>
                            </center>
                            <Vue3IconPicker v-model="icon" placeholder="Select icon" iconLibrary="all" valueType="name"
                                selectedIconColor="iconColor" selectedIconBgColor="iconBgColor"
                                :disabled="INCIDENT_OPERATION == 'View'" />
                        </v-col>
                        <v-col cols="6">
                            <v-row>
                                <v-col cols="12">
                                    <v-color-picker v-model="iconColor" :show-swatches="INCIDENT_OPERATION != 'View'"
                                        width="370" mode="hex" :disabled="INCIDENT_OPERATION == 'View'"></v-color-picker>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="4">
                                    <v-autocomplete v-model="status" label="Status"
                                        :items="['Enable', 'Disabled']"></v-autocomplete> </v-col>
                                <v-col cols="4">
                                    <v-text-field v-model="name" label="Incident" placeholder="Enter Incident" outlined
                                        dense hide-details="auto">
                                    </v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-btn class="mt-4" @click="submit" :loading="loading" v-if="INCIDENT_OPERATION != 'View'">{{
                                        INCIDENT_OPERATION }}</v-btn>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </v-dialog>
</template>

<script lang="ts" setup>
import { watch, computed, ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { Icon } from 'vue3-icon-picker'
import Swal from 'sweetalert2';

const store = useStore();
const dialog = ref(true);
const SELECTED_INCIDENT = computed(() => store.getters.SELECTED_INCIDENT);
const INCIDENT_OPERATION = computed(() => store.getters.INCIDENT_OPERATION);
const name = ref('')
const status = ref('')
const icon = ref('')
const loading = ref(false)
const iconColor = ref('#000000')


if (INCIDENT_OPERATION.value === 'Update' || INCIDENT_OPERATION.value === 'View') {
    name.value = SELECTED_INCIDENT.value.name
    icon.value = SELECTED_INCIDENT.value.icon
    status.value = SELECTED_INCIDENT.value.status
    iconColor.value = SELECTED_INCIDENT.value.color
}

const submit = () => {
    loading.value = true
    if (INCIDENT_OPERATION.value === 'Update') {
        const payload = {
            incident_id: SELECTED_INCIDENT.value.id,
            name: name.value,
            status: status.value,
            color: iconColor.value,
            icon: icon.value,
        }
        store.dispatch('UpdateIncidents', payload).then((response: any) => {
            console.log(response)
            if (response === "success") {
                closeModal()
                Swal.fire({
                    title: "Success!",
                    text: "Update successfully!",
                    icon: "success",
                });
            } else {
                Swal.fire({
                    title: "Validation Error",
                    text: response,
                    icon: "warning",
                });
            }
        })
    }
    else if (INCIDENT_OPERATION.value === 'Add') {
        const payload = {
            name: name.value,
            color: iconColor.value,
            icon: icon.value,
        }
        console.log(payload)
        store.dispatch('AddIncident', payload).then((response: any) => {
            console.log(response)
            if (response === "success") {
                closeModal()
                Swal.fire({
                    title: "Success!",
                    text: "Update successfully!",
                    icon: "success",
                });
            } else {
                Swal.fire({
                    title: "Validation Error",
                    text: response,
                    icon: "warning",
                });
            }
        })
    }
    loading.value = false
}

const closeModal = () => {
    dialog.value = false;
    store.commit('SELECTED_INCIDENT', null);
    store.commit('INCIDENT_OPERATION', null);
};

</script>

