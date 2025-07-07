<template>
  <div class="vouchers-page">
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

    <!-- Header -->
    <div class="page-header">
      <div class="header-content">
        <div class="title-section">
          <h1 class="page-title">
            <i class="bi bi-ticket-perforated"></i>
            Quản Lý Voucher
          </h1>
          <p class="page-subtitle">Quản lý và theo dõi các mã giảm giá</p>
        </div>
        <button class="btn-add" @click="openAddModal">
          <i class="bi bi-plus-lg"></i>
          <span>Thêm Voucher</span>
        </button>
      </div>
    </div>

    <!-- Vouchers Grid -->
    <div class="vouchers-grid">
      <div v-for="voucher in vouchers" :key="voucher.id" class="voucher-card">
        <div class="voucher-header">
          <div class="voucher-icon">
            <i class="bi bi-tag-fill"></i>
          </div>
          <div class="voucher-status" :class="getStatusClass(voucher.status)">
            <i :class="getStatusIcon(voucher.status)"></i>
            {{ getStatusText(voucher.status) }}
          </div>
        </div>
        
        <div class="voucher-body">
          <h3 class="voucher-name">{{ voucher.voucher_name }}</h3>
          <div class="voucher-code">{{ voucher.code }}</div>
          <div class="voucher-details">
            <div class="detail-item">
              <span class="label">Loại giảm:</span>
              <span class="value">{{ getDiscountTypeText(voucher.discount_type) }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Giá trị:</span>
              <span class="value">{{ formatDiscountValue(voucher) }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Đơn tối thiểu:</span>
              <span class="value">{{ formatCurrency(voucher.min_order_amount) }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Giảm tối đa:</span>
              <span class="value">{{ formatCurrency(voucher.max_discount_amount) }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Số lượng:</span>
              <span class="value">{{ voucher.quantity }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Thời hạn:</span>
              <span class="value">{{ formatDateRange(voucher.start_date, voucher.end_date) }}</span>
            </div>
          </div>
          <div v-if="voucher.description" class="voucher-description">{{ voucher.description }}</div>
        </div>
        
        <div class="voucher-actions">
          <button class="action-btn edit-btn" @click="editVoucher(voucher)">
            <i class="bi bi-pencil"></i>
            <span>Sửa</span>
          </button>
          <button class="action-btn delete-btn" @click="deleteVoucher(voucher.id)">
            <i class="bi bi-trash"></i>
            <span>Xóa</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="vouchers.length === 0" class="empty-state">
      <div class="empty-icon">
        <i class="bi bi-ticket-perforated"></i>
      </div>
      <h3>Chưa có voucher nào</h3>
      <p>Bắt đầu tạo voucher đầu tiên để quản lý khuyến mãi</p>
      <button class="btn-add" @click="openAddModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tạo Voucher Đầu Tiên</span>
      </button>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>
            <i class="bi bi-ticket-perforated"></i>
            {{ isEditing ? 'Chỉnh sửa Voucher' : 'Tạo Voucher Mới' }}
          </h3>
          <button class="modal-close" @click="closeModal">
            <i class="bi bi-x"></i>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label for="voucher_name">
                <i class="bi bi-tag"></i>
                Tên Voucher
              </label>
              <input 
                id="voucher_name"
                v-model="form.voucher_name" 
                type="text"
                placeholder="Nhập tên voucher..."
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="code">
                <i class="bi bi-qr-code"></i>
                Mã Voucher
              </label>
              <input 
                id="code"
                v-model="form.code" 
                type="text"
                placeholder="Nhập mã voucher..."
                class="form-input"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="discount_type">
                <i class="bi bi-percent"></i>
                Loại Giảm Giá
              </label>
              <select id="discount_type" v-model="form.discount_type" class="form-select">
                <option value="percentage">Phần trăm (%)</option>
                <option value="fixed">Cố định (VND)</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="discount_value">
                <i class="bi bi-currency-dollar"></i>
                Giá Trị Giảm
              </label>
              <input 
                id="discount_value"
                v-model.number="form.discount_value" 
                type="number"
                :placeholder="form.discount_type === 'percentage' ? 'Nhập % giảm...' : 'Nhập số tiền...'"
                class="form-input"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="min_order_amount">
                <i class="bi bi-cart-check"></i>
                Đơn Hàng Tối Thiểu
              </label>
              <input 
                id="min_order_amount"
                v-model.number="form.min_order_amount" 
                type="number"
                placeholder="Nhập giá trị đơn hàng tối thiểu..."
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="max_discount_amount">
                <i class="bi bi-cash-stack"></i>
                Giảm Tối Đa
              </label>
              <input 
                id="max_discount_amount"
                v-model.number="form.max_discount_amount" 
                type="number"
                placeholder="Nhập số tiền giảm tối đa..."
                class="form-input"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="quantity">
                <i class="bi bi-box"></i>
                Số Lượng
              </label>
              <input 
                id="quantity"
                v-model.number="form.quantity" 
                type="number"
                placeholder="Nhập số lượng..."
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="status">
                <i class="bi bi-check-circle"></i>
                Trạng Thái
              </label>
              <select id="status" v-model="form.status" class="form-select">
                <option :value="1">Không hoạt động</option>
                <option :value="0">Hoạt động</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="start_date">
                <i class="bi bi-calendar-event"></i>
                Ngày Bắt Đầu
              </label>
              <input 
                id="start_date"
                v-model="form.start_date" 
                type="datetime-local"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="end_date">
                <i class="bi bi-calendar-x"></i>
                Ngày Kết Thúc
              </label>
              <input 
                id="end_date"
                v-model="form.end_date" 
                type="datetime-local"
                class="form-input"
              />
            </div>
          </div>
          
          <div class="form-group">
            <label for="description">
              <i class="bi bi-file-text"></i>
              Mô Tả
            </label>
            <textarea 
              id="description"
              v-model="form.description" 
              placeholder="Nhập mô tả voucher..."
              class="form-textarea"
              rows="3"
            ></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <button class="btn-secondary" @click="closeModal">
            <i class="bi bi-x-lg"></i>
            <span>Hủy</span>
          </button>
          <button class="btn-primary" @click="submitForm">
            <i class="bi bi-check-lg"></i>
            <span>{{ isEditing ? 'Cập nhật' : 'Tạo mới' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'AdminVouchers',
  data() {
    return {
      vouchers: [],
      showModal: false,
      isEditing: false,
      form: {
        id: null,
        voucher_name: '',
        code: '',
        discount_type: 'percentage',
        discount_value: 0,
        max_discount_amount: 0,
        min_order_amount: 0,
        start_date: '',
        end_date: '',
        quantity: 1,
        status: 0, // mặc định là còn hạn
        description: ''
      },
      notification: {
        show: false,
        message: '',
        type: 'success'
      }
    }
  },
  mounted() {
    this.fetchVouchers()
  },
  methods: {
    async fetchVouchers() {
      try {
        const res = await axios.get('http://localhost:8000/api/vouchers')
        this.vouchers = res.data.data || res.data
      } catch (e) {
        this.showNotification('Lỗi khi tải dữ liệu', 'error')
      }
    },

    openAddModal() {
      this.resetForm()
      this.showModal = true
    },

    editVoucher(voucher) {
      this.form = {
        ...voucher,
        start_date: this.formatDateTimeForInput(voucher.start_date),
        end_date: this.formatDateTimeForInput(voucher.end_date)
      }
      this.isEditing = true
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.isEditing = false
    },

    resetForm() {
      this.form = {
        id: null,
        voucher_name: '',
        code: '',
        discount_type: 'percentage',
        discount_value: 0,
        max_discount_amount: 0,
        min_order_amount: 0,
        start_date: '',
        end_date: '',
        quantity: 1,
        status: 0,
        description: ''
      }
    },

    async submitForm() {
      if (!this.form.voucher_name || !this.form.code) {
        this.showNotification('Vui lòng nhập đầy đủ thông tin bắt buộc', 'error')
        return
      }

      if (new Date(this.form.start_date) >= new Date(this.form.end_date)) {
        this.showNotification('Ngày kết thúc phải sau ngày bắt đầu', 'error')
        return
      }

      try {
        const formData = {
          voucher_name: this.form.voucher_name,
          code: this.form.code,
          discount_type: this.form.discount_type,
          discount_value: parseFloat(this.form.discount_value),
          max_discount_amount: parseFloat(this.form.max_discount_amount),
          min_order_amount: parseFloat(this.form.min_order_amount),
          quantity: parseInt(this.form.quantity),
          status: parseInt(this.form.status),
          description: this.form.description,
          start_date: this.formatDateTimeForAPI(this.form.start_date),
          end_date: this.formatDateTimeForAPI(this.form.end_date)
        }

        if (this.isEditing) {
          await axios.put(`http://localhost:8000/api/vouchers/${this.form.id}`, formData)
          this.showNotification('Cập nhật thành công', 'success')
        } else {
          await axios.post(`http://localhost:8000/api/vouchers`, formData)
          this.showNotification('Thêm mới thành công', 'success')
        }

        this.fetchVouchers()
        this.closeModal()
      } catch (e) {
        if (e.response?.data?.errors) {
          const firstError = Object.values(e.response.data.errors)[0][0]
          this.showNotification(firstError, 'error')
        } else {
          this.showNotification('Lỗi khi lưu dữ liệu', 'error')
        }
      }
    },

    async deleteVoucher(id) {
      if (!confirm('Bạn có chắc muốn xóa voucher này không?')) return

      try {
        await axios.delete(`http://localhost:8000/api/vouchers/${id}`)
        this.showNotification('Xóa thành công', 'success')
        this.fetchVouchers()
      } catch (e) {
        this.showNotification('Lỗi khi xóa', 'error')
      }
    },

    showNotification(message, type) {
      this.notification = { show: true, message, type }
      setTimeout(() => (this.notification.show = false), 3000)
    },

    closeNotification() {
      this.notification.show = false
    },

    getNotificationIcon(type) {
      switch (type) {
        case 'success': return 'bi-check-circle'
        case 'error': return 'bi-x-circle'
        default: return 'bi-info-circle'
      }
    },

    getStatusClass(status) {
      return status === 0 ? 'status-active' : 'status-inactive'
    },

    getStatusIcon(status) {
      return status === 0 ? 'bi-check-circle' : 'bi-x-circle'
    },

    getStatusText(status) {
      return status === 0 ? 'Hoạt động' : 'Không hoạt động'
    },

    getDiscountTypeText(type) {
      return type === 'percentage' ? 'Phần trăm' : 'Cố định'
    },

    formatDiscountValue(voucher) {
      return voucher.discount_type === 'percentage'
        ? `${voucher.discount_value}%`
        : this.formatCurrency(voucher.discount_value)
    },

    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(value)
    },

    formatDateRange(startDate, endDate) {
      const start = new Date(startDate).toLocaleDateString('vi-VN')
      const end = new Date(endDate).toLocaleDateString('vi-VN')
      return `${start} - ${end}`
    },

    formatDateTimeForInput(dateString) {
      if (!dateString) return ''
      const date = new Date(dateString)
      return date.toISOString().slice(0, 16)
    },

    formatDateTimeForAPI(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toISOString().slice(0, 19).replace('T', ' ')
    }
  }
}
</script>


<style scoped>
.vouchers-page {
  min-height: 100vh;
  /* background: linear-gradient(135deg, #ff7900 0%, #ffb366 100%); */
  background: linear-gradient(135deg, #fff5e6 0%, #aaaaaa 100%);
  padding: 2rem;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Toast Notification System */
.toast-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 2000; /* Tăng z-index để đảm bảo hiển thị trên các phần tử khác */
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  align-items: flex-end;
  width: auto; /* Đảm bảo container không chiếm toàn bộ chiều rộng */
  max-width: 400px; /* Giới hạn chiều rộng tối đa */
}

.toast-notification {
  background: white;
  padding: 1rem 1.5rem;
  border-radius: 0.75rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%; /* Sử dụng toàn bộ chiều rộng của container */
  min-width: 300px;
  max-width: 400px;
  border-left: 4px solid;
}

.toast-success {
  border-left-color: #10b981;
}

.toast-error {
  border-left-color: #ef4444;
}

.toast-warning {
  border-left-color: #f59e0b;
}

.toast-info {
  border-left-color: #3b82f6;
}

.toast-content {
  flex: 1;
}

.toast-title {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.25rem;
}

.toast-message {
  color: #6b7280;
  font-size: 0.875rem;
}

.toast-close {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: all 0.3s ease;
}

.toast-close:hover {
  background: #f3f4f6;
  color: #1f2937;
}

.toast-enter-active, .toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from, .toast-leave-to {
  opacity: 0;
  transform: translateX(100%); /* Trượt từ bên phải vào */
}

/* Header */
.page-header {
  /* background: rgba(255, 255, 255, 0.15); */
  background: white;
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 1rem;
  padding: 2rem;
  margin-bottom: 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-title {
  /* color: white; */
  color: #ff7900;
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.page-subtitle {
  color: #6b7280;
  margin: 0.5rem 0 0 0;
  font-size: 1.1rem;
}

.btn-add {
  background: linear-gradient(135deg, #ff7900 0%, #ff9500 100%);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(255, 121, 0, 0.3);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 121, 0, 0.4);
}

/* Vouchers Grid */
.vouchers-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.voucher-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 1rem;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.voucher-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.voucher-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.voucher-icon {
  width: 40px;
  height: 40px;
  /* background: linear-gradient(135deg, #f3f3f3 0%, #ff9500 100%); */
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgb(255, 124, 2);
  font-size: 1.2rem;
}

.voucher-status {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.875rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.status-active {
  background: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.status-inactive {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.voucher-name {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 0.5rem 0;
}

.voucher-code {
  background: linear-gradient(135deg, #ff7900, #ff9500);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-family: 'Monaco', 'Menlo', monospace;
  font-weight: 600;
  display: inline-block;
  margin-bottom: 1rem;
}

.voucher-details {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  background: rgba(0, 0, 0, 0.02);
  border-radius: 0.25rem;
}

.detail-item .label {
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}

.detail-item .value {
  font-weight: 600;
  color: #1f2937;
}

.voucher-description {
  background: rgba(0, 0, 0, 0.02);
  padding: 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.voucher-actions {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  flex: 1;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.5rem;
  font-weight: \-600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  font-size: 0.875rem;
}

.edit-btn {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.edit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.toggle-btn.show-btn {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
}

.toggle-btn.hide-btn {
  background: linear-gradient(135deg, #6b7280, #4b5563);
  color: white;
}

.toggle-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
}

.delete-btn {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
}

.delete-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 1rem;
  color: white;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
  opacity: 0.7;
}

.empty-state h3 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}

.empty-state p {
  opacity: 0.8;
  margin-bottom: 2rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 2rem;
}

.modal-content {
  background: white;
  border-radius: 1rem;
  width: 100%;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 25px 50xbc0;
}

.modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #1f2937;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #6b7280;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: all 0.3s ease;
}

.modal-close:hover {
  background: #f3f4f6;
  color: #1f2937;
}

.modal-body {
  padding: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
}

.form-input, .form-select, .form-textarea {
  padding: 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

.btn-secondary {
  background: #f3f4f6;
  color: #374151;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.btn-primary {
  background: linear-gradient(135deg, #ff7900, #ff9500);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 121, 0, 0.3);
}

/* Confirmation Modal */
.confirm-modal {
  background: white;
  border-radius: 1rem;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}

.confirm-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.confirm-header i {
  color: #f59e0b;
  font-size: 1.5rem;
}

.confirm-header h3 {
  margin: 0;
  color: #1f2937;
}

.confirm-body {
  padding: 1.5rem;
}

.confirm-body p {
  margin: 0 0 1rem 0;
  color: #374151;
  line-height: 1.5;
}

.confirm-note {
  font-size: 0.875rem;
  color: #6b7280;
  font-style: italic;
}

.confirm-footer {
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

.btn-danger {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.btn-danger:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
  .vouchers-page {
    padding: 1rem;
  }
  
  .vouchers-grid {
    grid-template-columns: 1fr;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .header-content {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .voucher-actions {
    flex-direction: column;
  }
  
  .toast-container {
    top: 1rem;
    right: 1rem;
    width: calc(100% - 2rem);
    max-width: 400px;
    align-items: flex-end;
  }
  
  .toast-notification {
    min-width: auto;
    width: 100%;
  }
}
</style>