<template>
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-white mb-8">
            <h1 class="text-4xl font-bold mb-2">Welcome, {{ student?.name }}! 👋</h1>
            <p class="text-purple-100 text-lg">Explore your projects and manage your profile</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- My Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">My Projects</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ myProjectsCount }}</p>
                    </div>
                    <div class="text-4xl">🎨</div>
                </div>
                <RouterLink to="/student/projects?tab=my" class="mt-4 inline-block text-purple-600 hover:text-purple-700 text-sm font-medium">
                    View Projects →
                </RouterLink>
            </div>

            <!-- Published Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Published</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ publishedCount }}</p>
                    </div>
                    <div class="text-4xl">✅</div>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-400 text-xs">
                    {{ (publishedCount / myProjectsCount * 100).toFixed(0) || 0 }}% of your projects are published
                </p>
            </div>

            <!-- Friends' Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Friends' Projects</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ friendsProjectsCount }}</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
                <RouterLink to="/student/projects?tab=friends" class="mt-4 inline-block text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Explore Projects →
                </RouterLink>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <RouterLink
                    to="/student/projects?tab=my"
                    class="flex items-center gap-4 p-4 border-2 border-purple-300 hover:border-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl transition"
                >
                    <div class="text-3xl">📁</div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">My Projects</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">View and manage your projects</p>
                    </div>
                </RouterLink>

                <RouterLink
                    to="/student/projects?tab=friends"
                    class="flex items-center gap-4 p-4 border-2 border-blue-300 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl transition"
                >
                    <div class="text-3xl">🌟</div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">Explore Projects</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Discover published projects</p>
                    </div>
                </RouterLink>

                <RouterLink
                    to="/student/profile"
                    class="flex items-center gap-4 p-4 border-2 border-green-300 hover:border-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-xl transition"
                >
                    <div class="text-3xl">👤</div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">Update Profile</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Edit your profile info</p>
                    </div>
                </RouterLink>
            </div>
        </div>

        <!-- Recent Projects Preview -->
        <div v-if="recentProjects.length > 0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Your Recent Projects</h2>
            <div class="space-y-4">
                <div v-for="project in recentProjects.slice(0, 3)" :key="project.id" class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ project.title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ truncate(project.description, 100) }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span v-if="project.is_published" class="inline-block bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold px-3 py-1 rounded-full">
                            Published
                        </span>
                        <span v-else class="inline-block bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-semibold px-3 py-1 rounded-full">
                            Private
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'

const student = ref(null)
const myProjectsCount = ref(0)
const publishedCount = ref(0)
const friendsProjectsCount = ref(0)
const recentProjects = ref([])

onMounted(() => {
    // Get student from localStorage
    const storedStudent = localStorage.getItem('student')
    if (storedStudent) {
        student.value = JSON.parse(storedStudent)
    }

    fetchProjects()
})

const fetchProjects = async () => {
    try {
        const response = await fetch('/api/student/projects', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Accept': 'application/json'
            }
        })

        if (response.ok) {
            const data = await response.json()
            const projects = data.projects

            // Count own projects and published status
            const ownProjects = projects.filter(p => p.is_owner)
            myProjectsCount.value = ownProjects.length
            publishedCount.value = ownProjects.filter(p => p.is_published).length
            friendsProjectsCount.value = projects.filter(p => !p.is_owner).length

            // Store recent projects
            recentProjects.value = ownProjects.slice(0, 5)
        }
    } catch (error) {
        console.error('Error fetching projects:', error)
    }
}

const truncate = (text, length) => {
    if (text.length > length) {
        return text.substring(0, length) + '...'
    }
    return text
}
</script>
