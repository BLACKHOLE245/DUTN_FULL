<template>
  <div class="comments-page">
    <transition name="toast">
      <div v-if="notification.show" :class="['toast-notification', `toast-${notification.type}`]">
        <i :class="['bi', getNotificationIcon(notification.type)]"></i>
        <span>{{ notification.message }}</span>
        <button @click="closeNotification" class="toast-close">
          <i class="bi bi-x"></i>
        </button>
      </div>
    </transition>

    <div class="page-header">
      <div class="header-content">
        <div class="title-section">
          <h1 class="page-title">
            <i class="bi bi-chat-dots"></i>
            Quản Lý Bình Luận
          </h1>
          <p class="page-subtitle">Quản lý và kiểm duyệt bình luận người dùng</p>
        </div>
        <div class="header-stats">
          <div class="stat-card">
            <div class="stat-number">{{ totalComments }}</div>
            <div class="stat-label">Tổng số</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ pendingComments }}</div>
            <div class="stat-label">Chờ duyệt</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ approvedComments }}</div>
            <div class="stat-label">Đã duyệt</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ hiddenComments }}</div>
            <div class="stat-label">Đã ẩn</div>
          </div>
        </div>
      </div>
    </div>

    <div class="filters-section">
      <div class="search-group">
        <div class="search-wrapper">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Tìm kiếm bình luận theo nội dung hoặc người dùng..." v-model="searchTerm"
            @input="handleSearch" class="search-input" />
        </div>
      </div>

      <div class="filter-group">
        <label>
          <i class="bi bi-funnel"></i>
          Lọc theo sản phẩm
        </label>
        <select v-model="selectedProduct" @change="handleFilter" class="filter-select">
          <option value="">-- Tất cả sản phẩm --</option>
          <option v-for="product in products" :key="product" :value="product">
            {{ product }}
          </option>
        </select>
      </div>

      <div class="filter-group">
        <label>
          <i class="bi bi-flag"></i>
          Lọc theo trạng thái
        </label>
        <select v-model="selectedStatus" @change="handleFilter" class="filter-select">
          <option value="">-- Tất cả trạng thái --</option>
          <option :value="STATUS.PENDING">Chờ duyệt</option>
          <option :value="STATUS.VISIBLE">Đã duyệt</option>
          <option :value="STATUS.HIDDEN">Đã ẩn</option>
        </select>
      </div>

      <div class="filter-actions">
        <button @click="clearFilters" class="clear-filters-btn">
          <i class="bi bi-x-circle"></i>
          Xóa bộ lọc
        </button>
        <button @click="refreshData" class="refresh-btn" :disabled="loading">
          <i class="bi bi-arrow-clockwise"></i>
          Làm mới
        </button>
      </div>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <p>Đang tải dữ liệu...</p>
    </div>

    <div v-else-if="error" class="error-state">
      <i class="bi bi-exclamation-triangle"></i>
      <p>{{ error }}</p>
      <button @click="retryFetch" class="retry-btn">
        <i class="bi bi-arrow-clockwise"></i>
        Thử lại
      </button>
    </div>

    <div v-else-if="paginatedComments.length > 0" class="comments-grid">
      <div v-for="comment in paginatedComments" :key="comment.id" class="comment-card">
        <div class="comment-header">
          <div class="user-info">
            <div class="user-avatar">
              {{ (comment.user?.name_user || 'N/A').charAt(0) }}
            </div>
            <div class="user-details">
              <div class="user-name">{{ comment.user?.name_user || 'N/A' }}</div>
              <div class="user-email">{{ comment.user?.email || 'N/A' }}</div>
              <div class="comment-date">{{ formatDate(comment.created_at) }}</div>
            </div>
          </div>
          <div class="comment-status" :class="getStatusClass(comment.status)">
            <i :class="['bi', getStatusIcon(comment.status)]"></i>
            {{ getStatusText(comment.status) }}
          </div>
        </div>

        <div class="comment-body">
          <div class="product-info">
            <i class="bi bi-box"></i>
            <span>{{ comment.product?.product_name || 'N/A' }}</span>
          </div>
          <div class="comment-content">
            {{ comment.comment_content }}
          </div>
          <div class="comment-rating" v-if="comment.rating">
            <i class="bi bi-star-fill"></i>
            <span>{{ comment.rating }}/5</span>
          </div>
        </div>

        <div class="comment-actions">
          <button v-if="canApprove(comment.status)" @click="approveComment(comment.id)" class="action-btn approve-btn"
            :disabled="loading">
            <i class="bi bi-check-lg"></i>
            <span>Duyệt</span>
          </button>

          <button v-if="canHide(comment.status)" @click="hideComment(comment.id)" class="action-btn hide-btn"
            :disabled="loading">
            <i class="bi bi-eye-slash"></i>
            <span>Ẩn</span>
          </button>
        </div>
      </div>
    </div>

    <div v-else class="empty-state">
      <div class="empty-icon">
        <i class="bi bi-chat-dots"></i>
      </div>
      <h3>Không có bình luận nào</h3>
      <p>Chưa có bình luận nào phù hợp với tiêu chí tìm kiếm</p>
    </div>

    <div v-if="totalPages > 1" class="pagination">
      <button class="page-btn" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">
        <i class="bi bi-chevron-left"></i>
      </button>
      <button v-for="page in visiblePages" :key="page" class="page-btn" :class="{ active: page === currentPage }"
        @click="changePage(page)">
        {{ page }}
      </button>
      <button class="page-btn" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/comments'

