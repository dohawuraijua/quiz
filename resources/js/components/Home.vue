<template>

    <main class="content-area bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <h2 id="welcome-heading" class="text-3xl font-bold text-gray-900 mb-2">Welcome back!</h2>
            <p id="dashboard-subtitle" class="text-gray-600 mb-8">Continue your learning journey today</p>
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Enrolled Courses</p>
                    <p class="text-3xl font-bold text-gray-900">8</p>
                </div>
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-900">5</p>
                </div>
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Pending Assignments</p>
                    <p class="text-3xl font-bold text-gray-900">4</p>
                </div>
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Average Grade</p>
                    <p class="text-3xl font-bold text-gray-900">87%</p>
                </div>
            </div><!-- Continue Learning Section -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Continue Learning</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"><!-- Course Card 1 -->
                    <div class="card overflow-hidden">
                        <div class="h-40 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor"
                                viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <div class="p-6"><span class="badge bg-blue-100 text-blue-700 mb-2">Programming</span>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Web Development Fundamentals</h4>
                            <p class="text-sm text-gray-600 mb-4">Learn HTML, CSS, and JavaScript basics</p>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1"><span
                                        class="text-gray-600">Progress</span> <span
                                        class="font-semibold text-gray-900">65%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 65%"></div>
                                </div>
                            </div><button
                                class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-colors">
                                Continue Learning </button>
                        </div>
                    </div><!-- Course Card 2 -->
                    <div class="card overflow-hidden">
                        <div
                            class="h-40 bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor"
                                viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <div class="p-6"><span class="badge bg-purple-100 text-purple-700 mb-2">Design</span>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">UI/UX Design Principles</h4>
                            <p class="text-sm text-gray-600 mb-4">Master design thinking and user experience</p>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1"><span
                                        class="text-gray-600">Progress</span> <span
                                        class="font-semibold text-gray-900">42%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 42%"></div>
                                </div>
                            </div><button
                                class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-colors">
                                Continue Learning </button>
                        </div>
                    </div><!-- Course Card 3 -->
                    <div class="card overflow-hidden">
                        <div
                            class="h-40 bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor"
                                viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="p-6"><span class="badge bg-emerald-100 text-emerald-700 mb-2">Business</span>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Data Analytics Basics</h4>
                            <p class="text-sm text-gray-600 mb-4">Learn to analyze and visualize data</p>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1"><span
                                        class="text-gray-600">Progress</span> <span
                                        class="font-semibold text-gray-900">78%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 78%"></div>
                                </div>
                            </div><button
                                class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-colors">
                                Continue Learning </button>
                        </div>
                    </div>
                </div>
            </div><!-- Upcoming Deadlines -->
            <div class="card p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Upcoming Deadlines</h3>
                <div class="space-y-4">
                    <div
                        class="sm:flex sm:items-start sm:justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">JavaScript Final Project</p>
                                <p class="text-sm text-gray-600">Web Development Fundamentals</p>
                                <p class="text-sm text-red-600 font-medium mt-1">Due: Tomorrow, 11:59 PM</p>
                            </div>
                        </div><span class="badge bg-red-100 text-red-700 mt-2 sm:mt-0">Urgent</span>
                    </div>
                    <div
                        class="sm:flex sm:items-start sm:justify-between p-4 bg-amber-50 rounded-lg border border-amber-200">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">UI Design Quiz</p>
                                <p class="text-sm text-gray-600">UI/UX Design Principles</p>
                                <p class="text-sm text-amber-600 font-medium mt-1">Due: In 3 days</p>
                            </div>
                        </div><span class="badge bg-amber-100 text-amber-700 mt-2 sm:mt-0">Upcoming</span>
                    </div>
                    <div
                        class="sm:flex sm:items-start sm:justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Data Analysis Assignment</p>
                                <p class="text-sm text-gray-600">Data Analytics Basics</p>
                                <p class="text-sm text-blue-600 font-medium mt-1">Due: In 5 days</p>
                            </div>
                        </div><span class="badge bg-blue-100 text-blue-700 mt-2 sm:mt-0">Scheduled</span>
                    </div>
                </div>
            </div>
        </div>

    </main>

</template>
