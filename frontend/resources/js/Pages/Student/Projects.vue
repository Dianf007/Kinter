<template>
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Projects</h1>
            <p class="text-gray-600 dark:text-gray-400">View and manage your Scratch projects</p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-4 mb-8 border-b border-gray-200 dark:border-gray-700">
            <button
                @click="activeTab = 'my'"
                :class="[
                    'px-4 py-3 font-medium transition border-b-2',
                    activeTab === 'my'
                        ? 'text-purple-600 border-purple-600'
                        : 'text-gray-600 dark:text-gray-400 border-transparent hover:text-gray-900 dark:hover:text-gray-300'
                ]"
            >
                My Projects ({{ myProjects.length }})
            </button>
            <button
                @click="activeTab = 'friends'"
                :class="[
                    'px-4 py-3 font-medium transition border-b-2',
                    activeTab === 'friends'
                        ? 'text-purple-600 border-purple-600'
                        : 'text-gray-600 dark:text-gray-400 border-transparent hover:text-gray-900 dark:hover:text-gray-300'
                ]"
            >
                Friends' Projects ({{ friendsProjects.length }})
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
            <div class="animate-spin text-4xl mb-4">⏳</div>
            <p class="text-gray-600 dark:text-gray-400">Loading projects...</p>
        </div>

        <!-- My Projects Tab -->
        <div v-else-if="activeTab === 'my'">
            <div v-if="myProjects.length === 0" class="text-center py-12">
                <div class="text-6xl mb-4">📭</div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't created any projects yet</p>
                <p class="text-sm text-gray-500 dark:text-gray-500">Start creating amazing projects on Scratch!</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    v-for="project in myProjects"
                    :key="project.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition"
                >
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 text-white">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h3 class="text-xl font-bold mb-2">{{ project.title }}</h3>
                                <p class="text-purple-100 text-sm line-clamp-2">{{ project.description }}</p>
                            </div>
                            <span
                                v-if="project.is_published"
                                class="inline-block bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap"
                            >
                                Published
                            </span>
                            <span v-else class="inline-block bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">
                                Private
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <p class="mb-2"><strong>Instructions:</strong></p>
                            <p class="line-clamp-3">{{ project.instructions }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ formatDate(project.created_at) }}
                            </p>
                            <button
                                @click="togglePublish(project)"
                                :disabled="togglingPublish[project.id]"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                :class="project.is_published
                                    ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            >
                                {{ togglingPublish[project.id] ? 'Updating...' : (project.is_published ? '🔓 Unpublish' : '🔒 Publish') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Friends' Projects Tab -->
        <div v-else-if="activeTab === 'friends'">
            <div v-if="friendsProjects.length === 0" class="text-center py-12">
                <div class="text-6xl mb-4">🔍</div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">No published projects from friends yet</p>
                <p class="text-sm text-gray-500 dark:text-gray-500">Check back soon!</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    v-for="project in friendsProjects"
                    :key="project.id"
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition"
                >
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6 text-white">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2">{{ project.title }}</h3>
                                <p class="text-blue-100 text-sm line-clamp-2">{{ project.description }}</p>
                            </div>
                            <span class="inline-block bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">
                                Published
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-4">
                        <!-- Student Info -->
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <img :src="project.student.avatar_url" :alt="project.student.name" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ project.student.name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Created {{ formatDate(project.created_at) }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <p class="mb-2"><strong>Instructions:</strong></p>
                            <p class="line-clamp-3">{{ project.instructions }}</p>
                        </div>

                        <!-- View Link -->
                        <a
                            :href="`https://scratch.mit.edu/projects/${project.scratch_id}`"
                            target="_blank"
                            class="inline-block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
                        >
                            View on Scratch →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const activeTab = ref('my')
const loading = ref(true)
const myProjects = ref([])
const friendsProjects = ref([])
const togglingPublish = ref({})

onMounted(() => {
    fetchProjects()
})

const fetchProjects = async () => {
    loading.value = true
    try {
        const response = await fetch('/api/student/projects', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Accept': 'application/json'
            }
        })

        if (response.ok) {
            const data = await response.json()
            myProjects.value = data.projects.filter(p => p.is_owner)
            friendsProjects.value = data.projects.filter(p => !p.is_owner)
        }
    } catch (error) {
        console.error('Error fetching projects:', error)
    } finally {
        loading.value = false
    }
}

const togglePublish = async (project) => {
    togglingPublish.value[project.id] = true

    try {
        const response = await fetch(`/api/student/projects/${project.id}/publish`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                is_published: !project.is_published
            })
        })

        if (response.ok) {
            project.is_published = !project.is_published
        }
    } catch (error) {
        console.error('Error updating publish status:', error)
    } finally {
        togglingPublish.value[project.id] = false
    }
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}
</script>