const STATUS = {
  VISIBLE: 0,
  HIDDEN: 1,
  PENDING: 2
}

const comments = ref([])
const filteredComments = ref([])
const products = ref([])
const loading = ref(true)
const error = ref('')
const searchTerm = ref('')
const selectedProduct = ref('')
const selectedStatus = ref('')
const currentPage = ref(1)
const totalItems = ref(0)
const perPage = ref(15)

const notification = ref({
  show: false,
  type: '',
  message: ''
})

let searchDebounceTimer = null

const paginatedComments = computed(() => filteredComments.value)

const totalPages = computed(() =>
  Math.ceil(totalItems.value / perPage.value)
)

const totalComments = computed(() => filteredComments.value.length)

const pendingComments = computed(() =>
  filteredComments.value.filter(c => c.status === STATUS.PENDING).length
)

const approvedComments = computed(() =>
  filteredComments.value.filter(c => c.status === STATUS.VISIBLE).length
)

const hiddenComments = computed(() =>
  filteredComments.value.filter(c => c.status === STATUS.HIDDEN).length
)

const visiblePages = computed(() => {
  const pages = []
  const total = totalPages.value
  const current = currentPage.value

  if (total <= 7) {
    for (let i = 1; i <= total; i++) pages.push(i)
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) pages.push(i)
      pages.push('...')
      pages.push(total)
    } else if (current >= total - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = total - 4; i <= total; i++) pages.push(i)
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) pages.push(i)
      pages.push('...')
      pages.push(total)
    }
  }
  return pages
})

const showNotification = (type, message) => {
  notification.value = {
    show: true,
    type,
    message
  }

  setTimeout(() => {
    hideNotification()
  }, 4000)
}

const hideNotification = () => {
  notification.value.show = false
}

const closeNotification = () => {
  hideNotification()
}

const getNotificationIcon = (type) => {
  const iconMap = {
    success: 'bi-check-circle',
    error: 'bi-exclamation-triangle',
    warning: 'bi-exclamation-triangle',
    info: 'ui-icon-info'
  }
  return iconMap[type] || 'bi-info-circle'
}

const fetchComments = async (showLoadingIndicator = true) => {
  if (showLoadingIndicator) loading.value = true
  error.value = ''

  try {
    const params = {
      page: currentPage.value,
      per_page: perPage.value
    }

    if (searchTerm.value) {
      params.search = searchTerm.value
    }
    if (selectedProduct.value) {
      params.product = selectedProduct.value
    }
    if (selectedStatus.value !== '') {
      params.status = selectedStatus.value
    }

    const response = await axios.get(API_BASE_URL, { params })
    const result = response.data

    if (!result || !result.success) {
      throw new Error(result?.message || 'Phản hồi API không hợp lệ')
    }

    if (!Array.isArray(result.data?.data)) {
      throw new Error('Dữ liệu bình luận không hợp lệ')
    }

    const commentList = result.data.data
    comments.value = commentList
    filteredComments.value = [...commentList]
    totalItems.value = result.data.total || 0
    perPage.value = result.data.per_page || 15

    const productList = [
      ...new Set(
        commentList
          .map(c => c.product?.product_name)
          .filter(Boolean)
      )
    ]
    products.value = productList

    if (!showLoadingIndicator) {
      showNotification('success', 'Đã cập nhật danh sách bình luận')
    }

  } catch (err) {
    console.error('Lỗi lấy danh sách bình luận:', err)
    const errorMessage = err.response?.data?.message || err.message || 'Không thể tải danh sách bình luận'
    error.value = errorMessage
    showNotification('error', errorMessage)
  } finally {
    if (showLoadingIndicator) loading.value = false
  }
}

