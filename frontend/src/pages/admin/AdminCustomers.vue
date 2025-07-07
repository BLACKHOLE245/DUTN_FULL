<template>
  <div class="customers-page">
    <div v-if="showConfirmModal" class="modal-overlay" @click="closeModal">
      <div class="confirmation-modal" @click.stop>
        <div class="modal-header">
          <div class="modal-icon">
            <i :class="confirmData.icon"></i>
          </div>
          <h3>{{ confirmData.title }}</h3>
        </div>
        <div class="modal-body">
          <p>{{ confirmData.message }}</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-cancel" @click="closeModal">Hủy</button>
          <button class="btn btn-confirm" :class="confirmData.btnClass" @click="confirmAction">
            {{ confirmData.confirmText }}
          </button>
        </div>
      </div>
    </div>

    <div class="header">
      <h2>Quản Lí Khách Hàng</h2>
    </div>

    <div class="toolbar">
      <div class="search-box">
        <form @submit.prevent>
          <input v-model="search" type="text" placeholder="Tìm kiếm tên, email, số điện thoại..."
            class="search-input" />
        </form>
      </div>

      <div class="filter-group">
        <label>Trạng Thái:</label>
        <select v-model="statusFilter">
          <option value="">--Tất cả--</option>
          <option value="active">Hoạt Động</option>
          <option value="banned">Bị Khóa</option>
        </select>
      </div>

      <div class="filter-group">
        <label>Chức Vụ:</label>
        <select v-model="roleFilter">
          <option value="">--Tất cả--</option>
          <option value="1">Admin</option>
          <option value="2">Khách Hàng</option>
        </select>

      </div>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>Tên Người Dùng</th>
          <th>Email</th>
          <th>Số Điện thoại</th>
          <th>Địa Chỉ</th>
          <th>Trạng Thái</th>
          <th>Chức Vụ</th>
          <th>Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in paginatedUsers" :key="user.id">
          <td>{{ user.name_user }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone || '---' }}</td>
          <td>{{ user.address || '---' }}</td>
          <td :class="{ 'status-active': !user.is_banned, 'status-inactive': user.is_banned }">
            {{ user.is_banned ? 'Bị Khóa' : 'Hoạt Động' }}
          </td>
          <td>{{ user.role?.role || 'Admin' }}</td>
          <td class="actions-cell">
            <button class="btn btn-sm" :class="user.is_banned ? 'btn-success' : 'btn-secondary'"
              @click="toggleBanUser(user)" :title="user.is_banned ? 'Mở Khóa' : 'Khóa'"
              :disabled="isCurrentUser(user) || (user.role?.role === 'Admin' && user.role?.id === 1)">
              <i :class="user.is_banned ? 'bi bi-unlock' : 'bi bi-lock'"></i>
            </button>
          </td>
        </tr>
        <tr v-if="paginatedUsers.length === 0">
          <td colspan="7">Không tìm thấy kết quả phù hợp.</td>
        </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1">«</button>
      <span>Trang {{ currentPage }} / {{ totalPages }}</span>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">»</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import debounce from 'lodash/debounce';

