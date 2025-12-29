<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="text-black dark:text-white">Job Opportunities</span>
          </h1>
          <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto mb-8">
            Discover exciting career opportunities in Software, Robotics, AI, and cutting-edge technology
          </p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 bg-gray-50 dark:bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Sidebar Filters -->
          <div class="lg:col-span-1 space-y-6">
            <!-- Post Job Button -->
            <div v-if="isAuthenticated" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <button
                @click="showCreateModal = true"
                class="w-full bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-lg font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 flex items-center justify-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Post a Job
              </button>
            </div>

            <!-- Categories Filter -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Categories</h3>
              <div class="space-y-2">
                <button
                  v-for="category in categories"
                  :key="category.id"
                  @click="filterByCategory(category.id)"
                  :class="[
                    'w-full text-left px-4 py-2 rounded-lg transition-colors flex items-center gap-2',
                    selectedCategory === category.id
                      ? 'bg-black dark:bg-white text-white dark:text-black'
                      : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                  ]"
                >
                  <span>{{ category.icon }}</span>
                  <span>{{ category.name }}</span>
                </button>
              </div>
            </div>

            <!-- Job Type Filter -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Job Type</h3>
              <div class="space-y-2">
                <button
                  v-for="type in jobTypes"
                  :key="type.id"
                  @click="filterByJobType(type.id)"
                  :class="[
                    'w-full text-left px-4 py-2 rounded-lg transition-colors',
                    selectedJobType === type.id
                      ? 'bg-black dark:bg-white text-white dark:text-black'
                      : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                  ]"
                >
                  {{ type.name }}
                </button>
              </div>
            </div>

            <!-- Experience Level Filter -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
              <h3 class="text-lg font-bold text-black dark:text-white mb-4">Experience Level</h3>
              <div class="space-y-2">
                <button
                  v-for="level in experienceLevels"
                  :key="level.id"
                  @click="filterByExperienceLevel(level.id)"
                  :class="[
                    'w-full text-left px-4 py-2 rounded-lg transition-colors',
                    selectedExperienceLevel === level.id
                      ? 'bg-black dark:bg-white text-white dark:text-black'
                      : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                  ]"
                >
                  {{ level.name }}
                </button>
              </div>
            </div>
          </div>

          <!-- Main Jobs List -->
          <div class="lg:col-span-3 space-y-6">
            <!-- Search Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  @keyup.enter="searchJobs"
                  type="text"
                  placeholder="Search jobs by title, description, or skills..."
                  class="w-full px-4 py-3 pl-12 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="text-center py-12">
              <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
              <p class="mt-4 text-gray-600 dark:text-gray-400">Loading jobs...</p>
            </div>

            <!-- Jobs List -->
            <div v-else class="space-y-6">
              <article
                v-for="job in jobs"
                :key="job.id"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow duration-300"
              >
                <div class="p-6">
                  <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                      <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-2xl font-bold text-black dark:text-white hover:text-primary-600 dark:hover:text-primary-400 cursor-pointer" @click="viewJob(job.id)">
                          {{ job.title }}
                        </h2>
                        <span v-if="job.is_featured" class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded text-xs font-medium">
                          ⭐ Featured
                        </span>
                      </div>
                      <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                        <div class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                          </svg>
                          {{ job.company?.company || job.company?.username || 'Company' }}
                        </div>
                        <div class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                          {{ job.location || 'Remote' }}
                        </div>
                        <div class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ formatJobType(job.job_type) }}
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <span :class="getCategoryBadgeClass(job.category)" class="px-3 py-1 rounded-full text-xs font-medium">
                        {{ getCategoryName(job.category) }}
                      </span>
                    </div>
                  </div>

                  <!-- Job Description Preview -->
                  <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-2">
                    {{ job.description }}
                  </p>

                  <!-- Skills -->
                  <div v-if="job.skills && job.skills.length > 0" class="flex flex-wrap gap-2 mb-4">
                    <span
                      v-for="skill in job.skills.slice(0, 5)"
                      :key="skill"
                      class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-xs"
                    >
                      {{ skill }}
                    </span>
                    <span v-if="job.skills.length > 5" class="px-2 py-1 text-gray-600 dark:text-gray-400 text-xs">
                      +{{ job.skills.length - 5 }} more
                    </span>
                  </div>

                  <!-- Job Details Footer -->
                  <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                      <div v-if="job.salary_range" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ job.salary_range }} {{ job.currency }}
                      </div>
                      <div v-if="job.experience_level" class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        {{ formatExperienceLevel(job.experience_level) }}
                      </div>
                      <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ job.views_count }} views
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <button
                        @click="shareJob(job)"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors flex items-center gap-2"
                        title="Share this job"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                        </svg>
                        Share
                      </button>
                      <button
                        @click="viewJob(job.id)"
                        class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors font-medium"
                      >
                        View Details
                      </button>
                    </div>
                  </div>
                </div>
              </article>

              <!-- Empty State -->
              <div v-if="jobs.length === 0" class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">No jobs found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your filters or check back later</p>
                <button
                  v-if="isAuthenticated"
                  @click="showCreateModal = true"
                  class="bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-lg font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors"
                >
                  Post First Job
                </button>
              </div>

              <!-- Pagination -->
              <div v-if="pagination.total > pagination.per_page" class="flex justify-center mt-8">
                <nav class="flex space-x-2">
                  <button
                    @click="loadPage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    Previous
                  </button>
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="loadPage(page)"
                    :class="[
                      'px-4 py-2 rounded-lg transition-colors',
                      page === pagination.current_page
                        ? 'bg-black dark:bg-white text-white dark:text-black'
                        : 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-700'
                    ]"
                  >
                    {{ page }}
                  </button>
                  <button
                    @click="loadPage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  >
                    Next
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Create Job Modal -->
    <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeCreateModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-bold text-black dark:text-white">Post a Job</h3>
          <button
            @click="closeCreateModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="createJob" class="p-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Job Title *</label>
              <input
                v-model="newJob.title"
                type="text"
                required
                maxlength="255"
                placeholder="e.g., Senior AI Engineer"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Category *</label>
              <select
                v-model="newJob.category"
                required
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="">Select category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.icon }} {{ cat.name }}
                </option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Job Type *</label>
              <select
                v-model="newJob.job_type"
                required
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="">Select type</option>
                <option v-for="type in jobTypes" :key="type.id" :value="type.id">
                  {{ type.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Experience Level</label>
              <select
                v-model="newJob.experience_level"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="">Select level</option>
                <option v-for="level in experienceLevels" :key="level.id" :value="level.id">
                  {{ level.name }}
                </option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Location</label>
            <input
              v-model="newJob.location"
              type="text"
              placeholder="e.g., Remote, New York, Hybrid"
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Salary Range</label>
              <input
                v-model="newJob.salary_range"
                type="text"
                placeholder="e.g., $80,000 - $120,000"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Currency</label>
              <input
                v-model="newJob.currency"
                type="text"
                placeholder="USD"
                maxlength="3"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Description *</label>
            <textarea
              v-model="newJob.description"
              required
              rows="6"
              placeholder="Describe the job position, responsibilities, and what you're looking for..."
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
            ></textarea>
            <p :class="[
              'text-sm mt-1',
              newJob.description.length < 30 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400'
            ]">
              {{ newJob.description.length }} / 30 characters minimum
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Requirements</label>
            <textarea
              v-model="newJob.requirements"
              rows="4"
              placeholder="List the required qualifications, skills, and experience..."
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Required Skills (comma separated)</label>
            <input
              v-model="skillsInput"
              type="text"
              placeholder="e.g., Python, Machine Learning, TensorFlow, Docker"
              @input="updateSkills"
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
            <div v-if="newJob.skills && newJob.skills.length > 0" class="flex flex-wrap gap-2 mt-2">
              <span
                v-for="skill in newJob.skills"
                :key="skill"
                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm flex items-center gap-2"
              >
                {{ skill }}
                <button type="button" @click="removeSkill(skill)" class="hover:text-red-600">×</button>
              </span>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Benefits (comma separated)</label>
            <input
              v-model="benefitsInput"
              type="text"
              placeholder="e.g., Health Insurance, Remote Work, Stock Options"
              @input="updateBenefits"
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
            <div v-if="newJob.benefits && newJob.benefits.length > 0" class="flex flex-wrap gap-2 mt-2">
              <span
                v-for="benefit in newJob.benefits"
                :key="benefit"
                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm flex items-center gap-2"
              >
                {{ benefit }}
                <button type="button" @click="removeBenefit(benefit)" class="hover:text-red-600">×</button>
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Application Email</label>
              <input
                v-model="newJob.application_email"
                type="email"
                placeholder="jobs@company.com"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-black dark:text-white mb-2">Application URL</label>
              <input
                v-model="newJob.application_url"
                type="url"
                placeholder="https://company.com/careers/apply"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-black dark:text-white mb-2">Application Deadline</label>
            <input
              v-model="newJob.application_deadline"
              type="date"
              :min="new Date().toISOString().split('T')[0]"
              class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Leave empty if no deadline</p>
          </div>

          <div class="flex justify-end gap-3 pt-4">
            <button
              type="button"
              @click="closeCreateModal"
              class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isCreating"
              class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors disabled:opacity-50"
            >
              {{ isCreating ? 'Posting...' : 'Post Job' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Job Detail Modal -->
    <div
      v-if="selectedJob"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeJobDetail"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-bold text-black dark:text-white">Job Details</h3>
          <button
            @click="closeJobDetail"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="p-6">
          <!-- Job Header -->
          <div class="mb-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h2 class="text-3xl font-bold text-black dark:text-white mb-2">{{ selectedJob.title }}</h2>
                <div class="flex items-center gap-4 text-gray-600 dark:text-gray-400 mb-3">
                  <div class="flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ selectedJob.company?.company || selectedJob.company?.username || 'Company' }}
                  </div>
                  <div class="flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ selectedJob.location || 'Remote' }}
                  </div>
                  <div class="flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ formatJobType(selectedJob.job_type) }}
                  </div>
                </div>
              </div>
              <span :class="getCategoryBadgeClass(selectedJob.category)" class="px-3 py-1 rounded-full text-sm font-medium">
                {{ getCategoryName(selectedJob.category) }}
              </span>
            </div>
          </div>

          <!-- Job Description -->
          <div class="mb-6">
            <h3 class="text-xl font-bold text-black dark:text-white mb-3">Description</h3>
            <div class="prose prose-lg max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
              {{ selectedJob.description }}
            </div>
          </div>

          <!-- Requirements -->
          <div v-if="selectedJob.requirements" class="mb-6">
            <h3 class="text-xl font-bold text-black dark:text-white mb-3">Requirements</h3>
            <div class="prose prose-lg max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
              {{ selectedJob.requirements }}
            </div>
          </div>

          <!-- Skills -->
          <div v-if="selectedJob.skills && selectedJob.skills.length > 0" class="mb-6">
            <h3 class="text-xl font-bold text-black dark:text-white mb-3">Required Skills</h3>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="skill in selectedJob.skills"
                :key="skill"
                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
              >
                {{ skill }}
              </span>
            </div>
          </div>

          <!-- Benefits -->
          <div v-if="selectedJob.benefits && selectedJob.benefits.length > 0" class="mb-6">
            <h3 class="text-xl font-bold text-black dark:text-white mb-3">Benefits</h3>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
              <li v-for="benefit in selectedJob.benefits" :key="benefit">{{ benefit }}</li>
            </ul>
          </div>

          <!-- Job Details -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div v-if="selectedJob.salary_range" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Salary Range</p>
              <p class="text-lg font-semibold text-black dark:text-white">{{ selectedJob.salary_range }} {{ selectedJob.currency }}</p>
            </div>
            <div v-if="selectedJob.experience_level" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Experience Level</p>
              <p class="text-lg font-semibold text-black dark:text-white">{{ formatExperienceLevel(selectedJob.experience_level) }}</p>
            </div>
            <div v-if="selectedJob.application_deadline" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Application Deadline</p>
              <p class="text-lg font-semibold text-black dark:text-white">{{ formatDate(selectedJob.application_deadline) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Views</p>
              <p class="text-lg font-semibold text-black dark:text-white">{{ selectedJob.views_count }}</p>
            </div>
          </div>

          <!-- Apply Button -->
          <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a
              v-if="selectedJob.application_url"
              :href="selectedJob.application_url"
              target="_blank"
              rel="noopener noreferrer"
              class="flex-1 bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors font-medium text-center"
            >
              Apply Now
            </a>
            <a
              v-else-if="selectedJob.application_email"
              :href="`mailto:${selectedJob.application_email}?subject=Application for ${selectedJob.title}`"
              class="flex-1 bg-black dark:bg-white text-white dark:text-black px-6 py-3 rounded-lg hover:bg-gray-900 dark:hover:bg-gray-100 transition-colors font-medium text-center"
            >
              Apply via Email
            </a>
            <button
              v-else
              disabled
              class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 px-6 py-3 rounded-lg cursor-not-allowed font-medium"
            >
              No Application Method Available
            </button>
            <button
              @click="shareJob(selectedJob)"
              class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
              </svg>
              Share / Invite
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Share Job Modal -->
    <div
      v-if="showShareModal && jobToShare"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeShareModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full mx-4" @click.stop>
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-xl font-semibold text-black dark:text-white">Share Job Opportunity</h3>
          <button
            @click="closeShareModal"
            class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="p-6">
          <div class="mb-6">
            <h4 class="text-lg font-medium text-black dark:text-white mb-2">{{ jobToShare.title }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ jobToShare.company?.company || jobToShare.company?.username || 'Company' }}</p>
          </div>

          <!-- Share Options -->
          <div class="grid grid-cols-2 gap-3">
            <!-- Facebook -->
            <button
              @click="shareToFacebook"
              class="flex items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
              <span>Facebook</span>
            </button>

            <!-- Twitter/X -->
            <button
              @click="shareToTwitter"
              class="flex items-center justify-center p-4 bg-black hover:bg-gray-800 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
              <span>X (Twitter)</span>
            </button>

            <!-- LinkedIn -->
            <button
              @click="shareToLinkedIn"
              class="flex items-center justify-center p-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              <span>LinkedIn</span>
            </button>

            <!-- WhatsApp -->
            <button
              @click="shareToWhatsApp"
              class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
              </svg>
              <span>WhatsApp</span>
            </button>

            <!-- Telegram -->
            <button
              @click="shareToTelegram"
              class="flex items-center justify-center p-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
              </svg>
              <span>Telegram</span>
            </button>

            <!-- Email -->
            <button
              @click="shareViaEmail"
              class="flex items-center justify-center p-4 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>Email</span>
            </button>

            <!-- Native Share (Mobile) -->
            <button
              v-if="hasNativeShare"
              @click="shareViaNative"
              class="flex items-center justify-center p-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
              </svg>
              <span>More</span>
            </button>

            <!-- Copy Link -->
            <button
              @click="copyToClipboard(getJobUrl(jobToShare))"
              class="flex items-center justify-center p-4 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors col-span-2"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
              <span>Copy Link</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { jobService, type JobPost, type CreateJobRequest } from '../services/jobService'

const router = useRouter()
const authStore = useAuthStore()

const jobs = ref<JobPost[]>([])
const categories = ref<Array<{ id: string; name: string; icon: string }>>([])
const jobTypes = ref<Array<{ id: string; name: string }>>([])
const experienceLevels = ref<Array<{ id: string; name: string }>>([])
const selectedCategory = ref('')
const selectedJobType = ref('')
const selectedExperienceLevel = ref('')
const searchQuery = ref('')
const isLoading = ref(false)
const showCreateModal = ref(false)
const selectedJob = ref<JobPost | null>(null)
const isCreating = ref(false)

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0
})

const newJob = ref<CreateJobRequest>({
  title: '',
  description: '',
  requirements: '',
  location: '',
  job_type: '',
  category: '',
  experience_level: '',
  salary_range: '',
  currency: 'USD',
  skills: [],
  benefits: [],
  application_email: '',
  application_url: '',
  application_deadline: ''
})

const skillsInput = ref('')
const benefitsInput = ref('')

const isAuthenticated = computed(() => authStore.isAuthenticated)

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, pagination.value.current_page - 2)
  const end = Math.min(pagination.value.last_page, pagination.value.current_page + 2)
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

const loadJobs = async () => {
  isLoading.value = true
  try {
    const response = await jobService.getJobs({
      category: selectedCategory.value || undefined,
      job_type: selectedJobType.value || undefined,
      experience_level: selectedExperienceLevel.value || undefined,
      search: searchQuery.value || undefined,
      per_page: pagination.value.per_page,
      page: pagination.value.current_page
    })
    
    if (response.success) {
      jobs.value = response.data
      pagination.value = response.pagination
    }
  } catch (error: any) {
    console.error('Failed to load jobs:', error)
  } finally {
    isLoading.value = false
  }
}

const loadCategories = async () => {
  try {
    const response = await jobService.getCategories()
    if (response.success) {
      categories.value = response.data
    }
  } catch (error) {
    console.error('Failed to load categories:', error)
  }
}

const loadJobTypes = async () => {
  try {
    const response = await jobService.getJobTypes()
    if (response.success) {
      jobTypes.value = response.data
    }
  } catch (error) {
    console.error('Failed to load job types:', error)
  }
}

const loadExperienceLevels = async () => {
  try {
    const response = await jobService.getExperienceLevels()
    if (response.success) {
      experienceLevels.value = response.data
    }
  } catch (error) {
    console.error('Failed to load experience levels:', error)
  }
}

const filterByCategory = (categoryId: string) => {
  selectedCategory.value = selectedCategory.value === categoryId ? '' : categoryId
  pagination.value.current_page = 1
  loadJobs()
}

const filterByJobType = (jobTypeId: string) => {
  selectedJobType.value = selectedJobType.value === jobTypeId ? '' : jobTypeId
  pagination.value.current_page = 1
  loadJobs()
}

const filterByExperienceLevel = (levelId: string) => {
  selectedExperienceLevel.value = selectedExperienceLevel.value === levelId ? '' : levelId
  pagination.value.current_page = 1
  loadJobs()
}

const searchJobs = () => {
  pagination.value.current_page = 1
  loadJobs()
}

const loadPage = (page: number) => {
  pagination.value.current_page = page
  loadJobs()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const createJob = async () => {
  if (!newJob.value.title || !newJob.value.description || !newJob.value.category || !newJob.value.job_type) {
    alert('Please fill in all required fields')
    return
  }

  if (newJob.value.description.length < 30) {
    alert('Description must be at least 30 characters long')
    return
  }

  isCreating.value = true
  try {
    const response = await jobService.createJob(newJob.value)
    if (response.success) {
      showCreateModal.value = false
      resetNewJob()
      loadJobs()
      alert('Job posted successfully!')
    } else {
      alert('Failed to create job. Please try again.')
    }
  } catch (error: any) {
    console.error('Failed to create job:', error)
    let errorMessage = 'Failed to create job'
    
    if (error.message) {
      try {
        const errorData = JSON.parse(error.message.replace('HTTP 422: ', ''))
        if (errorData.errors) {
          const errorList = Object.entries(errorData.errors)
            .map(([field, messages]: [string, any]) => {
              const fieldName = field.replace('_', ' ')
              return `${fieldName}: ${Array.isArray(messages) ? messages.join(', ') : messages}`
            })
            .join('\n')
          errorMessage = `Validation errors:\n${errorList}`
        } else if (errorData.message) {
          errorMessage = errorData.message
        }
      } catch (e) {
        // If parsing fails, use the original error message
        errorMessage = error.message || 'Failed to create job'
      }
    }
    
    alert(errorMessage)
  } finally {
    isCreating.value = false
  }
}

const resetNewJob = () => {
  newJob.value = {
    title: '',
    description: '',
    requirements: '',
    location: '',
    job_type: '',
    category: '',
    experience_level: '',
    salary_range: '',
    currency: 'USD',
    skills: [],
    benefits: [],
    application_email: '',
    application_url: '',
    application_deadline: ''
  }
  skillsInput.value = ''
  benefitsInput.value = ''
}

const closeCreateModal = () => {
  showCreateModal.value = false
  resetNewJob()
}

const updateSkills = () => {
  const skills = skillsInput.value.split(',').map(s => s.trim()).filter(s => s)
  newJob.value.skills = skills
}

const removeSkill = (skill: string) => {
  newJob.value.skills = newJob.value.skills?.filter(s => s !== skill) || []
  skillsInput.value = newJob.value.skills.join(', ')
}

const updateBenefits = () => {
  const benefits = benefitsInput.value.split(',').map(b => b.trim()).filter(b => b)
  newJob.value.benefits = benefits
}

const removeBenefit = (benefit: string) => {
  newJob.value.benefits = newJob.value.benefits?.filter(b => b !== benefit) || []
  benefitsInput.value = newJob.value.benefits.join(', ')
}

const viewJob = async (jobId: number) => {
  try {
    const response = await jobService.getJob(jobId)
    if (response.success) {
      selectedJob.value = response.data
    }
  } catch (error) {
    console.error('Failed to load job:', error)
  }
}

const closeJobDetail = () => {
  selectedJob.value = null
}

const showShareModal = ref(false)
const jobToShare = ref<JobPost | null>(null)

// Check if native share API is available
const hasNativeShare = computed(() => {
  return typeof navigator !== 'undefined' && 'share' in navigator
})

const shareJob = (job: JobPost) => {
  jobToShare.value = job
  showShareModal.value = true
}

const closeShareModal = () => {
  showShareModal.value = false
  jobToShare.value = null
}

const getJobUrl = (job: JobPost) => {
  return `${window.location.origin}/jobs#job-${job.id}`
}

const shareToFacebook = () => {
  if (!jobToShare.value) return
  const url = encodeURIComponent(getJobUrl(jobToShare.value))
  const text = encodeURIComponent(`${jobToShare.value.title} - ${jobToShare.value.company?.company || 'Job Opportunity'}`)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400')
}

const shareToTwitter = () => {
  if (!jobToShare.value) return
  const url = encodeURIComponent(getJobUrl(jobToShare.value))
  const text = encodeURIComponent(`Check out this job opportunity: ${jobToShare.value.title}`)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
}

const shareToLinkedIn = () => {
  if (!jobToShare.value) return
  const url = encodeURIComponent(getJobUrl(jobToShare.value))
  window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400')
}

const shareToWhatsApp = () => {
  if (!jobToShare.value) return
  const url = encodeURIComponent(getJobUrl(jobToShare.value))
  const text = encodeURIComponent(`Check out this job opportunity: ${jobToShare.value.title}\n${getJobUrl(jobToShare.value)}`)
  window.open(`https://wa.me/?text=${text}`, '_blank')
}

const shareToTelegram = () => {
  if (!jobToShare.value) return
  const url = encodeURIComponent(getJobUrl(jobToShare.value))
  const text = encodeURIComponent(`Check out this job opportunity: ${jobToShare.value.title}`)
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
}

const shareViaEmail = () => {
  if (!jobToShare.value) return
  const subject = encodeURIComponent(`Job Opportunity: ${jobToShare.value.title}`)
  const body = encodeURIComponent(`Hi,\n\nI found this interesting job opportunity that might interest you:\n\n${jobToShare.value.title}\n\n${jobToShare.value.description.substring(0, 200)}...\n\nView full details: ${getJobUrl(jobToShare.value)}\n\nBest regards`)
  window.location.href = `mailto:?subject=${subject}&body=${body}`
}

const shareViaNative = async () => {
  if (!jobToShare.value) return
  
  if (typeof navigator === 'undefined' || !('share' in navigator)) {
    copyToClipboard(getJobUrl(jobToShare.value))
    return
  }
  
  try {
    await navigator.share({
      title: jobToShare.value.title,
      text: `Check out this job opportunity: ${jobToShare.value.title}`,
      url: getJobUrl(jobToShare.value)
    })
  } catch (error) {
    // User cancelled or error occurred
    if ((error as Error).name !== 'AbortError') {
      copyToClipboard(getJobUrl(jobToShare.value))
    }
  }
}

const copyToClipboard = async (text: string) => {
  try {
    await navigator.clipboard.writeText(text)
    alert('Link copied to clipboard!')
  } catch (error) {
    console.error('Failed to copy:', error)
  }
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}

const formatJobType = (type: string): string => {
  const typeMap: Record<string, string> = {
    'full-time': 'Full Time',
    'part-time': 'Part Time',
    'contract': 'Contract',
    'internship': 'Internship'
  }
  return typeMap[type] || type
}

const formatExperienceLevel = (level: string): string => {
  const levelMap: Record<string, string> = {
    'entry': 'Entry Level',
    'mid': 'Mid Level',
    'senior': 'Senior Level',
    'executive': 'Executive'
  }
  return levelMap[level] || level
}

const getCategoryName = (category: string): string => {
  const cat = categories.value.find(c => c.id === category)
  return cat ? `${cat.icon} ${cat.name}` : category
}

const getCategoryBadgeClass = (category: string): string => {
  const classes: Record<string, string> = {
    'software': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
    'robotics': 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200',
    'ai': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
    'data-science': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
    'machine-learning': 'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200',
    'automation': 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200',
    'iot': 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200'
  }
  return classes[category] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'
}

onMounted(async () => {
  await loadCategories()
  await loadJobTypes()
  await loadExperienceLevels()
  await loadJobs()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.prose {
  @apply text-gray-700 dark:text-gray-300;
}

.prose p {
  @apply mb-4;
}
</style>

