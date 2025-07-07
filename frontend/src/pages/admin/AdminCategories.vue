<template>
  <div class="categories-page">
    <!-- Page Header -->
    <div class="page-header">
      <h2>Quản Lí Danh Mục</h2>
    </div>

    <!-- Add/Edit Form Panel -->
    <div class="form-panel" v-if="showForm">
      <div class="form-container">
        <div class="form-header">
          <h3>{{ isEditing ? 'Cập Nhật Danh Mục' : 'Thêm Danh Mục Mới' }}</h3>
          <button class="btn-close" @click="closeForm">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <form @submit.prevent="saveCategory" class="category-form">
          <div class="form-row">
            <div class="form-group">
              <label for="categoryName">Tên Danh Mục <span class="required">*</span></label>
              <input id="categoryName" ref="categoryNameInput" v-model="form.name" type="text" class="form-control"
                :class="{ 'error': errors.name }" placeholder="Nhập tên danh mục..." required />
              <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
            </div>

            <div class="form-group">
              <label for="categoryStatus">Trạng Thái <span class="required">*</span></label>
              <select id="categoryStatus" v-model.number="form.status" class="form-control" required>
                <option :value="0">Hiển thị</option>
                <option :value="1">Ẩn</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="categoryDesc">Mô Tả</label>
            <textarea id="categoryDesc" v-model="form.description" class="form-control"
              placeholder="Nhập mô tả danh mục..." rows="3"></textarea>
          </div>

          <div class="form-actions">
            <button type="button" class="btn btn-secondary" @click="closeForm">
              <i class="bi bi-x-circle"></i> Hủy
            </button>
            <button type="submit" class="btn btn-primary" :disabled="saving">
              <i v-if="saving" class="bi bi-arrow-repeat spin"></i>
              <i v-else-if="isEditing" class="bi bi-check-circle"></i>
              <i v-else class="bi bi-plus-circle"></i>
              <span v-if="saving">Đang lưu...</span>
              <span v-else>{{ isEditing ? 'Cập Nhật' : 'Thêm Mới' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="search-box">
        <i class="bi bi-search search-icon"></i>
        <input v-model="search" type="text" placeholder="Tìm kiếm danh mục..." class="search-input" />
      </div>
      <div class="filter-group">
        <label for="statusFilter">Lọc Theo Trạng Thái:</label>
        <select v-model="statusFilter" id="statusFilter" class="filter-select">
          <option value="">-- Tất cả --</option>
          <option :value="0">Hiển thị</option>
          <option :value="1">Ẩn</option>
        </select>
      </div>
      <div class="actions">
        <button class="btn btn-primary" @click="openAddForm">
          <i class="bi bi-plus"></i> Thêm Danh Mục
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="table-wrapper">
      <div v-if="loading" class="loading">
        <div class="loading-spinner"></div>
        <span>Đang tải dữ liệu...</span>
      </div>
      <table v-else class="data-table">
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên Danh Mục</th>
            <th>Mô Tả</th>
            <th>Trạng Thái</th>
            <th>Ngày Tạo</th>
            <th>Hành Động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredCategories.length === 0">
            <td colspan="6" class="no-data">
              <i class="bi bi-inbox"></i>
              <span>{{ search || statusFilter ? 'Không tìm thấy dữ liệu' : 'Chưa có danh mục nào' }}</span>
            </td>
          </tr>
          <tr v-for="(category, index) in paginatedCategories" :key="category.id" class="table-row">
            <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
            <td class="category-name">
              <strong>{{ category.name }}</strong>
            </td>
            <td class="category-desc">{{ category.description || '---' }}</td>
            <td>
              <span :class="['status-badge', category.status === 0 ? 'active' : 'inactive']">
                <i :class="['bi', category.status === 0 ? 'bi-eye' : 'bi-eye-slash']"></i>
                {{ category.status_text }}
              </span>
            </td>
            <td>{{ formatDate(category.created_at) }}</td>
            <td class="actions-cell">
              <button class="btn btn-sm btn-warning" @click="editCategory(category)" title="Sửa">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button 
                :class="['btn', 'btn-sm', category.status === 0 ? 'btn-secondary' : 'btn-success']" 
                @click="toggleCategoryStatus(category)" 
                :title="category.status === 0 ? 'Ẩn danh mục' : 'Hiện danh mục'"
              >
                <i :class="['bi', category.status === 0 ? 'bi-eye-slash' : 'bi-eye']"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper" v-if="totalPages > 1">
      <div class="pagination-info">
        Hiển thị {{ (currentPage - 1) * itemsPerPage + 1 }} -
        {{ Math.min(currentPage * itemsPerPage, filteredCategories.length) }}
        trong tổng số {{ filteredCategories.length }} bản ghi
      </div>
      <div class="pagination">
        <button class="btn btn-sm" @click="changePage(1)" :disabled="currentPage === 1">
          <i class="bi bi-chevron-double-left"></i>
        </button>
        <button class="btn btn-sm" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">
          <i class="bi bi-chevron-left"></i>
        </button>
        <span class="page-info">Trang {{ currentPage }} / {{ totalPages }}</span>
        <button class="btn btn-sm" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">
          <i class="bi bi-chevron-right"></i>
        </button>
        <button class="btn btn-sm" @click="changePage(totalPages)" :disabled="currentPage === totalPages">
          <i class="bi bi-chevron-double-right"></i>
        </button>
      </div>
    </div>

    <!-- Toast Notification -->
    <transition name="toast">
      <div v-if="notification.show" :class="['toast-notification', `toast-${notification.type}`]">
        <i :class="['bi', getNotificationIcon(notification.type)]"></i>
        <span>{{ notification.message }}</span>
        <button @click="closeNotification" class="toast-close">
          <i class="bi bi-x"></i>
        </button>
      </div>
    </transition>

    <!-- Confirmation Modal for Status Toggle -->
    <div v-if="showStatusModal" class="modal-overlay" @click.self="showStatusModal = false">
      <div class="modal-content modal-confirm">
        <div class="modal-header">
          <h3><i class="bi bi-question-circle"></i> Xác nhận thay đổi trạng thái</h3>
          <button class="modal-close" @click="showStatusModal = false"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
          <p v-if="statusChangeCategory">
            Bạn có chắc chắn muốn {{ statusChangeCategory.status === 0 ? 'ẩn' : 'hiện' }} danh mục 
            <strong>"{{ statusChangeCategory.name }}"</strong> không?
          </p>
        </div>
        <div class="modal-footer">
          <button class="modal-btn modal-btn-secondary" @click="showStatusModal = false">
            <i class="bi bi-x-lg"></i> Hủy
          </button>
          <button class="modal-btn modal-btn-primary" @click="confirmStatusChange">
            <i :class="['bi', statusChangeCategory && statusChangeCategory.status === 0 ? 'bi-eye-slash' : 'bi-eye']"></i> 
            {{ statusChangeCategory && statusChangeCategory.status === 0 ? 'Ẩn' : 'Hiện' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AdminCategories',
  data() {
    return {
      categories: [],
      form: {
        name: '',
        description: '',
        status: 0,
      },
      errors: {},
      isEditing: false,
      editId: null,
      showForm: false,
      loading: false,
      saving: false,
      currentPage: 1,
      itemsPerPage: 10,
      search: '',
      statusFilter: '',
      notification: {
        show: false,
        message: '',
        type: 'info'
      },
      showStatusModal: false,
      statusChangeCategory: null
    };
  },
  computed: {
    filteredCategories() {
      return this.categories.filter((cat) => {
        const matchesSearch =
          cat.name.toLowerCase().includes(this.search.toLowerCase()) ||
          (cat.description && cat.description.toLowerCase().includes(this.search.toLowerCase()));
        const matchesStatus = this.statusFilter === '' || cat.status === Number(this.statusFilter);
        return matchesSearch && matchesStatus;
      });
    },
    paginatedCategories() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredCategories.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredCategories.length / this.itemsPerPage);
    },
  },
  watch: {
    search() {
      this.currentPage = 1;
    },
    statusFilter() {
      this.currentPage = 1;
    },
  },
  methods: {
    async fetchCategories() {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost:8000/api/categories');
        this.categories = (response.data.data || []).map((cat) => ({
          id: cat.id,
          name: cat.category_name,
          description: cat.description,
          status: cat.status,
          created_at: cat.created_at,
          status_text: cat.status === 0 ? 'Hiển thị' : 'Ẩn'
        }));
      } catch (error) {
        console.error('Lỗi khi tải danh mục:', error);
        this.showNotification('Có lỗi khi tải danh mục!', 'error');
      } finally {
        this.loading = false;
      }
    },

    validateForm() {
      this.errors = {};
      if (!this.form.name.trim()) {
        this.errors.name = 'Vui lòng nhập tên danh mục';
      }
      return Object.keys(this.errors).length === 0;
    },

    async saveCategory() {
      if (!this.validateForm()) return;

      this.saving = true;
      const payload = {
        category_name: this.form.name,
        description: this.form.description,
        status: this.form.status,
      };

      try {
        const method = this.isEditing ? 'put' : 'post';
        const url = this.isEditing
          ? `http://localhost:8000/api/categories/${this.editId}`
          : 'http://localhost:8000/api/categories';
        await axios[method](url, payload, {
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        });
        await this.fetchCategories();
        this.showNotification(
          this.isEditing ? 'Cập nhật danh mục thành công!' : 'Thêm danh mục thành công!',
          'success'
        );
        this.closeForm();
      } catch (error) {
        console.error('Lỗi khi lưu danh mục:', error.response?.data || error.message);
        this.showNotification('Có lỗi xảy ra, vui lòng thử lại!', 'error');
      } finally {
        this.saving = false;
      }
    },

    openAddForm() {
      this.resetForm();
      this.isEditing = false;
      this.showForm = true;
      this.$nextTick(() => {
        if (this.$refs.categoryNameInput) {
          this.$refs.categoryNameInput.focus();
        }
      });
    },

    editCategory(category) {
      this.form = {
        name: category.name,
        description: category.description,
        status: category.status,
      };
      this.editId = category.id;
      this.isEditing = true;
      this.showForm = true;
      this.errors = {};
      this.$nextTick(() => {
        if (this.$refs.categoryNameInput) {
          this.$refs.categoryNameInput.focus();
        }
      });
    },

    // Thay thế chức năng xóa bằng ẩn/hiện
    toggleCategoryStatus(category) {
      this.statusChangeCategory = category;
      this.showStatusModal = true;
    },

    async confirmStatusChange() {
      if (!this.statusChangeCategory) return;

      const newStatus = this.statusChangeCategory.status === 0 ? 1 : 0;
      const payload = {
        category_name: this.statusChangeCategory.name,
        description: this.statusChangeCategory.description,
        status: newStatus,
      };

      try {
        await axios.put(`http://localhost:8000/api/categories/${this.statusChangeCategory.id}`, payload, {
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        });
        
        await this.fetchCategories();
        this.showNotification(
          `${newStatus === 1 ? 'Ẩn' : 'Hiện'} danh mục thành công!`,
          'success'
        );
      } catch (error) {
        console.error('Lỗi khi thay đổi trạng thái danh mục:', error);
        this.showNotification('Có lỗi khi thay đổi trạng thái danh mục!', 'error');
      } finally {
        this.showStatusModal = false;
        this.statusChangeCategory = null;
      }
    },

    closeForm() {
      this.showForm = false;
      this.resetForm();
    },

    resetForm() {
      this.form = { name: '', description: '', status: 0 };
      this.isEditing = false;
      this.editId = null;
      this.errors = {};
    },

    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },

    formatDate(dateString) {
      if (!dateString) return '---';
      const date = new Date(dateString);
      return date.toLocaleDateString('vi-VN');
    },

    showNotification(message, type = 'info') {
      this.notification = {
        show: true,
        message,
        type
      };
      setTimeout(() => {
        this.closeNotification();
      }, 5000);
    },

    closeNotification() {
      this.notification.show = false;
    },

    getNotificationIcon(type) {
      switch (type) {
        case 'success': return 'bi-check-circle';
        case 'error': return 'bi-exclamation-circle';
        case 'warning': return 'bi-exclamation-triangle';
        default: return 'bi-info-circle';
      }
    }
  },

  mounted() {
    this.fetchCategories();
  },
};
</script>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
.categories-page {
  padding: 24px;
  background: linear-gradient(135deg, #fff5e6 0%, #aaaaaa 100%);
  min-height: 100vh;
  position: relative;
}

.categories-page::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff7900' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
  z-index: 0;
}
/* Đảm bảo các phần tử con không bị che bởi background */
.categories-page > * {
  position: relative;
  z-index: 1;
}
/* Form Panel Styles */
.form-panel {
  position: fixed;
  top: 0;
  right: -600px;
  width: 600px;
  height: 100vh;
  background: white;
  box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  transition: right 0.3s ease;
  overflow-y: auto;
}

