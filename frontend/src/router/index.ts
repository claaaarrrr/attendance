// Composables
import { createRouter, createWebHistory } from "vue-router";
import TABSPAGE from "@/views/components/TabsPage.vue";
import SCHEDULE from "@/views/modules/SCHEDULE.vue";
import RECORDS from "@/views/modules/RECORDS.vue";
import ACCOUNTS from "@/views/modules/ACCOUNTS.vue";
import LOGIN from "@/views/modules/LOGIN.vue";
import QRCODE from "@/views/modules/QRCODE.vue";
import SCANNER from "@/views/modules/SCANNER.vue";
import USERS from "@/views/modules/USERS.vue";
import { useStore } from "vuex";
import { error } from "console";

const routes = [
  {
    path: "/",
    redirect: "/LOGIN",
  },
  {
    path: "/LOGIN",
    name: "/LOGIN",
    component: LOGIN,
  },
  {
    path: "/TABSPAGE",
    component: TABSPAGE,
    children: [
      {
        path: "",
        redirect: { name: "RECORDS" },
      },
      {
        path: "SCHEDULE",
        name: "SCHEDULE",
        component: SCHEDULE,
        meta: { role: ["0", "2"] },
      },
      {
        path: "RECORDS",
        name: "RECORDS",
        component: RECORDS,
        meta: { role: ["0", "1", "2"] },
      },
      {
        path: "ACCOUNTS",
        name: "ACCOUNTS",
        component: ACCOUNTS,
        meta: { role: ["1"] },
      },
      {
        path: "QRCODE",
        name: "QRCODE",
        component: QRCODE,
        meta: { role: ["1"] },
      },
      {
        path: "SCANNER",
        name: "SCANNER",
        component: SCANNER,
        meta: { role: ["0", "2"] },
      },
      {
        path: "USERS",
        name: "USERS",
        component: USERS,
        meta: { role: ["0"] },
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const store = useStore();
  const tabs = [
    [
      { label: "USERS", route: "/TABSPAGE/USERS" },
      { label: "RECORDS", route: "/TABSPAGE/RECORDS" },
      { label: "SCHEDULE", route: "/TABSPAGE/SCHEDULE" },
      { label: "SCANNER", route: "/TABSPAGE/SCANNER" },
    ],
    [
      { label: "ACCOUNT", route: "/TABSPAGE/ACCOUNTS" },
      { label: "RECORDS", route: "/TABSPAGE/RECORDS" },
      { label: "QR CODE", route: "/TABSPAGE/QRCODE" },
    ],
    [
      { label: "RECORDS", route: "/TABSPAGE/RECORDS" },
      { label: "SCHEDULE", route: "/TABSPAGE/SCHEDULE" },
      { label: "SCANNER", route: "/TABSPAGE/SCANNER" },
    ],
  ];

  if (to.name == "/LOGIN") {
    store
      .dispatch("GetUserDetails")
      .then((response) => {
        next(`${tabs[response.user_role][0].route}`);
      })
      .catch((error) => {
        next();
      });
  } else {
    store
      .dispatch("GetUserDetails")
      .then((response) => {
        if ((to.meta.role as any).includes(response.user_role.toString())) {
          next();
        } else {
          next(`${tabs[response.user_role][0].route}`);
        }
      })
      .catch((error) => {
        next("/LOGIN");
      });
  }
});

export default router;
