<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
        <!-- Navbar - hanya tampil jika sudah authenticated -->
        <nav v-if="isAuthenticated && student" class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <RouterLink to="/student/dashboard" class="flex items-center gap-2 text-xl font-bold text-purple-600 hover:text-purple-700">
                        <span>🎨</span>
                        <span>Kinter</span>
                    </RouterLink>

                    <!-- Menu -->
                    <div class="flex gap-6 items-center">
                        <RouterLink to="/student/dashboard" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 text-sm font-medium transition">
                            Dashboard
                        </RouterLink>
                        <RouterLink to="/student/projects" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 text-sm font-medium transition">
                            Projects
                        </RouterLink>
                        <RouterLink to="/student/profile" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 text-sm font-medium transition">
                            Profile
                        </RouterLink>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <img :src="student.avatar_url" :alt="student.name" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ student.name }}</span>
                        </div>
                        <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-lg transition">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            <RouterView />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-gray-600 dark:text-gray-400 text-sm">
                    <p>&copy; 2026 Kinter Student Portal. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterView, RouterLink, useRouter } from 'vue-router'

const router = useRouter()
const student = ref(null)
const isAuthenticated = ref(false)

onMounted(() => {
    const token = localStorage.getItem('student_token')
    if (token) {
        isAuthenticated.value = true
        fetchStudentData()
    }
})

const fetchStudentData = async () => {
    try {
        const response = await fetch('/api/student/auth/me', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Accept': 'application/json'
            }
        })

        if (response.ok) {
            student.value = await response.json()
        } else {
            localStorage.removeItem('student_token')
            router.push('/student/login')
        }
    } catch (error) {
        console.error('Error fetching student data:', error)
    }
}

const logout = async () => {
    try {
        await fetch('/api/student/auth/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Accept': 'application/json'
            }
        })
    } catch (error) {
        console.error('Error during logout:', error)
    } finally {
        localStorage.removeItem('student_token')
        router.push('/student/login')
    }
}
</script>
