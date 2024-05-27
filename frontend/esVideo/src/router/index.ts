import { createRouter, createWebHistory } from 'vue-router'
import MainView from '@/views/MainView.vue'
import LoginView from '@/views/LoginView.vue'
import ProfileView from '@/views/ProfileView.vue'
import LoginConfirm from "@/views/LoginConfirm.vue";
import EditProfileView from "@/views/EditProfileView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/home',
    },
    {
      path: '/home',
      component: MainView
    },
    {
      path: '/login',
      component: LoginView
    },
    {
      path: '/login/:atp/confirm',
      component: LoginConfirm
    },
    {
      path: '/profile/:uid',
      component: ProfileView
    },
    {
      path: '/edit-profile',
      component: EditProfileView
    }
  ]
})

export default router
