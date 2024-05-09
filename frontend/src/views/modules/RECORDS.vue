<template>
    <v-app>
        <v-container>
            <v-row>
                <h1>Records</h1>
                <v-spacer></v-spacer>
                <v-btn color="red" elevation="1">
                    <v-icon icon="mdi-file-pdf-box"></v-icon>
                    Generate PDF</v-btn>
            </v-row>
            <v-row>
                <v-data-table class="elevation-1" :headers="headers" :items-per-page="10">
                </v-data-table>
            </v-row>
        </v-container>
    </v-app>
</template>


<script>

import { mapGetters } from "vuex";
import moment from 'moment';

export default {
    data() {
        return {
            headers: [
                { title: "Name", sortable: false },
                { title: "Time In", sortable: false },
            ]
        };
    },
    computed: {
        ...mapGetters(["", "", ""]),
    },
    methods: {




    },
    created() {

    },
};
</script>

<style scoped>
/* Add your styling if needed */
.spacer {
    flex: 1;
}
</style>