.form-panel.show {
  right: 0;
}

.form-container {
  padding: 30px;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #ff7900;
}

.form-header h3 {
  margin: 0;
  color: #ff7900;
  font-size: 24px;
  font-weight: 600;
}

.btn-close {
  background: #fff5e6;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ff7900;
  transition: all 0.3s ease;
}

.btn-close:hover {
  background: #ff7900;
  color: white;
}

.category-form {
  flex: 1;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #333;
  font-weight: 600;
  font-size: 14px;
}

.required {
  color: #dc3545;
}

.form-control {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 14px;
  box-sizing: border-box;
  transition: all 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.form-control.error {
  border-color: #dc3545;
}

.error-message {
  color: #dc3545;
  font-size: 12px;
  margin-top: 6px;
  display: block;
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  padding-top: 30px;
  border-top: 2px solid #fff5e6;
  margin-top: auto;
}

/* Page Header */
.page-header {
  background: white;
  text-align: center;
  padding: 25px 30px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 25px;
  border-top: 4px solid #ff7900;
}

.page-header h2 {
  margin: 0;
  color: #ff7900;
  font-size: 28px;
  font-weight: 700;
}

/* Toolbar */
.toolbar {
  background: white;
  padding: 20px 25px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 25px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 15px;
}

.search-box {
  position: relative;
  flex: 1;
  min-width: 250px;
  max-width: 400px;
}

.search-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #ff7900;
  z-index: 1;
}

