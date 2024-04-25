
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Home.vue') },
      { path: 'login', component: () => import('pages/Login.vue') },
      { path: 'register', component: () => import('pages/Register.vue') },
      { path: 'restaurant', component: () => import('pages/Restaurant.vue') }
    ]
  },
  {

    path: '/erp',
    component: () => import('layouts/ErpLayout.vue'),
    children: [

      { path: 'restaurant-login', component: () => import('src/pages/RestaurantLogin.vue') },
      { path: 'employee-login', component: () => import('src/pages/EmployeeLogin.vue') },
      { path: 'restaurant-sales', component: () => import('src/pages/RestaurantSales.vue') },


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
