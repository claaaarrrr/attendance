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
                <v-data-table-server class="elevation-1" :headers="headers" v-model:items-per-page="itemsPerPage"
                    :items="GET_ATTLOGS.data || []" :items-length="GET_ATTLOGS.total || total" :loading="load"
                    @update:options="fetchRecords">
                    <template v-slot:[`item.status`]="{ item }">
                        <v-chip :color="item.time_in > GET_IN ? 'red' : 'green'">
                            {{ item.time_in > GET_IN ? 'Late' : 'On-Time' }}
                        </v-chip>
                    </template>
                </v-data-table-server>
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
                { title: "Name", sortable: false, key: "name" },
                { title: "Time In", sortable: false, key: "time_in" },
                { title: "Status", sortable: false, value: 'status' },
            ],
            total: 0,
            itemsPerPage: 5,
            load: false,
        };
    },
    computed: {
        ...mapGetters(["GET_ATTLOGS", "GET_IN", "GET_OUT"]),
    },
    methods: {
        fetchRecords(page) {
            this.load = true;
            const payload = {
                itemsPerPage: page.itemsPerPage,
                page: page.page,
            };
            this.$store.dispatch('getAttendance', payload).then((response) => {
                this.load = false;
            });
        },

        fetchSchedule() {
            this.$store.dispatch('getSchedule');
        }
    },

    watch: {
    },

    created() {
        this.fetchSchedule();
    },
};
</script>

<style scoped>
/* Add your styling if needed */
.spacer {
    flex: 1;
}
</style>