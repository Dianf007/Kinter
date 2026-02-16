import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import StudentLogin from './Pages/Student/Login.vue'
import StudentDashboard from './Pages/Student/Dashboard.vue'
import StudentProjects from './Pages/Student/Projects.vue'
import StudentProfile from './Pages/Student/Profile.vue'
import StudentLayout from './Layouts/StudentLayout.vue'

const routes = [
    {
        path: '/student',
        component: StudentLayout,
        children: [
            {
                path: 'login',
                name: 'student.login',
                component: StudentLogin,
                meta: { requiresAuth: false }
            },
            {
                path: 'dashboard',
                name: 'student.dashboard',
                component: StudentDashboard,
                meta: { requiresAuth: true }
            },
            {
                path: 'projects',
                name: 'student.projects',
                component: StudentProjects,
                meta: { requiresAuth: true }
            },
            {
                path: 'profile',
                name: 'student.profile',
                component: StudentProfile,
                meta: { requiresAuth: true }
            }
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/student/login'
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('student_token')
    const isAuthenticated = !!token

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'student.login' })
    } else if (to.name === 'student.login' && isAuthenticated) {
        next({ name: 'student.dashboard' })
    } else {
        next()
    }
})

const app = createApp(App)

app.use(router)
app.mount('#app')
