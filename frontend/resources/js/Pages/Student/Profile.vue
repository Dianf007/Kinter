<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Edit Profile</h1>
            <p class="text-gray-600 dark:text-gray-400">Update your personal information</p>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
            <div class="animate-spin text-4xl mb-4">⏳</div>
            <p class="text-gray-600 dark:text-gray-400">Loading profile...</p>
        </div>

        <div v-else class="space-y-6">
            <!-- Success/Error Messages -->
            <div v-if="successMessage" class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                {{ successMessage }}
            </div>
            <div v-if="errorMessage" class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                {{ errorMessage }}
            </div>

            <!-- Profile Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Avatar Section -->
                    <div class="flex items-center gap-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <img :src="form.avatar_url || defaultAvatar" :alt="form.name" class="w-20 h-20 rounded-full">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Profile Picture</h3>
                            <input
                                type="file"
                                @change="handleAvatarUpload"
                                accept="image/*"
                                class="text-sm"
                                :disabled="submitting"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Max 2MB. Formats: JPEG, PNG, GIF</p>
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                :disabled="submitting"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                :disabled="submitting"
                            >
                        </div>

                        <!-- Student ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student ID</label>
                            <input
                                v-model="form.student_id"
                                type="text"
                                disabled
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white outline-none"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cannot be changed</p>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                            <select
                                v-model="form.gender"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                :disabled="submitting"
                            >
                                <option value="">Select Gender</option>
                                <option value="L">Male</option>
                                <option value="P">Female</option>
                            </select>
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Birth Date</label>
                            <input
                                v-model="form.birth_date"
                                type="date"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                :disabled="submitting"
                            >
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                :disabled="submitting"
                            >
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <textarea
                            v-model="form.address"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                            :disabled="submitting"
                        ></textarea>
                    </div>

                    <!-- Password Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Change Password (Optional)</h3>
                        <div class="space-y-4">
                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Leave empty to keep current password"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                    :disabled="submitting"
                                >
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Min 6 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    placeholder="Confirm new password"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                                    :disabled="submitting"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button
                            type="submit"
                            :disabled="submitting"
                            class="flex-1 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2"
                        >
                            <span v-if="submitting" class="animate-spin">⏳</span>
                            {{ submitting ? 'Saving...' : 'Save Changes' }}
                        </button>
                        <button
                            type="button"
                            @click="resetForm"
                            :disabled="submitting"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-bold py-3 px-4 rounded-lg transition duration-300"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const loading = ref(true)
const submitting = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const defaultAvatar = ref('https://ui-avatars.com/api/?name=Student&background=667eea&color=fff')

const form = ref({
    name: '',
    email: '',
    student_id: '',
    gender: '',
    birth_date: '',
    phone: '',
    address: '',
    password: '',
    password_confirmation: '',
    avatar_url: '',
    avatar: null
})

const originalForm = ref({})

onMounted(() => {
    fetchProfile()
})

const fetchProfile = async () => {
    loading.value = true
    try {
        const response = await fetch('/api/student/auth/me', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Accept': 'application/json'
            }
        })

        if (response.ok) {
            const data = await response.json()
            form.value = {
                ...form.value,
                ...data,
                password: '',
                password_confirmation: ''
            }
            originalForm.value = JSON.parse(JSON.stringify(form.value))
            defaultAvatar.value = data.avatar_url
        }
    } catch (error) {
        console.error('Error fetching profile:', error)
        errorMessage.value = 'Failed to load profile'
    } finally {
        loading.value = false
    }
}

const handleAvatarUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.value.avatar = file
        // Create preview
        const reader = new FileReader()
        reader.onload = (e) => {
            form.value.avatar_url = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const submitForm = async () => {
    submitting.value = true
    successMessage.value = ''
    errorMessage.value = ''

    try {
        const formData = new FormData()
        formData.append('name', form.value.name)
        formData.append('email', form.value.email)
        formData.append('gender', form.value.gender)
        formData.append('birth_date', form.value.birth_date)
        formData.append('phone', form.value.phone)
        formData.append('address', form.value.address)

        if (form.value.password) {
            formData.append('password', form.value.password)
            formData.append('password_confirmation', form.value.password_confirmation)
        }

        if (form.value.avatar instanceof File) {
            formData.append('avatar', form.value.avatar)
        }

        const response = await fetch('/api/student/profile', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('student_token')}`,
                'Accept': 'application/json'
            },
            body: formData
        })

        const data = await response.json()

        if (response.ok) {
            successMessage.value = 'Profile updated successfully!'
            originalForm.value = JSON.parse(JSON.stringify(form.value))
            form.value.password = ''
            form.value.password_confirmation = ''

            // Update localStorage
            localStorage.setItem('student', JSON.stringify(data.student))
        } else {
            errorMessage.value = data.message || 'Failed to update profile'
        }
    } catch (error) {
        console.error('Error updating profile:', error)
        errorMessage.value = 'An error occurred while updating profile'
    } finally {
        submitting.value = false
    }
}

const resetForm = () => {
    form.value = JSON.parse(JSON.stringify(originalForm.value))
    successMessage.value = ''
    errorMessage.value = ''
}
</script>
