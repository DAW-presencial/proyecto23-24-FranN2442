
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Home.vue') },
      { path: 'login', component: () => import('pages/Login.vue') },
      { path: 'register', component: () => import('pages/Register.vue') },
      { path: 'restaurant', component: () => import('pages/Restaurant.vue') },
      { path: 'profile', component: () => import('pages/Profile.vue') },
      { path: 'send-mail', component: () => import('pages/SendMail.vue') },

    ]
  },
  {

    path: '/erp/',
    component: () => import('layouts/ErpLayout.vue'),
    children: [

      { path: 'restaurant-login', component: () => import('pages/RestaurantLogin.vue') },
      { path: 'employee-login', component: () => import('pages/EmployeeLogin.vue') },
      { path: 'restaurant-sales', component: () => import('pages/RestaurantSales.vue') },
      { path: 'restaurant-panel', component: () => import('pages/RestaurantPanel.vue') },

    ]
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  },
  {
    path: '/unauthorized',
    component: () => import('pages/ErrorUnauthorized.vue')
  }
]

export default routes
