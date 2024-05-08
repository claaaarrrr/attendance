import Vue from 'vue';
import VueRouter from 'vue-router';
import TABSPAGE from '@/views/component/TabsPage.vue';
import SCHEDULE from '@/views/module/SCHEDULE.vue';
import RECORDS from '@/views/module/RECORDS.vue';
import ACCOUNTS from '@/views/module/ACCOUNTS.vue';
// import VOTING from '@/views/module/VOTING.vue';
// import TEST from '@/views/module/TEST.vue';
import LOGIN from '@/views/module/LOGIN.vue';
import QRCODE from '@/views/module/QRCODE.vue';
import SCANNER from '@/views/module/SCANNER.vue';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        redirect: '/LOGIN'
    },
    {
        path: '/LOGIN',
        name: '/LOGIN',
        component: LOGIN,
    },
    {
        path: '/TABSPAGE',
        component: TABSPAGE,
        children: [
            {
                path: '',
                redirect: { name: 'RECORDS' }
            },
            {
                path: 'SCHEDULE',
                name: 'SCHEDULE',
                component: SCHEDULE,
            },
            {
                path: 'RECORDS',
                name: 'RECORDS',
                component: RECORDS,
            },
            {
                path: 'ACCOUNTS',
                name: 'ACCOUNTS',
                component: ACCOUNTS,
            },
            {
                path: 'QRCODE',
                name: 'QRCODE',
                component: QRCODE,
            },
            {
                path: 'SCANNER',
                name: 'SCANNER',
                component: SCANNER,
            },
        ]
    },
    // {
    //     path: '/TEST',
    //     name: '/TEST',
    //     component: TEST,
    // },

];

const router = new VueRouter({
    routes,
});

export default router;
