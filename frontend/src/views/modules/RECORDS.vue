<template>
    <v-app>
        <v-container>
            <v-row>
                <h1>Records</h1>
                <v-spacer></v-spacer v-if="USER_DETAILS.user_role_desc == 'admin'">
                <v-btn color="success" elevation="1" v-if="USER_DETAILS.user_role_desc == 'admin'"
                    @click="generateExcel">
                    <v-icon icon="mdi-microsoft-excel"></v-icon>
                    Generate EXCEL</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="red" elevation="1" @click="generatePDF">
                    <v-icon icon="mdi-file-pdf-box"></v-icon>
                    Generate PDF</v-btn>
            </v-row>
            <v-row>
                <v-data-table-server class="elevation-1" :headers="headers" v-model:items-per-page="itemsPerPage"
                    :items="GET_ATTLOGS.data || []" :items-length="GET_ATTLOGS.total || total" :loading="load"
                    @update:options="fetchRecords" item-value="scanned">
                    <template v-slot:[`item.scanned_by`]="{ item }">
                        {{ item.scanned_by.charAt(0).toUpperCase() + item.scanned_by.slice(1).toLowerCase() }}
                    </template>
                    <template v-slot:[`item.status`]="{ item }">
                        <v-chip :color="item.time_in > GET_IN ? 'red' : 'green'">
                            {{ item.time_in > GET_IN ? 'Late' : 'On-Time' }}
                        </v-chip>
                    </template>

                    <template v-slot:thead
                        v-if="USER_DETAILS.user_role_desc == 'admin' || USER_DETAILS.user_role_desc == 'student'">
                        <v-text-field v-model="scanned" variant="underlined" prepend-icon="mdi-magnify" class="ma-2"
                            density="compact" @change="fetchRecords" placeholder="Filter by Office..."
                            hide-details></v-text-field>
                    </template>
                </v-data-table-server>
            </v-row>
        </v-container>
    </v-app>
</template>


<script>
import { mapGetters } from "vuex";
import moment from 'moment';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';

export default {
    data() {
        return {
            headers: [
                { title: "Name", sortable: false, key: "name" },
                { title: "Date", sortable: false, key: "date_in" },
                { title: "Time In", sortable: false, key: "time_in" },
                { title: "Scanned At", sortable: false, key: "scanned_by" },
                { title: "Status", sortable: false, value: 'status' },
            ],
            total: 0,
            itemsPerPage: 5,
            load: false,
            scanned: '',
        };
    },
    computed: {
        ...mapGetters(["GET_ATTLOGS", "GET_IN", "GET_OUT", "USER_DETAILS"]),
    },
    methods: {
        fetchRecords(page) {
            this.load = true;
            const payload = {
                itemsPerPage: page.itemsPerPage,
                page: page.page,
                search: this.scanned,
            };
            this.$store.dispatch('getAttendance', payload).then((response) => {
                this.load = false;
            });
        },

        fetchSchedule() {
            this.$store.dispatch('getSchedule');
        },

        generatePDF() {
            const doc = new jsPDF();

            doc.setFontSize(12);

            doc.setFont("helvetica", "bold");
            doc.text("Attendance Records", 15, 15);

            let y = 25;

            const headers = ["No.", "Name", "Date", "Scanned At", "Time In", "Status"];

            const tableData = this.GET_ATTLOGS.data.map((item, index) => {
                const formattedDate = new Date(item.date_in).toLocaleDateString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                return [
                    (index + 1).toString(), // No. column
                    item.name,              // Name column
                    formattedDate,          // Date column
                    item.scanned_by,
                    item.time_in.toString(),// Time In column
                    item.time_in > this.GET_IN ? 'Late' : 'On-Time' // Status column
                ];
            });

            doc.autoTable({
                head: [headers],
                body: tableData,
                startY: y + 5,
                margin: { top: 20 },
                styles: {
                    fontSize: 10,
                    cellPadding: 2,
                    valign: 'middle',
                    overflow: 'linebreak'
                },
                columnStyles: {
                    0: { cellWidth: 10 },
                }
            });

            doc.save("attendance_records.pdf");
        },

        generateExcel() {
            const processedData = this.GET_ATTLOGS.data.map(item => {
                const status = item.time_in > this.GET_IN ? 'Late' : 'On-Time';
                return {
                    ...item,
                    status: status
                };
            });

            const ws = XLSX.utils.json_to_sheet(processedData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Attendance Logs');

            XLSX.writeFile(wb, 'AttendanceLogs.xlsx');
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