.search-input {
  width: 100%;
  padding: 12px 16px 12px 45px;
  border: 2px solid #e9ecef;
  border-radius: 25px;
  font-size: 14px;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-group label {
  font-size: 14px;
  color: #555;
  font-weight: 500;
  white-space: nowrap;
}

.filter-select {
  padding: 12px 16px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 14px;
  min-width: 140px;
  transition: all 0.3s ease;
}

.filter-select:focus {
  outline: none;
  border-color: #ff7900;
}

/* Table */
.table-wrapper {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 25px;
}

.loading {
  text-align: center;
  padding: 60px;
  color: #666;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #ff7900;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 16px 20px;
  text-align: left;
  border-bottom: 1px solid #f0f0f0;
}

.data-table th {
  background: linear-gradient(135deg, #ff7900 0%, #e6690a 100%);
  font-weight: 700;
  color: white;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.table-row {
  transition: background-color 0.2s ease;
}

.table-row:hover {
  background: #fff5e6;
}

.category-name strong {
  color: #ff7900;
  font-size: 15px;
}

.category-desc {
  max-width: 250px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  color: #666;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-badge.active {
  background: #d4edda;
  color: #155724;
}

.status-badge.inactive {
  background: #f8d7da;
  color: #721c24;
}

.actions-cell {
  white-space: nowrap;
}

.no-data {
  text-align: center;
  padding: 60px;
  color: #666;
  font-style: italic;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.no-data i {
  font-size: 48px;
  color: #ff7900;
  opacity: 0.5;
}

/* Pagination */
.pagination-wrapper {
  background: white;
  padding: 20px 25px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
}

.pagination-info {
  color: #666;
  font-size: 14px;
  font-weight: 500;
}

.pagination {
  display: flex;
  align-items: center;
  gap: 8px;
}

.page-info {
  margin: 0 15px;
  font-size: 14px;
  color: #ff7900;
  font-weight: 600;
}

/* Buttons */
.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: linear-gradient(135deg, #ff7900 0%, #e6690a 100%);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(255, 121, 0, 0.3);
  background: linear-gradient(135deg, #e6690a 0%, #cc5a00 100%);
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background: #545b62;
  transform: translateY(-1px);
}

.btn-warning {
  background: linear-gradient(135deg, #ff7900 0%, #ffa500 100%);
  color: white;
}

.btn-warning:hover:not(:disabled) {
  background: linear-gradient(135deg, #e6690a 0%, #e6940a 100%);
  transform: translateY(-1px);
}

.btn-danger {
  background: #dc3545;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background: #c82333;
  transform: translateY(-1px);
}

.btn-sm {
  padding: 8px 12px;
  font-size: 12px;
}

/* Toast Notification */
.toast-notification {
  position: fixed;
  top: 30px;
  right: 30px;
  background: white;
  padding: 16px 20px;
  border-radius: 8px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  gap: 12px;
  z-index: 2000;
  min-width: 300px;
  border-left: 4px solid;
}

.toast-success {
  border-left-color: #28a745;
}

.toast-error {
  border-left-color: #dc3545;
}

.toast-warning {
  border-left-color: #ff7900;
}

.toast-info {
  border-left-color: #ff7900;
}

.toast-close {
  background: none;
  border: none;
  cursor: pointer;
  color: #666;
  margin-left: auto;
  padding: 4px;
}

.toast-close:hover {
  color: #333;
}

/* Animations */
.spin {
  animation: spin 1s linear infinite;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

/* Show form panel */
.form-panel {
  right: -600px;
  transition: right 0.3s ease;
}

.categories-page:has(.form-panel) .form-panel {
  right: 0;
}

/* Alternative way to show form panel */
.form-panel.active {
  right: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .categories-page {
    padding: 15px;
  }

  .form-panel {
    width: 100%;
    right: -100%;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .toolbar {
    flex-direction: column;
    align-items: stretch;
  }

  .search-box {
    max-width: none;
  }

  .pagination-wrapper {
    flex-direction: column;
    text-align: center;
  }

  .data-table {
    font-size: 12px;
  }

  .category-desc {
    max-width: 120px;
  }
}
</style>
<style scoped>
  /* Toast Notification Styles */
.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background: white;
  border-radius: 8px;
  padding: 16px 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 300px;
  max-width: 400px;
  z-index: 1000;
  border-left: 4px solid;
  font-size: 14px;
}

/* Toast Types */
.toast-success {
  border-left-color: #10b981;
  background: #f0fdf4;
}

.toast-success i:first-child {
  color: #10b981;
}

.toast-error {
  border-left-color: #ef4444;
  background: #fef2f2;
}

.toast-error i:first-child {
  color: #ef4444;
}

.toast-warning {
  border-left-color: #ff7900;
  background: #fff5e6;
}

.toast-warning i:first-child {
  color: #ff7900;
}

.toast-info {
  border-left-color: #ff7900;
  background: #fff5e6;
}

.toast-info i:first-child {
  color: #ff7900;
}

/* Toast Content */
.toast-notification span {
  flex: 1;
  color: #374151;
  font-weight: 500;
}

.toast-notification i:first-child {
  font-size: 18px;
}

/* Toast Close Button */
.toast-close {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  color: #6b7280;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toast-close:hover {
  background: rgba(255, 121, 0, 0.1);
  color: #ff7900;
}

.toast-close i {
  font-size: 16px;
}

/* Toast Transition */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1001;
  backdrop-filter: blur(2px);
}

/* Modal Content */
.modal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow: hidden;
  animation: modalSlideIn 0.3s ease-out;
  border-top: 4px solid #ff7900;
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Modal Header */
.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
  background: linear-gradient(135deg, #fff5e6 0%, #ffffff 100%);
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  display: flex;
  align-items: center;
  color: #ff7900;
}
.modal-header i {
  margin-right: 8px;
  color: #ff7900;
}
.modal-close {
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
  border-radius: 6px;
  color: #ff7900;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  background: rgba(255, 121, 0, 0.1);
  color: #e6690a;
}

.modal-close i {
  font-size: 18px;
}

/* Modal Body */
.modal-body {
  padding: 24px;
}

.modal-body p {
  margin: 0;
  color: #6b7280;
  font-size: 16px;
  line-height: 1.5;
}

/* Modal Footer */
.modal-footer {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding: 20px 24px;
  border-top: 1px solid #e5e7eb;
  background: #fff5e6;
}

/* Modal Button Styles */
.modal-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: 1px solid transparent;
  text-decoration: none;
}

.modal-btn-secondary {
  background: white;
  color: #6b7280;
  border-color: #d1d5db;
}

.modal-btn-secondary:hover {
  background: #f9fafb;
  border-color: #9ca3af;
  color: #374151;
}

.modal-btn-primary {
  background: #ff7900;
  color: white;
  border-color: #ff7900;
}

.modal-btn-primary:hover {
  background: #e6690a;
  border-color: #e6690a;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(255, 121, 0, 0.2);
}

.modal-btn i {
  font-size: 14px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.spin {
  animation: spin 1s linear infinite;
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
/* Responsive */
@media (max-width: 640px) {
  .toast-notification {
    left: 20px;
    right: 20px;
    min-width: auto;
    max-width: none;
  }
  
  .modal-content {
    margin: 20px;
    width: calc(100% - 40px);
  }
  
  .modal-footer {
    flex-direction: column-reverse;
  }
  
  .modal-footer .modal-btn {
    width: 100%;
    justify-content: center;
  }
}
</style>