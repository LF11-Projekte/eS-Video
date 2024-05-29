import { createRouter, createWebHistory } from 'vue-router'
import MainView from '@/views/MainView.vue'
import LoginView from '@/views/LoginView.vue'
import ProfileView from '@/views/ProfileView.vue'
import LoginConfirm from "@/views/LoginConfirm.vue";
import EditProfileView from "@/views/EditProfileView.vue";
import {AppState} from "@/state";
import UploadView from "@/views/UploadView.vue";
import VideoView from "@/views/VideoView.vue";
import ClassView from "@/views/ClassView.vue";
import SearchView from "@/views/SearchView.vue";

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
      name: 'LoginView',
      path: '/login',
      component: LoginView
    },
    {
      name: 'LoginWorker',
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
    },
    {
      path: '/upload',
      component: UploadView
    },
    {
      path: '/video/:key',
      component: VideoView
    },
    {
      path: '/class/:cid',
      component: ClassView
    },
    {
      path: '/search/:search',
      component: SearchView
    }
  ]
});

router.beforeEach((to, from, next) => {

  // Wenn Nutzer nicht angemeldet -> nur /login erlauben
  if (!AppState.StateObj.Usr_LoggedIn && to.name !== 'LoginView' && to.name !== 'LoginWorker') {
    return next({path: '/login'});
  }

  // Wenn Nutzer angemeldet -> nicht /login erlauben
  if (AppState.StateObj.Usr_LoggedIn && (to.name == 'LoginView' || to.name == 'LoginWorker')) {
     return next({path: '/home'});
  }

  return next();
});

export default router