export default {
  name: 'AdminCustomers',
  data() {
    return {
      users: [],
      search: '',
      statusFilter: '',
      roleFilter: '',
      currentPage: 1,
      itemsPerPage: 10,
      showConfirmModal: false,
      confirmData: {},
      pendingAction: null,
      currentUser: null
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user => {
        const searchText = this.search.toLowerCase();
        const matchesSearch =
          user.name_user?.toLowerCase().includes(searchText) ||
          user.email?.toLowerCase().includes(searchText) ||
          user.phone?.toLowerCase().includes(searchText);

        let matchesStatus = true;
        if (this.statusFilter === 'active') {
          matchesStatus = !user.is_banned;
        } else if (this.statusFilter === 'banned') {
          matchesStatus = user.is_banned;
        }

        const matchesRole = !this.roleFilter || String(user.role_id) === String(this.roleFilter);
        return matchesSearch && matchesStatus && matchesRole;
      });
    },
    paginatedUsers() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredUsers.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredUsers.length / this.itemsPerPage);
    }
  },
  watch: {
    search: debounce(function () {
      this.currentPage = 1;
    }, 300),
    statusFilter() {
      this.currentPage = 1;
    },
    roleFilter() {
      this.currentPage = 1;
    }
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('http://localhost:8000/api/users');
        this.users = response.data.data || [];
      } catch (error) {
        console.error('Lỗi khi tải danh sách người dùng:', error);
        this.showNotification('Có lỗi khi tải danh sách người dùng!', 'error');
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    async toggleBanUser(user) {
      if (this.isCurrentUser(user)) {
        this.showNotification('Bạn không thể tự khóa chính mình!', 'error');
        return;
      }
      if (user.role?.role === 'Admin' && user.role?.id === 1) {
        this.showNotification('Không thể khóa tài khoản Admin!', 'error');
        return;
      }

      const action = user.is_banned ? 'mở khóa' : 'khóa';
      const actionText = user.is_banned ? 'Mở Khóa' : 'Khóa';

      this.showConfirmation({
        title: `${actionText} Người Dùng`,
        message: `Bạn có chắc chắn muốn ${action} người dùng "${user.name_user}" không?`,
        icon: user.is_banned ? 'bi bi-unlock-fill text-success' : 'bi bi-lock-fill text-warning',
        confirmText: actionText,
        btnClass: user.is_banned ? 'btn-success' : 'btn-warning',
        action: () => this.executeToggleBan(user)
      });
    },

    async executeToggleBan(user) {
      try {
        const response = await axios.post(`http://127.0.0.1:8000/api/users/${user.id}/toggle-ban`);

        if (response.data.success) {
          const userIndex = this.users.findIndex(u => u.id === user.id);
          if (userIndex !== -1) {
            this.users[userIndex] = response.data.data;
          }

          const message = user.is_banned ? 'Mở khóa người dùng thành công!' : 'Khóa người dùng thành công!';
          this.showNotification(message, 'success');
        }
      } catch (error) {
        console.error('Lỗi khi thay đổi trạng thái người dùng:', error);
        this.showNotification('Có lỗi khi thay đổi trạng thái người dùng!', 'error');
      }
    },

    showConfirmation(data) {
      this.confirmData = data;
      this.showConfirmModal = true;
    },

    closeModal() {
      this.showConfirmModal = false;
      this.confirmData = {};
      this.pendingAction = null;
    },

    confirmAction() {
      if (this.confirmData.action) {
        this.confirmData.action();
      }
      this.closeModal();
    },
    isCurrentUser(user) {
      const currentUserId = localStorage.getItem('user_id') || sessionStorage.getItem('user_id');
      if (currentUserId) {
        return parseInt(currentUserId) === user.id;
      }
      if (this.currentUser) {
        return this.currentUser.id === user.id;
      }
      const currentUserEmail = localStorage.getItem('user_email');
      if (currentUserEmail) {
        return currentUserEmail === user.email;
      }

      return false;
    },
    async getCurrentUser() {
      try {
        const response = await axios.get('http://localhost:8000/api/user/profile', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.currentUser = response.data.data || response.data.user;
      } catch (error) {
        console.error('Lỗi khi lấy thông tin user hiện tại:', error);
        const userId = localStorage.getItem('user_id');
        const userEmail = localStorage.getItem('user_email');
        if (userId || userEmail) {
          this.currentUser = {
            id: parseInt(userId),
            email: userEmail
          };
        }
      }
    },
    async banUser(user) {
      if (this.isCurrentUser(user)) {
        this.showNotification('Bạn không thể tự khóa chính mình!', 'error');
        return;
      }
      if (user.role?.role === 'Admin' && user.role?.id === 1) {
        this.showNotification('Không thể khóa tài khoản Admin!', 'error');
        return;
      }

      this.showConfirmation({
        title: 'Khóa Người Dùng',
        message: `Bạn có chắc chắn muốn khóa người dùng "${user.name_user}" không?`,
        icon: 'bi bi-lock-fill text-warning',
        confirmText: 'Khóa',
        btnClass: 'btn-warning',
        action: () => this.executeBan(user)
      });
    },

    async executeBan(user) {
      try {
        const response = await axios.post(`http://127.0.0.1:8000/api/users/${user.id}/ban`);

        if (response.data.success) {
          const userIndex = this.users.findIndex(u => u.id === user.id);
          if (userIndex !== -1) {
            this.users[userIndex] = response.data.data;
          }

          this.showNotification('Khóa người dùng thành công!', 'success');
        }
      } catch (error) {
        console.error('Lỗi khi khóa người dùng:', error);
        this.showNotification('Có lỗi khi khóa người dùng!', 'error');
      }
    },
    showNotification(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast-message toast-${type}`;
      toast.textContent = message;

      document.body.appendChild(toast);

      // Kích hoạt hiệu ứng
      requestAnimationFrame(() => {
        toast.classList.add('show');
      });

      // Tự động ẩn
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
      }, 3000);
    }

  },
  mounted() {
    this.fetchUsers();
    this.getCurrentUser();
  }
};
</script>

<style scoped>
.customers-page {
  padding: 24px;
  background: linear-gradient(135deg, #fff5e6 0%, #aaaaaa 100%);
  min-height: 100vh;
  position: relative;
}

.customers-page::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
  z-index: 0;
}

.header {
  background: white;
  border: 1px solid #ddd;
  padding: 32px;
  border-radius: 20px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  margin-bottom: 24px;
  position: relative;
  z-index: 1;
  animation: slideInDown 0.6s ease-out;
}

.header h2 {
  margin: 0;
  color: #ff7900;
  font-size: 32px;
  font-weight: 700;
  text-align: center;
  letter-spacing: -0.5px;
  text-shadow: none;
}

.toolbar {
  background: white;
  border: 1px solid #ddd;
  padding: 20px 24px;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: nowrap;
  gap: 16px;
  position: relative;
  z-index: 1;
  animation: slideInUp 0.6s ease-out 0.1s both;
}

.search-box {
  flex: 0 1 280px;
  min-width: 200px;
  max-width: 280px;
}

.search-input {
  width: 100%;
  padding: 12px 20px;
  border: 2px solid rgba(54, 54, 54, 0.2);
  border-radius: 12px;
  font-size: 14px;
  background: rgba(255, 255, 255, 0);
  backdrop-filter: blur(10px);
  color: rgb(0, 0, 0);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-input::placeholder {
  color: #888;
}

.search-input:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.filter-group {
  flex: 0 1 180px;
  display: flex;
  align-items: center;
  gap: 8px;
  min-width: 140px;
}

.filter-group label {
  font-size: 14px;
  color: #686868;
  white-space: nowrap;
  font-weight: 500;
}

.filter-group select {
  padding: 10px 12px;
  border: 2px solid rgba(32, 32, 32, 0.2);
  border-radius: 10px;
  font-size: 14px;
  flex: 1;
  min-width: 100px;
  /* background: rgba(253, 253, 253, 0.596); */
  backdrop-filter: blur(10px);
  color: rgb(71, 71, 71);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.filter-group select:focus {
  outline: none;
  border-color: #ff7900;
  background: rgba(255, 255, 255, 0.15);
  box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
}

.filter-group select option {
  background: #ffffff;
  color: rgba(32, 32, 32, 0.849);
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  /* background: rgba(255, 255, 255, 0.15); */
  background: white;
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 16px;
  box-shadow:
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04);
  overflow: hidden;
  position: relative;
  z-index: 1;
  animation: slideInUp 0.6s ease-out 0.2s both;
}

.data-table th,
.data-table td {
  padding: 16px 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  text-align: left;
  transition: all 0.3s ease;
}

.data-table th {
  background: #ff7900;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.95);
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  position: relative;
}

.data-table th::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 20px;
  right: 20px;
  height: 2px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.116), transparent);
}

.data-table td {
  color: rgba(0, 0, 0, 0.9);
  font-size: 14px;
}

.data-table tbody tr {
  transition: all 0.3s ease;
}

.data-table tbody tr:hover {
  background: rgba(255, 255, 255, 0.05);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.status-active {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(34, 197, 94, 0.1);
  border: 1px solid rgba(34, 197, 94, 0.3);
  color: #22c55e;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
  backdrop-filter: blur(10px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow:
    0 4px 6px -1px rgba(34, 197, 94, 0.1),
    0 2px 4px -1px rgba(34, 197, 94, 0.06);
}

.status-active::before {
  content: '';
  width: 8px;
  height: 8px;
  background: linear-gradient(135deg, #22c55e, #16a34a);
  border-radius: 50%;
  box-shadow: 0 0 6px rgba(34, 197, 94, 0.6);
  animation: pulse-green 2s infinite;
}

.status-active:hover {
  background: rgba(34, 197, 94, 0.15);
  border-color: rgba(34, 197, 94, 0.4);
  transform: translateY(-1px);
  box-shadow:
    0 8px 10px -3px rgba(34, 197, 94, 0.15),
    0 4px 6px -2px rgba(34, 197, 94, 0.1);
}

.status-inactive {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  color: #ef4444;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
  backdrop-filter: blur(10px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow:
    0 4px 6px -1px rgba(239, 68, 68, 0.1),
    0 2px 4px -1px rgba(239, 68, 68, 0.06);
}

.status-inactive::before {
  content: '';
  width: 8px;
  height: 8px;
  background: linear-gradient(135deg, #ef4444, #dc2626);
  border-radius: 50%;
  box-shadow: 0 0 6px rgba(239, 68, 68, 0.6);
  animation: pulse-red 2s infinite;
}

.status-inactive:hover {
  background: rgba(239, 68, 68, 0.15);
  border-color: rgba(239, 68, 68, 0.4);
  transform: translateY(-1px);
  box-shadow:
    0 8px 10px -3px rgba(239, 68, 68, 0.15),
    0 4px 6px -2px rgba(239, 68, 68, 0.1);
}

.actions-cell {
  white-space: nowrap;
}

.btn {
  padding: 10px 16px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: inline-flex;
  align-items: center;
  gap: 6px;
  position: relative;
  overflow: hidden;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn:hover::before {
  left: 100%;
}

.btn-sm {
  padding: 8px 12px;
  font-size: 12px;
}

.btn-warning {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover:not(:disabled) {
  background: linear-gradient(135deg, #d97706, #b45309);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.btn-danger {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover:not(:disabled) {
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.btn-secondary {
  background: linear-gradient(135deg, #6b7280, #4b5563);
  color: white;
  box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

.btn-secondary:hover:not(:disabled) {
  background: linear-gradient(135deg, #4b5563, #374151);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
}

.btn i {
  font-size: 14px;
}

.toast-message {
  font-size: 14px;
  font-weight: 500;
}

.pagination {
  background: rgba(255, 255, 255, 0.171);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.15);
  padding: 20px 24px;
  border-radius: 16px;
  box-shadow:
    0 8px 32px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  margin-top: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  position: relative;
  z-index: 1;
  animation: slideInUp 0.6s ease-out 0.3s both;
}

.pagination button {
  padding: 10px 16px;
  border: 2px solid rgba(255, 255, 255, 0.2);
  border-radius: 10px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  color: rgb(29, 29, 29);
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  min-width: 44px;
}

.pagination button:hover:not(:disabled) {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.4);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.pagination button:disabled {
  opacity: 0.4;
  cursor: not-allowed;
  transform: none;
}

.pagination span {
  font-size: 14px;
  color: rgba(255, 255, 255, 0.9);
  font-weight: 500;
  padding: 0 8px;
}

/* Animations */
@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translate3d(0, -100%, 0);
  }

  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translate3d(0, 100%, 0);
  }

  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

/* Pulse animations for status indicators */
@keyframes pulse-green {

  0%,
  100% {
    box-shadow: 0 0 6px rgba(34, 197, 94, 0.6);
    transform: scale(1);
  }

  50% {
    box-shadow: 0 0 12px rgba(34, 197, 94, 0.8);
    transform: scale(1.1);
  }
}

@keyframes pulse-red {

  0%,
  100% {
    box-shadow: 0 0 6px rgba(239, 68, 68, 0.6);
    transform: scale(1);
  }

  50% {
    box-shadow: 0 0 12px rgba(239, 68, 68, 0.8);
    transform: scale(1.1);
  }
}

/* Loading animation for table rows */
.data-table tbody tr {
  animation: fadeIn 0.4s ease-out;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .customers-page {
    padding: 16px;
  }

  .header {
    padding: 24px 20px;
    border-radius: 16px;
  }

  .header h2 {
    font-size: 24px;
  }

  .toolbar {
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px;
  }

  .search-box {
    flex: 1 1 100%;
    max-width: none;
  }

  .filter-group {
    flex: 1 1 auto;
    min-width: 120px;
  }

  .data-table {
    font-size: 12px;
    border-radius: 12px;
  }

  .data-table th,
  .data-table td {
    padding: 12px 16px;
  }

  .pagination {
    padding: 16px;
    border-radius: 12px;
  }
}

/* Dark mode support */
/* @media (prefers-color-scheme: dark) {
  .customers-page {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  }
} */

/* Reduced motion for accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Focus indicators for accessibility */
.btn:focus,
.search-input:focus,
.filter-group select:focus {
  outline: 2px solid rgba(255, 255, 255, 0.5);
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .data-table {
    border: 2px solid white;
  }

  .btn {
    border: 2px solid white;
  }
}
</style>


<style scoped>
/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

/* Confirmation Modal */
.confirmation-modal {
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  max-width: 450px;
  width: 90%;
  animation: slideIn 0.3s ease;
  overflow: hidden;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }

  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.modal-header {
  text-align: center;
  padding: 24px 24px 16px;
  border-bottom: 1px solid #f0f0f0;
}

.modal-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 16px;
  border-radius: 50%;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
}

.modal-header h3 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #333;
}

.modal-body {
  padding: 20px 24px;
  text-align: center;
}

.modal-body p {
  margin: 0;
  font-size: 16px;
  color: #666;
  line-height: 1.5;
}

.modal-footer {
  padding: 16px 24px 24px;
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  min-width: 80px;
}

.btn-cancel {
  background: #f8f9fa;
  color: #6c757d;
  border: 1px solid #dee2e6;
}

.btn-cancel:hover {
  background: #e9ecef;
  border-color: #adb5bd;
}

.btn-confirm {
  color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-confirm:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-warning {
  background: #ffc107;
}

.btn-warning:hover {
  background: #e0a800;
}

.btn-success {
  background: #28a745;
  /* background: rebeccapurple; */
}

.btn-success:hover {
  background: #218838;
}

.btn-danger {
  background: #dc3545;
}

.btn-danger:hover {
  background: #c82333;
}

/* Text Colors */
.text-warning {
  color: #ffc107 !important;
}

.text-success {
  color: #28a745 !important;
}

.text-danger {
  color: #dc3545 !important;
}

/* Responsive */
@media (max-width: 480px) {
  .confirmation-modal {
    width: 95%;
    margin: 20px;
  }

  .modal-footer {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }
}
</style>

<style>
.toast-message {
  position: fixed;
  top: 24px;
  right: 24px;
  max-width: 380px;
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 18px 24px;
  border-radius: 16px;
  font-size: 15px;
  font-weight: 500;
  color: white;
  background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
  box-shadow:
    0 12px 32px rgba(0, 0, 0, 0.2),
    0 4px 16px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  opacity: 0;
  transform: translateY(-30px) scale(0.95);
  transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  z-index: 9999;
  pointer-events: none;
  overflow: hidden;
}

/* Hiệu ứng shimmer */
.toast-message::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg,
      transparent,
      rgba(255, 255, 255, 0.15),
      transparent);
  transition: left 0.8s ease;
}

.toast-message.show::before {
  left: 100%;
}

/* Khi toast được kích hoạt */
.toast-message.show {
  opacity: 1;
  transform: translateY(0) scale(1);
  pointer-events: auto;
  animation: bounce-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Animation bounce-in */
@keyframes bounce-in {
  0% {
    opacity: 0;
    transform: translateY(-30px) scale(0.8);
  }

  60% {
    opacity: 1;
    transform: translateY(5px) scale(1.05);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Animation fade-out */
.toast-message.hide {
  opacity: 0;
  transform: translateY(-20px) scale(0.9);
  transition: all 0.3s ease-in;
}

/* Icon với hiệu ứng pulse */
.toast-message i {
  font-size: 22px;
  margin-top: 2px;
  flex-shrink: 0;
  animation: pulse-icon 2s infinite;
  text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
}

@keyframes pulse-icon {

  0%,
  100% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.1);
  }
}

/* Nội dung toast */
.toast-content {
  flex: 1;
  line-height: 1.4;
}

.toast-title {
  font-weight: 600;
  font-size: 16px;
  margin-bottom: 4px;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.toast-description {
  font-size: 14px;
  opacity: 0.9;
  font-weight: 400;
}

/* Nút đóng */
.toast-close {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 24px;
  height: 24px;
  border: none;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border-radius: 50%;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0.7;
  transition: all 0.3s ease;
}

.toast-close:hover {
  opacity: 1;
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.1);
}

/* Màu theo từng loại thông báo với gradient */
.toast-success {
  background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
  border-color: rgba(46, 204, 113, 0.3);
}

.toast-success i {
  color: #a8f5c2;
}

/* Toast mở khóa người dùng - background sáng */
.toast-unlock {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  color: #495057;
  border-color: rgba(108, 117, 125, 0.2);
  box-shadow:
    0 12px 32px rgba(0, 0, 0, 0.1),
    0 4px 16px rgba(0, 0, 0, 0.05),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
}

.toast-unlock i {
  color: #28a745;
  text-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
}

.toast-unlock .toast-title {
  color: #343a40;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
}

.toast-unlock .toast-description {
  color: #6c757d;
}

.toast-error {
  background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
  border-color: rgba(231, 76, 60, 0.3);
}

.toast-error i {
  color: #ffb3b3;
}

.toast-warning {
  background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
  color: #2c3e50;
  border-color: rgba(243, 156, 18, 0.3);
}

.toast-warning i {
  color: #2c3e50;
  text-shadow: 0 0 8px rgba(44, 62, 80, 0.3);
}

.toast-info {
  background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
  border-color: rgba(52, 152, 219, 0.3);
}

.toast-info i {
  color: #a8d8f5;
}

/* Progress bar */
.toast-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 0 0 16px 16px;
  transform-origin: left;
  animation: progress-bar 5s linear forwards;
}

@keyframes progress-bar {
  0% {
    transform: scaleX(1);
  }

  100% {
    transform: scaleX(0);
  }
}

/* Hover effects */
.toast-message:hover {
  transform: translateY(-2px) scale(1.02);
  box-shadow:
    0 16px 40px rgba(0, 0, 0, 0.25),
    0 8px 20px rgba(0, 0, 0, 0.15),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

/* Multiple toast stacking */
.toast-message:nth-child(2) {
  top: 110px;
  opacity: 0.95;
  transform: scale(0.98);
}

.toast-message:nth-child(3) {
  top: 190px;
  opacity: 0.9;
  transform: scale(0.96);
}

.toast-message:nth-child(4) {
  top: 270px;
  opacity: 0.85;
  transform: scale(0.94);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .toast-message {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border-color: rgba(255, 255, 255, 0.15);
  }

  .toast-warning {
    color: #ffffff;
  }

  .toast-warning i {
    color: #ffffff;
    text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
  }

  /* Toast unlock trong dark mode */
  .toast-unlock {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #495057;
    border-color: rgba(108, 117, 125, 0.3);
  }

  .toast-unlock .toast-title {
    color: #343a40;
  }

  .toast-unlock .toast-description {
    color: #6c757d;
  }
}

/* Responsive: tối ưu cho màn hình nhỏ */
@media (max-width: 480px) {
  .toast-message {
    right: 16px;
    left: 16px;
    max-width: unset;
    font-size: 14px;
    padding: 16px 20px;
    border-radius: 12px;
  }

  .toast-title {
    font-size: 15px;
  }

  .toast-description {
    font-size: 13px;
  }

  .toast-message i {
    font-size: 20px;
  }

  .toast-message:nth-child(2) {
    top: 95px;
  }

  .toast-message:nth-child(3) {
    top: 170px;
  }

  .toast-message:nth-child(4) {
    top: 245px;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .toast-message {
    transition: opacity 0.3s ease;
  }

  .toast-message i {
    animation: none;
  }

  .toast-message::before {
    display: none;
  }

  .toast-progress {
    animation: none;
  }
}
</style>