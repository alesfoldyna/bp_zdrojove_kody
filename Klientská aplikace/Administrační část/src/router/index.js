import Vue from 'vue'
import Router from 'vue-router'
import AdminHome from '@/components/AdminHome'
import Login from '@/components/Login'
import addNpUser from '@/components/addNpUser'
import resetPass from '@/components/resetPass'
import addAdvertising from '@/components/addAdvertising'
import advList from '@/components/advList'
import advDetails from '@/components/advDetails'
import EditAdvertising from '@/components/EditAdvertising'
import editNpUser from '@/components/editNpUser'
import usersList from '@/components/usersList'
import userDetail from '@/components/userDetail'
import addUser from '@/components/addUsers'
import editUser from '@/components/editUsers'
import advForCheck from '@/components/advForCheck'
import gearManagement from '@/components/gearManagement'
import carBrandManagement from '@/components/carBrandManagement'
import modelManagementList from '@/components/modelManagementList'
import modelManagementDetails from '@/components/modelManagementDetails'
import puMyInvoice from '@/components/puMyInvoice'
import puMyContracts from '@/components/puMyContracts'
import conditionsManagementList from '@/components/conditionsManagementList'
import conditionsManagementDetails from '@/components/conditionsManagementDetails'
import conditionsManagementAdd from '@/components/conditionsManagementAdd'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'AdminHome',
      component: AdminHome
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/addNpUser',
      name: 'addNpUser',
      component: addNpUser
    },
    {
      path: '/resetPass/:id/token/:token',
      name: 'resetPass',
      component: resetPass
    },
    {
      path: '/addAdvertising',
      name: 'addAdvertising',
      component: addAdvertising
    },
    {
      path: '/advList',
      name: 'advList',
      component: advList
    },
    {
      path: '/advDetails/:id/user/:id_user',
      name: 'advDetails',
      component: advDetails
    },
    {
      path: '/EditAdvertising/:id_adver',
      name: 'EditAdvertising',
      component: EditAdvertising
    },
    {
      path: '/editNpUser',
      name: 'editNpUser',
      component: editNpUser
    },
    {
      path: '/usersList',
      name: 'usersList',
      component: usersList
    },
    {
      path: '/userDetail/:id_user',
      name: 'userDetail',
      component: userDetail
    },
    {
      path: '/addUser',
      name: 'addUser',
      component: addUser
    },
    {
      path: '/editUser/:id_user',
      name: 'editUser',
      component: editUser
    },
    {
      path: '/advForCheck',
      name: 'advForCheck',
      component: advForCheck
    },
    {
      path: '/gearManagement',
      name: 'gearManagement',
      component: gearManagement
    },
    {
      path: '/carBrandManagement',
      name: 'carBrandManagement',
      component: carBrandManagement
    },
    {
      path: '/modelManagementList',
      name: 'modelManagementList',
      component: modelManagementList
    },
    {
      path: '/modelManagementDetails',
      name: 'modelManagementDetails',
      component: modelManagementDetails
    },
    {
      path: '/puMyInvoice',
      name: 'puMyInvoice',
      component: puMyInvoice
    },
    {
      path: '/puMyContracts',
      name: 'puMyContracts',
      component: puMyContracts
    },
    {
      path: '/conditionsManagementList',
      name: 'conditionsManagementList',
      component: conditionsManagementList
    },
    {
      path: '/conditionsManagementDetails/:id_podminka',
      name: 'conditionsManagementDetails',
      component: conditionsManagementDetails
    },
    {
      path: '/conditionsManagementAdd',
      name: 'conditionsManagementAdd',
      component: conditionsManagementAdd
    },
  ]
})