const updateCommentInArrays = (id, updatedData) => {
  const commentIndex = comments.value.findIndex(c => c.id === id)
  if (commentIndex !== -1) {
    comments.value[commentIndex] = { ...comments.value[commentIndex], ...updatedData }
  }

  const filteredIndex = filteredComments.value.findIndex(c => c.id === id)
  if (filteredIndex !== -1) {
    filteredComments.value[filteredIndex] = { ...filteredComments.value[filteredIndex], ...updatedData }
  }
}

const approveComment = async (id) => {
  const originalComment = comments.value.find(c => c.id === id)
  if (!originalComment) return

  const originalStatus = originalComment.status
  updateCommentInArrays(id, { status: STATUS.VISIBLE })

  try {
    const response = await axios.patch(`${API_BASE_URL}/${id}/approve`)

    if (response.data.success && response.data.data) {
      updateCommentInArrays(id, response.data.data)
    }

    showNotification('success', 'Đã duyệt bình luận thành công')
  } catch (err) {
    updateCommentInArrays(id, { status: originalStatus })

    console.error('Lỗi duyệt bình luận:', err)
    const errorMessage = err.response?.data?.message || 'Không thể duyệt bình luận'
    showNotification('error', errorMessage)
  }
}

const hideComment = async (id) => {
  const originalComment = comments.value.find(c => c.id === id)
  if (!originalComment) return

  const originalStatus = originalComment.status
  updateCommentInArrays(id, { status: STATUS.HIDDEN })

  try {
    const response = await axios.patch(`${API_BASE_URL}/${id}/hide`)

    if (response.data.success && response.data.data) {
      updateCommentInArrays(id, response.data.data)
    }

    showNotification('success', 'Đã ẩn bình luận thành công')
  } catch (err) {
    updateCommentInArrays(id, { status: originalStatus })

    console.error('Lỗi ẩn bình luận:', err)
    const errorMessage = err.response?.data?.message || 'Không thể ẩn bình luận'
    showNotification('error', errorMessage)
  }
}

const filterComments = () => {
  if (!Array.isArray(comments.value)) {
    filteredComments.value = []
    return
  }

  filteredComments.value = comments.value.filter(c => {
    const search = searchTerm.value.toLowerCase().trim()
    const content = (c.comment_content || '').toLowerCase()
    const user = (c.user?.name_user || '').toLowerCase()
    const userEmail = (c.user?.email || '').toLowerCase()
    const product = c.product?.product_name

    const matchSearch = !search ||
      content.includes(search) ||
      user.includes(search) ||
      userEmail.includes(search)

    const matchProduct = !selectedProduct.value || product === selectedProduct.value
    const matchStatus = selectedStatus.value === '' || c.status === parseInt(selectedStatus.value)

    return matchSearch && matchProduct && matchStatus
  })
}

const handleSearch = () => {
  if (searchDebounceTimer) {
    clearTimeout(searchDebounceTimer)
  }

  searchDebounceTimer = setTimeout(() => {
    currentPage.value = 1
    filterComments()
  }, 300)
}

const handleFilter = () => {
  currentPage.value = 1
  filterComments()
}

