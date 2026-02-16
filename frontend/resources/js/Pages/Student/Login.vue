<template>
    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-purple-500 to-indigo-600 flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🎨</div>
                <h1 class="text-3xl font-bold text-white mb-2">Kinter Student Portal</h1>
                <p class="text-purple-100">Login to your account</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 space-y-6">
                <!-- Error Alert -->
                <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ error }}
                </div>

                <!-- Form -->
                <form @submit.prevent="login" class="space-y-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="Enter your email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                            :disabled="loading"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="Enter your password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                            :disabled="loading"
                        >
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-400 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center gap-2"
                    >
                        <span v-if="loading" class="animate-spin">⏳</span>
                        {{ loading ? 'Logging in...' : 'Login' }}
                    </button>
                </form>

                <!-- Demo Credentials -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-blue-900 mb-2">Demo Credentials:</p>
                    <p class="text-xs text-blue-700"><strong>Email:</strong> student@example.com</p>
                    <p class="text-xs text-blue-700"><strong>Password:</strong> password123</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-purple-100 text-sm">
                    Need help? <a href="/" class="text-white font-medium hover:underline">Contact support</a>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(false)
const error = ref('')
const form = ref({
    email: '',
    password: ''
})

const login = async () => {
    error.value = ''
    loading.value = true

    try {
        const response = await fetch('/api/student/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(form.value)
        })

        const data = await response.json()

        if (response.ok) {
            // Save token
            localStorage.setItem('student_token', data.token)
            localStorage.setItem('student', JSON.stringify(data.student))

            // Redirect to dashboard
            router.push('/student/dashboard')
        } else {
            error.value = data.message || 'Login failed. Please try again.'
        }
    } catch (err) {
        error.value = 'An error occurred. Please try again.'
        console.error('Login error:', err)
    } finally {
        loading.value = false
    }
}
</script>