const changePage = async (page) => {
  if (page === '...' || page < 1 || page > totalPages.value || page === currentPage.value) {
    return
  }

  currentPage.value = page
  await fetchComments(true)

  await nextTick()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'

  try {
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return 'N/A'

    return date.toLocaleString('vi-VN', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch (err) {
    console.error('Lỗi format date:', err)
    return 'N/A'
  }
}

const getStatusText = (status) => {
  const statusMap = {
    [STATUS.VISIBLE]: 'Đã duyệt',
    [STATUS.HIDDEN]: 'Đã ẩn',
    [STATUS.PENDING]: 'Chờ duyệt'
  }
  return statusMap[status] || 'Không xác định'
}

const getStatusClass = (status) => {
  const classMap = {
    [STATUS.VISIBLE]: 'status-approved',
    [STATUS.HIDDEN]: 'status-hidden',
    [STATUS.PENDING]: 'status-pending'
  }
  return classMap[status] || 'status-unknown'
}

const getStatusIcon = (status) => {
  const iconMap = {
    [STATUS.VISIBLE]: 'bi-check-circle',
    [STATUS.HIDDEN]: 'bi-eye-slash',
    [STATUS.PENDING]: 'bi-clock'
  }
  return iconMap[status] || 'bi-question-circle'
}

const canApprove = (status) => {
  return status === STATUS.PENDING || status === STATUS.HIDDEN
}

const canHide = (status) => {
  return status === STATUS.VISIBLE || status === STATUS.PENDING
}

const refreshData = async () => {
  showNotification('info', 'Đang làm mới dữ liệu...')
  await fetchComments(true)
  filterComments()
}

const clearFilters = () => {
  searchTerm.value = ''
  selectedProduct.value = ''
  selectedStatus.value = ''
  currentPage.value = 1
  filterComments()
  showNotification('info', 'Đã xóa tất cả bộ lọc')
}

const retryFetch = async () => {
  error.value = ''
  await fetchComments(true)
  filterComments()
}

watch([searchTerm, selectedProduct, selectedStatus], () => {
  if (searchDebounceTimer) {
    clearTimeout(searchDebounceTimer)
  }
  searchDebounceTimer = setTimeout(() => {
    filterComments()
  }, 300)
})

onMounted(async () => {
  try {
    await fetchComments(true)
    filterComments()
  } catch (err) {
    console.error('Lỗi khởi tạo component:', err)
  }
})

defineExpose({
  fetchComments,
  approveComment,
  hideComment,
  refreshData,
  clearFilters,
  retryFetch,
  showNotification,
  hideNotification,
  canApprove,
  canHide,
  STATUS
})
</script>


<style scoped>
.comments-page {
  padding: 24px;
  background: linear-gradient(135deg, #fff5e6 0%, #aaaaaa 100%);
  min-height: 100vh;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 16px 24px;
  border-radius: 12px;
  color: white;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 12px;
  z-index: 1000;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
  backdrop-filter: blur(10px);
}

.toast-success {
  background: rgba(16, 185, 129, 0.9);
}

.toast-error {
  background: rgba(239, 68, 68, 0.9);
}

.toast-warning {
  background: rgba(245, 158, 11, 0.9);
}

.toast-info {
  background: rgba(59, 130, 246, 0.9);
}

.toast-close {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.toast-close:hover {
  background: rgba(255, 255, 255, 0.2);
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

/* Header */
.page-header {
  /* background: rgba(0, 0, 0, 0.05); */
  background: white;
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 32px;
  margin-bottom: 24px;
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 24px;
}

.title-section {
  flex: 1;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #2d3748;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-title i {
  font-size: 2.2rem;
  color: #ff7900;
}

.page-subtitle {
  color: #4a5568;
  font-size: 1.1rem;
  margin: 0;
}

.header-stats {
  display: flex;
  gap: 16px;
}

.stat-card {
  /* background: rgba(0, 0, 0, 0.05); */
  background: white;
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 20px;
  text-align: center;
  border: 1px solid rgba(0, 0, 0, 0.1);
  min-width: 100px;
}

.stat-number {
  display: block;
  font-size: 2rem;
  font-weight: 700;
  color: #2d3748;
  line-height: 1;
}

.stat-label {
  display: block;
  color: #4a5568;
  font-size: 0.9rem;
  margin-top: 4px;
}

/* Filters */
.filters-section {
  display: flex;
  gap: 24px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.search-group {
  flex: 1;
  min-width: 300px;
}

.search-wrapper {
  position: relative;
}

.search-wrapper i {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #718096;
  font-size: 1.1rem;
}

.search-input {
  width: 100%;
  padding: 16px 16px 16px 48px;
  border: none;
  border-radius: 12px;
  background: rgba(0, 0, 0, 0.05);
  backdrop-filter: blur(10px);
  color: #2d3748;
  font-size: 1rem;
  border: 1px solid rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.search-input::placeholder {
  color: #718096;
}

.search-input:focus {
  outline: none;
  background: rgba(0, 0, 0, 0.08);
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.filter-group {
  min-width: 250px;
}

.filter-group label {
  display: block;
  color: #2d3748;
  font-weight: 500;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-select {
  width: 100%;
  padding: 16px;
  border: none;
  border-radius: 12px;
  background: rgba(0, 0, 0, 0.05);
  backdrop-filter: blur(10px);
  color: #2d3748;
  font-size: 1rem;
  border: 1px solid rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.filter-select:focus {
  outline: none;
  background: rgba(0, 0, 0, 0.08);
  border-color: #ff7900;
}

.filter-select option {
  background: white;
  color: #2d3748;
}

/* Loading & Error States */
.loading-state,
.error-state {
  text-align: center;
  padding: 80px 20px;
  color: #2d3748;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-top: 4px solid #ff7900;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.error-state i {
  font-size: 3rem;
  margin-bottom: 16px;
  color: #ef4444;
}

/* Comments Grid */
.comments-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 24px;
  margin-bottom: 32px;
}

.comment-card {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 24px;
  border: 1px solid rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.comment-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  background: rgba(255, 255, 255, 0.9);
}

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff7900 0%, #ff9f40 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 1.2rem;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.user-details {
  flex: 1;
}

.user-name {
  color: #2d3748;
  font-weight: 600;
  font-size: 1rem;
  margin-bottom: 4px;
}

.comment-date {
  color: #718096;
  font-size: 0.875rem;
}

.comment-status {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 6px;
}

.comment-status.status-approved {
  background: rgba(34, 197, 94, 0.2);
  color: #22c55e;
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.comment-status.status-pending {
  background: rgba(255, 121, 0, 0.2);
  color: #ff7900;
  border: 1px solid rgba(255, 121, 0, 0.3);
}

.comment-status.status-hidden {
  background: rgba(239, 68, 68, 0.2);
  color: #ef4444;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.comment-body {
  margin-bottom: 20px;
}

.product-info {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #4a5568;
  font-size: 0.9rem;
  margin-bottom: 12px;
}

.product-info i {
  color: #718096;
}

.comment-content {
  color: #2d3748;
  line-height: 1.6;
  font-size: 1rem;
  padding: 16px;
  background: rgba(0, 0, 0, 0.03);
  border-radius: 12px;
  border-left: 4px solid #ff7900;
}

.comment-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.action-btn {
  padding: 10px 16px;
  border: none;
  border-radius: 10px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  backdrop-filter: blur(10px);
}

.approve-btn {
  background: rgba(34, 197, 94, 0.2);
  color: #22c55e;
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.approve-btn:hover {
  background: rgba(34, 197, 94, 0.3);
  transform: translateY(-2px);
}

.hide-btn {
  background: rgba(255, 121, 0, 0.2);
  color: #ff7900;
  border: 1px solid rgba(255, 121, 0, 0.3);
}

.hide-btn:hover {
  background: rgba(255, 121, 0, 0.3);
  transform: translateY(-2px);
}

.show-btn {
  background: rgba(59, 130, 246, 0.2);
  color: #3b82f6;
  border: 1px solid rgba(59, 130, 246, 0.3);
}

.show-btn:hover {
  background: rgba(59, 130, 246, 0.3);
  transform: translateY(-2px);
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: #2d3748;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 24px;
  opacity: 0.7;
  color: #718096;
}

.empty-state h3 {
  font-size: 1.5rem;
  margin-bottom: 12px;
  font-weight: 600;
  color: #2d3748;
}

.empty-state p {
  color: #4a5568;
  font-size: 1.1rem;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 32px;
}

.page-btn {
  padding: 12px 16px;
  border: none;
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  color: #2d3748;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.page-btn:hover:not(:disabled) {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.page-btn.active {
  background: #ff7900;
  color: white;
  border-color: #ff7900;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
  .comments-page {
    padding: 16px;
  }

  .header-content {
    flex-direction: column;
    align-items: flex-start;
  }

  .header-stats {
    width: 100%;
    justify-content: center;
  }

  .filters-section {
    flex-direction: column;
  }

  .search-group,
  .filter-group {
    min-width: unset;
  }

  .comments-grid {
    grid-template-columns: 1fr;
  }

  .page-title {
    font-size: 2rem;
  }

  .comment-actions {
    justify-content: center;
  }

 
}
</style>

<style scoped>
.clear-filters-btn {
  padding: 10px 16px;
  border: none;
  border-radius: 10px;
  background: rgba(239, 68, 68, 0.2); 
  color: #ef4444;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.clear-filters-btn:hover {
  background: rgba(239, 68, 68, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.clear-filters-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.refresh-btn {
  padding: 10px 16px;
  border: none;
  border-radius: 10px;
  background: rgba(59, 130, 246, 0.2); 
  color: #3b82f6;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(59, 130, 246, 0.3);
}

.refresh-btn:hover {
  background: rgba(59, 130, 246, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.refresh-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.retry-btn {
  padding: 10px 16px;
  border: none;
  border-radius: 10px;
  background: rgba(255, 121, 0, 0.2); 
  color: #ff7900;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 121, 0, 0.3);
}

.retry-btn:hover {
  background: rgba(255, 121, 0, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.retry-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}
</style>