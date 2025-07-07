<template>
  <div class="products-page">
    <transition name="toast">
      <div v-if="notification.show" :class="['toast-notification', `toast-${notification.type}`]">
        <i :class="['bi', getNotificationIcon(notification.type)]"></i>
        <span>{{ notification.message }}</span>
        <button @click="closeNotification" class="toast-close">
          <i class="bi bi-x"></i>
        </button>
      </div>
    </transition>

     <div class="header">
      <div class="header-content">
        <h2>
          <i class="bi bi-box-seam"></i>
          Quản Lí Sản Phẩm
        </h2>
        <p class="header-subtitle">Quản lý và theo dõi sản phẩm của bạn</p>
      </div>
      <div class="header-stats">
        <div class="stat-card">
          <div class="stat-number">{{ products.length }}</div>
          <div class="stat-label">Tổng sản phẩm</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{products.filter(p => p.status === 'Còn hàng').length}}</div>
          <div class="stat-label">Còn hàng</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{products.filter(p => p.status === 'Hết hàng').length}}</div>
          <div class="stat-label">Hết hàng</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{products.filter(p => p.is_active === 0).length}}</div>
          <div class="stat-label">Đã ẩn</div>
        </div>
      </div>
    </div>

    <div class="toolbar">
      <div class="search-section">
        <div class="search-box">
          <i class="bi bi-search search-icon"></i>
          <input v-model="searchTerm" type="text" placeholder="Tìm kiếm tên sản phẩm..." class="search-input" />
        </div>
      </div>

      <div class="filters-section">
        <div class="filter-group">
          <label>
            <i class="bi bi-flag"></i>
            Trạng Thái
          </label>
          <select v-model="statusFilter">
            <option value="">Tất cả</option>
            <option value="Còn hàng">Còn hàng</option>
            <option value="Hết hàng">Hết hàng</option>
          </select>
        </div>

        <div class="filter-group">
          <label>
            <i class="bi bi-tags"></i>
            Hãng
          </label>
          <select v-model="brandFilter">
            <option value="">Tất cả</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.brand_name">
              {{ brand.brand_name }}
            </option>
          </select>
        </div>

        <div class="filter-group">
          <label>
            <i class="bi bi-grid-3x3-gap"></i>
            Danh mục
          </label>
          <select v-model="categoryFilter">
            <option value="">Tất cả</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.category_name">
              {{ cat.category_name }}
            </option>
          </select>
        </div>

        <div class="filter-group">
          <label>
            <i class="bi bi-eye"></i>
            Hiển thị
          </label>
          <select v-model="visibilityFilter">
            <option value="">Tất cả</option>
            <option value="visible">Đang hiển thị</option>
            <option value="hidden">Đã ẩn</option>
          </select>
        </div>
      </div>

      <div class="actions-section">
        <button class="btn btn-primary" @click="openAddModal">
          <i class="bi bi-plus-lg"></i>
          Thêm Sản Phẩm
        </button>
      </div>
    </div>

    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th class="image-column">
              <i class="bi bi-image"></i>
              Hình Ảnh
            </th>
            <th>
              <i class="bi bi-box"></i>
              Tên Sản Phẩm
            </th>
            <th>
              <i class="bi bi-grid-3x3-gap"></i>
              Danh Mục
            </th>
            <th>
              <i class="bi bi-currency-dollar"></i>
              Giá Gốc
            </th>
            <th>
              <i class="bi bi-currency-dollar"></i>
              Giảm Giá
            </th>
            <th>
              <i class="bi bi-award"></i>
              Hãng
            </th>
            <th>
              <i class="bi bi-flag"></i>
              Trạng Thái
            </th>
            <th>
              <i class="bi bi-eye"></i>
              Hiển thị
            </th>
            <th>
              <i class="bi bi-gear"></i>
              Hành Động
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in paginatedProducts" :key="product.id" class="table-row" :class="{ 'row-hidden': product.is_active === 0 }">
            <td class="image-cell">
              <div class="product-image">
                <img
                  v-if="product.hinh_anh"
                  :src="getImageUrl(product.hinh_anh)"
                  :alt="product.product_name"
                  @error="handleImageError"
                  @load="handleImageLoad"
                />
                <div v-else class="no-image">
                  <i class="bi bi-image"></i>
                </div>
              </div>
            </td>

            <td class="product-name">
              <div class="product-info">
                <div class="product-avatar">
                  <i class="bi bi-box-seam"></i>
                </div>
                <div>
                  <div class="name">{{ product.product_name }}</div>
                  <div class="id">#{{ product.id }}</div>
                </div>
              </div>
            </td>
            <td>
              <span class="category-badge">
                {{ product.category?.category_name || '---' }}
              </span>
            </td>
            <td class="price">{{ formatVND(product.price_original) }}</td>
            <td class="price">{{ formatVND(product.sale_price) }}</td>
            <td>
              <span class="brand-tag">
                {{ product.brand?.brand_name || '---' }}
              </span>
            </td>
            <td>
              <span :class="['status-badge', product.status === 'Còn hàng' ? 'status-active' : 'status-inactive']">
                <i :class="['bi', product.status === 'Còn hàng' ? 'bi-check-circle' : 'bi-x-circle']"></i>
                {{ product.status }}
              </span>
            </td>
            <td>
              <span :class="['visibility-badge', product.is_active === 0 ? 'visibility-hidden' : 'visibility-visible']">
                <i :class="['bi', product.is_active === 0 ? 'bi-eye-slash' : 'bi-eye']"></i>
                {{ product.is_active === 0 ? 'Đã ẩn' : 'Hiển thị' }}
              </span>
            </td>
            <td class="actions-cell">
              <div class="action-buttons">
                <button 
                  :class="['btn', 'btn-sm', product.is_active === 0 ? 'btn-success' : 'btn-info']" 
                  @click="toggleVisibility(product)" 
                  :title="product.is_active === 0 ? 'Hiển thị' : 'Ẩn'"
                >
                  <i :class="['bi', product.is_active === 0 ? 'bi-eye' : 'bi-eye-slash']"></i>
                </button>
                <button class="btn btn-sm btn-warning" @click="editProduct(product)" title="Chỉnh sửa">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" @click="deleteProduct(product.id)" title="Xóa">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="paginatedProducts.length === 0">
            <td colspan="9" class="empty-state">
              <div class="empty-content">
                <i class="bi bi-inbox"></i>
                <h4>Không tìm thấy sản phẩm</h4>
                <p>Thử thay đổi bộ lọc hoặc thêm sản phẩm mới</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="pagination-btn">
        <i class="bi bi-chevron-left"></i>
      </button>
      <div class="pagination-info">
        <span>Trang {{ currentPage }} / {{ totalPages }}</span>
        <small>{{ filteredProducts.length }} sản phẩm</small>
      </div>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="pagination-btn">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>

    <div class="modal-overlay" v-if="showModal" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>
            <i :class="['bi', editingProduct ? 'bi-pencil-square' : 'bi-plus-circle']"></i>
            {{ editingProduct ? 'Chỉnh sửa Sản Phẩm' : 'Thêm Sản Phẩm Mới' }}
          </h3>
          <button @click="closeModal" class="modal-close">
            <i class="bi bi-x"></i>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>
              <i class="bi bi-box"></i>
              Tên sản phẩm
            </label>
            <input v-model="form.product_name" type="text" placeholder="Nhập tên sản phẩm" required />
            <span v-if="formErrors.product_name" class="error">{{ formErrors.product_name }}</span>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>
                <i class="bi bi-currency-dollar"></i>
                Giá gốc (VND)
              </label>
              <input v-model.number="form.price_original" type="number" min="0" step="1" placeholder="0"
                @input="validatePrice('price_original')" required />
              <span v-if="formErrors.price_original" class="error">{{ formErrors.price_original }}</span>
            </div>
            <div class="form-group">
              <label>
                <i class="bi bi-tag"></i>
                Giá khuyến mãi (VND)
              </label>
              <input v-model.number="form.sale_price" type="number" min="0" step="1" placeholder="0"
                @input="validatePrice('sale_price')" required />
              <span v-if="formErrors.sale_price" class="error">{{ formErrors.sale_price }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>
                <i class="bi bi-grid-3x3-gap"></i>
                Danh Mục
              </label>
              <select v-model="form.category_id" required>
                <option disabled :value="null">Chọn danh mục</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.category_name }}
                </option>
              </select>
              <span v-if="formErrors.category_id" class="error">{{ formErrors.category_id }}</span>
            </div>
            <div class="form-group">
              <label>
                <i class="bi bi-award"></i>
                Thương hiệu
              </label>
              <select v-model="form.brand_id" required>
                <option disabled :value="null">Chọn thương hiệu</option>
                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                  {{ brand.brand_name }}
                </option>
              </select>
              <span v-if="formErrors.brand_id" class="error">{{ formErrors.brand_id }}</span>
            </div>
          </div>

          <div class="form-group">
            <label>
              <i class="bi bi-flag"></i>
              Trạng thái
            </label>
            <select v-model="form.status" required>
              <option disabled value="">Chọn trạng thái</option>
              <option value="Còn hàng">Còn hàng</option>
              <option value="Hết hàng">Hết hàng</option>
            </select>
            <span v-if="formErrors.status" class="error">{{ formErrors.status }}</span>
          </div>

          <div class="form-group">
            <label>
              <i class="bi bi-image"></i>
              Hình ảnh
            </label>
            <input type="file" @change="previewImages" multiple accept="image/*" class="file-input" />
            <div class="image-preview" v-if="imagePreviews.length">
              <img v-for="img in imagePreviews" :src="img" :key="img" />
            </div>
          </div>
        </div>
              
        <div class="modal-footer">
          <button @click="closeModal" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i>
            Hủy
          </button>
          <button @click="saveProduct" class="btn btn-primary">
            <i class="bi bi-check-lg"></i>
            {{ editingProduct ? 'Cập nhật' : 'Lưu' }}
          </button>
        </div>
      </div>
    </div>
    <div class="modal-overlay" v-if="showDeleteModal" @click.self="closeDeleteModal">
      <div class="modal-content modal-delete">
        <div class="modal-header">
          <h3><i class="bi bi-exclamation-triangle"></i> Xác nhận xóa</h3>
          <button @click="closeDeleteModal" class="modal-close">
            <i class="bi bi-x"></i>
          </button>
        </div>
        <div class="modal-body">
          <p>Bạn có chắc chắn muốn xóa sản phẩm này?</p>
        </div>
        <div class="modal-footer">
          <button @click="closeDeleteModal" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Hủy
          </button>
          <button @click="confirmDelete" class="btn btn-danger">
            <i class="bi bi-trash"></i> Xóa
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      products: [],
      categories: [],
      brands: [],
      searchTerm: '',
      statusFilter: '',
      brandFilter: '',
      categoryFilter: '',
      visibilityFilter: '',
      currentPage: 1,
      itemsPerPage: 10,
      showModal: false,
      editingProduct: null,
      imagePreviews: [],
      selectedFiles: [],
      notification: {
        show: false,
        type: 'success',
        message: ''
      },
      form: {
        product_name: '',
        price_original: 0,
        sale_price: 0,
        status: 'Còn hàng',
        is_active: 1,
        category_id: null,
        brand_id: null
      },
      formErrors: {
        product_name: '',
        price_original: '',
        sale_price: '',
        status: '',
        is_active: '',
        category_id: '',
        brand_id: ''
      },
      showDeleteModal: false,
      deleteProductId: null
    };
  },
  computed: {
    filteredProducts() {
      return this.products.filter(product => {
        const searchText = this.searchTerm.toLowerCase();
        return (
          product.product_name.toLowerCase().includes(searchText) &&
          (!this.statusFilter || product.status === this.statusFilter) &&
          (!this.brandFilter || product.brand?.brand_name === this.brandFilter) &&
          (!this.categoryFilter || product.category?.category_name === this.categoryFilter) &&
          (!this.visibilityFilter ||
            (this.visibilityFilter === 'visible' && product.is_active === 1) ||
            (this.visibilityFilter === 'hidden' && product.is_active === 0))
        );
      });
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredProducts.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
    }
  },
  watch: {
    searchTerm: debounce(function () {
      this.currentPage = 1;
    }, 300),
    statusFilter() {
      this.currentPage = 1;
    },
    brandFilter() {
      this.currentPage = 1;
    },
    categoryFilter() {
      this.currentPage = 1;
    },
    visibilityFilter() {
      this.currentPage = 1;
    }
  },
  methods: {
    async fetchProducts() {
      try {
        const res = await axios.get('http://localhost:8000/api/products');
        this.products = res.data.data || [];
        console.log('Products data:', this.products);
      } catch (err) {
        this.showNotification('error', 'Lỗi khi tải danh sách sản phẩm');
        console.error(err);
      }
    },

    async fetchCategories() {
      try {
        const res = await axios.get('http://localhost:8000/api/categories');
        this.categories = res.data.data || [];
      } catch (err) {
        this.showNotification('error', 'Lỗi khi tải danh mục');
        console.error(err);
      }
    },

    async fetchBrands() {
      try {
        const res = await axios.get('http://localhost:8000/api/brands');
        this.brands = res.data.data || [];
      } catch (err) {
        this.showNotification('error', 'Lỗi khi tải thương hiệu');
        console.error(err);
      }
    },
    openAddModal() {
      this.editingProduct = null;
      this.form = {
        product_name: '',
        price_original: 0,
        sale_price: 0,
        status: 'Còn hàng',
        is_active: 1,
        category_id: null,
        brand_id: null
      };
      this.formErrors = {
        product_name: '',
        price_original: '',
        sale_price: '',
        status: '',
        is_active: '',
        category_id: '',
        brand_id: ''
      };
      this.imagePreviews = [];
      this.selectedFiles = [];
      this.showModal = true;
    },
    editProduct(product) {
      this.editingProduct = product;
      this.form = {
        product_name: product.product_name,
        price_original: product.price_original,
        sale_price: product.sale_price,
        status: product.status,
        is_active: product.is_active,
        category_id: product.category?.id,
        brand_id: product.brand?.id
      };
      this.formErrors = {
        product_name: '',
        price_original: '',
        sale_price: '',
        status: '',
        is_active: '',
        category_id: '',
        brand_id: ''
      };
      this.selectedFiles = [];
      this.imagePreviews = [];

      if (product.hinh_anh) {
        try {
          const imageList = JSON.parse(product.hinh_anh);
          this.imagePreviews = imageList.map(fileName => `http://localhost:8000/storage/products/${fileName}`);
        } catch (e) {
          console.error('Lỗi parse ảnh:', e);
        }
      }

      this.showModal = true;
    },
    validateForm() {
      let isValid = true;
      this.formErrors = {
        product_name: '',
        price_original: '',
        sale_price: '',
        status: '',
        is_active: '',
        category_id: '',
        brand_id: ''
      };

      if (!this.form.product_name.trim()) {
        this.formErrors.product_name = 'Tên sản phẩm không được để trống';
        isValid = false;
      }

      if (!Number.isInteger(Number(this.form.price_original)) || this.form.price_original < 0) {
        this.formErrors.price_original = 'Giá gốc phải là số nguyên không âm';
        isValid = false;
      }

      if (!Number.isInteger(Number(this.form.sale_price)) || this.form.sale_price < 0) {
        this.formErrors.sale_price = 'Giá khuyến mãi phải là số nguyên không âm';
        isValid = false;
      }

      if (!this.form.status) {
        this.formErrors.status = 'Vui lòng chọn trạng thái';
        isValid = false;
      }

      if (!this.form.category_id) {
        this.formErrors.category_id = 'Vui lòng chọn danh mục';
        isValid = false;
      }

      if (!this.form.brand_id) {
        this.formErrors.brand_id = 'Vui lòng chọn thương hiệu';
        isValid = false;
      }

      return isValid;
    },
    async saveProduct() {
      if (!this.validateForm()) {
        this.showNotification('error', 'Vui lòng kiểm tra lại thông tin nhập vào!');
        return;
      }

      try {
        const formData = new FormData();
        
        formData.append('product_name', this.form.product_name);
        formData.append('price_original', parseInt(this.form.price_original));
        formData.append('sale_price', parseInt(this.form.sale_price));
        formData.append('status', this.form.status);
        formData.append('is_active', this.form.is_active);
        formData.append('category_id', this.form.category_id);
        formData.append('brand_id', this.form.brand_id);
        
        if (this.selectedFiles.length > 0) {
          if (this.editingProduct) {
            for (let i = 0; i < this.selectedFiles.length; i++) {
              formData.append('hinh_anh[]', this.selectedFiles[i]);
            }
          } else {
            formData.append('hinh_anh', this.selectedFiles[0]);
          }
        }

        const config = {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        };

        if (this.editingProduct) {
          formData.append('_method', 'PUT');
          await axios.post(`http://localhost:8000/api/products/${this.editingProduct.id}`, formData, config);
          this.showNotification('success', 'Cập nhật sản phẩm thành công!');
        } else {
          await axios.post('http://localhost:8000/api/products', formData, config);
          this.showNotification('success', 'Thêm sản phẩm thành công!');
        }

        this.showModal = false;
        await this.fetchProducts();
      } catch (err) {
        this.showNotification('error', 'Lỗi khi lưu sản phẩm. Vui lòng kiểm tra lại!');
        console.error('Error saving product:', err);
        if (err.response) {
          console.error('Response data:', err.response.data);
          console.error('Response status:', err.response.status);
        }
        
        console.log('FormData contents:');
        for (let [key, value] of formData.entries()) {
          console.log(key, value);
        }
      }
    },
    async toggleVisibility(product) {
      try {
        const newStatus = product.is_active === 1 ? 0 : 1;
        
        await axios.patch(`http://localhost:8000/api/products/${product.id}/toggle-active`, {
          is_active: newStatus
        });
        
        product.is_active = newStatus;
        
        this.showNotification('success', 
          newStatus === 1 ? 'Hiển thị sản phẩm thành công!' : 'Ẩn sản phẩm thành công!'
        );
      } catch (err) {
        this.showNotification('error', 'Lỗi khi cập nhật trạng thái hiển thị');
        console.error('Error toggling visibility:', err);
      }
    },

    async hideProduct(productId) {
      try {
        await axios.patch(`http://localhost:8000/api/products/${productId}`, {
          is_active: 0
        });
        this.showNotification('success', 'Ẩn sản phẩm thành công!');
        await this.fetchProducts();
      } catch (err) {
        this.showNotification('error', 'Lỗi khi ẩn sản phẩm');
        console.error(err);
      }
    },

    async showProduct(productId) {
      try {
        await axios.patch(`http://localhost:8000/api/products/${productId}`, {
          is_active: 1
        });
        this.showNotification('success', 'Hiển thị sản phẩm thành công!');
        await this.fetchProducts();
      } catch (err) {
        this.showNotification('error', 'Lỗi khi hiển thị sản phẩm');
        console.error(err);
      }
    },
    getVisibilityText(isActive) {
      return isActive === 1 ? 'Hiển thị' : 'Ẩn';
    },
    getVisibilityClass(isActive) {
      return isActive === 1 ? 'badge-success' : 'badge-secondary';
    },
    openDeleteModal(id) {
      this.deleteProductId = id;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.deleteProductId = null;
    },
    async confirmDelete() {
      try {
        await axios.delete(`http://localhost:8000/api/products/${this.deleteProductId}`);
        this.showNotification('success', 'Xóa sản phẩm thành công!');
        await this.fetchProducts();
      } catch (err) {
        this.showNotification('error', 'Lỗi khi xóa sản phẩm');
        console.error(err);
      } finally {
        this.closeDeleteModal();
      }
    },
    async deleteProduct(id) {
      this.openDeleteModal(id);
    },
    closeModal() {
      this.showModal = false;
      this.formErrors = {
        product_name: '',
        price_original: '',
        sale_price: '',
        status: '',
        is_active: '',
        category_id: '',
        brand_id: ''
      };
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    previewImages(event) {
      const files = event.target.files;
      this.selectedFiles = Array.from(files);
      this.imagePreviews = [];
      
      for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreviews.push(e.target.result);
        };
        reader.readAsDataURL(files[i]);
      }
    },
    formatVND(number) {
      if (!number && number !== 0) return '0 đ';
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' đ';
    },
    showNotification(type, message) {
      this.notification = {
        show: true,
        type,
        message
      };
      setTimeout(() => {
        this.closeNotification();
      }, 4000);
    },
    closeNotification() {
      this.notification.show = false;
    },
    getImageUrl(imageName) {
      if (!imageName) {
        return 'http://localhost:8000/storage/products/no-image.png';
      }

      try {
        let imageFileName = imageName;
        
        if (typeof imageName === 'string' && imageName.startsWith('[')) {
          const imageArray = JSON.parse(imageName);
          imageFileName = imageArray[0];
        }

        return `http://localhost:8000/storage/products/${imageFileName}`;
      } catch (error) {
        console.error('Error parsing image name:', error);
        return 'http://localhost:8000/storage/products/no-image.png';
      }
    },
    handleImageError(event) {
      console.log('Image failed to load:', event.target.src);
      event.target.src = 'http://localhost:8000/storage/products/no-image.png';
    },
    handleImageLoad(event) {
      console.log('Image loaded successfully:', event.target.src);
    },
    getNotificationIcon(type) {
      const icons = {
        success: 'bi-check-circle',
        error: 'bi-x-circle',
        warning: 'bi-exclamation-triangle',
        info: 'bi-info-circle'
      };
      return icons[type] || 'bi-info-circle';
    }
  },
  mounted() {
    this.fetchProducts();
    this.fetchCategories();
    this.fetchBrands();
  }
};
</script>




<style scoped>
/* Import Bootstrap Icons */
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');

/* Cột hình ảnh */
.image-column {
  width: 70px;
  text-align: center;
}

.image-cell {
  padding: 10px 8px;
  text-align: center;
}

.product-image {
  width: 45px;
  height: 45px;
  border-radius: 8px;
  overflow: hidden;
  display: inline-block;
  border: 2px solid #e5e7eb;
  background-color: #f9fafb;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.no-image {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  font-size: 16px;
}

/* Cột hiển thị/ẩn */
.visibility-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

.visibility-visible {
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
  color: #065f46;
}

.visibility-hidden {
  background: linear-gradient(135deg, #fee2e2, #fecaca);
  color: #991b1b;
}

/* Hàng bị ẩn */
.row-hidden {
  opacity: 0.6;
  background-color: rgba(239, 68, 68, 0.05);
}

/* Toast Notification Styles */
.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 20px;
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  font-weight: 500;
  min-width: 300px;
  max-width: 400px;
}

.toast-success {
  background: linear-gradient(135deg, #10b981, #065f46);
}

.toast-error {
  background: linear-gradient(135deg, #ef4444, #991b1b);
}

.toast-warning {
  background: linear-gradient(135deg, #f59e0b, #92400e);
}

.toast-info {
  background: linear-gradient(135deg, #3b82f6, #1e40af);
}

.toast-close {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: background-color 0.2s;
}

.toast-close:hover {
  background: rgba(255, 255, 255, 0.2);
}

/* Toast Animation */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%) scale(0.8);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%) scale(0.8);
}

/* Main Container */
.products-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fff7f0 0%, #ffede0 100%);
  padding: 20px;
  font-family: 'Roboto', 'Noto Sans', sans-serif !important;
}


/* Header Styles */
.header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 121, 0, 0.1);
  box-shadow: 0 8px 32px rgba(255, 121, 0, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.header-content h2 {
  margin: 0 0 8px 0;
  color: #ff7900;
  font-size: 32px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
}

.header-subtitle {
  margin: 0;
  color: #6b7280;
  font-size: 16px;
  font-weight: 400;
}

.header-stats {
  display: flex;
  gap: 20px;
}

.stat-card {
  background: linear-gradient(135deg, #fff7f0, #ffede0);
  padding: 20px;
  border-radius: 16px;
  text-align: center;
  min-width: 120px;
  border: 1px solid rgba(255, 121, 0, 0.1);
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 121, 0, 0.15);
}

.stat-number {
  font-size: 24px;
  font-weight: 700;
  color: #ff7900;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 14px;
  color: #6b7280;
  font-weight: 500;
}

/* Toolbar Styles */
.toolbar {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 20px;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 121, 0, 0.1);
  box-shadow: 0 8px 32px rgba(255, 121, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.search-section {
  flex: 1;
  min-width: 250px;
}

.search-box {
  position: relative;
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #ff7900;
  font-size: 16px;
}

.search-input {
  width: 100%;
  padding: 14px 16px 14px 48px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 16px;
  background: white;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.filters-section {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  display: flex;
  align-items: center;
  gap: 6px;
}

.filter-group label i {
  color: #ff7900;
}

.filter-group select {
  padding: 10px 14px;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  background: white;
  font-size: 14px;
  min-width: 140px;
  transition: all 0.3s ease;
}

.filter-group select:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

/* Table Styles */
.table-container {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 0;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 121, 0, 0.1);
  box-shadow: 0 8px 32px rgba(255, 121, 0, 0.1);
  overflow: hidden;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  background: linear-gradient(135deg, #fff7f0, #ffede0);
  /* background: #ff7900; */
  padding: 16px 12px;
  text-align: left;
  font-weight: 600;
  color: #ff7900;
  font-size: 14px;
  border-bottom: 1px solid rgba(255, 121, 0, 0.1);
}

.data-table th i {
  margin-right: 8px;
  color: #ff7900;
}

.table-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid #f3f4f6;
}

.table-row:hover {
  background: rgba(255, 121, 0, 0.05);
  transform: translateY(-1px);
}

.data-table td {
  padding: 16px 12px;
  vertical-align: middle;
}

.product-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.product-avatar {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: linear-gradient(135deg, #ff7900, #e66b00);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 16px;
}

.product-info .name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 2px;
  font-size: 14px;
}

.product-info .id {
  font-size: 12px;
  color: #9ca3af;
}

.category-badge {
  background: linear-gradient(135deg, #e0f2fe, #b3e5fc);
  color: #0277bd;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
}

.brand-tag {
  background: linear-gradient(135deg, #fff3e0, #ffe0b2);
  color: #ef6c00;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
}

.price {
  font-weight: 700;
  color: #059669;
  font-size: 14px;
  white-space: nowrap;
}

.status-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

.status-active {
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
  color: #065f46;
}

.status-inactive {
  background: linear-gradient(135deg, #fecaca, #fca5a5);
  color: #991b1b;
}

.action-buttons {
  display: flex;
  gap: 6px;
}

.actions-cell {
  min-width: 140px;
}

/* Button Styles */
.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn-sm {
  padding: 8px 10px;
  font-size: 12px;
}

.btn-primary {
  background: linear-gradient(135deg, #ff7900, #e66b00);
  color: white;
  box-shadow: 0 4px 14px rgba(255, 121, 0, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 121, 0, 0.4);
}

.btn-info {
  background: linear-gradient(135deg, #06b6d4, #0891b2);
  color: white;
  box-shadow: 0 4px 14px rgba(6, 182, 212, 0.3);
}

.btn-info:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
}

.btn-success {
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-warning {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.btn-danger {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
  box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.btn-secondary {
  background: linear-gradient(135deg, #6b7280, #4b5563);
  color: white;
  box-shadow: 0 4px 14px rgba(107, 114, 128, 0.3);
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
  box-shadow: none !important;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-content {
  color: #9ca3af;
}

.empty-content i {
  font-size: 48px;
  margin-bottom: 16px;
  display: block;
  color: #ff7900;
}

.empty-content h4 {
  margin: 0 0 8px 0;
  font-size: 18px;
  color: #6b7280;
}

.empty-content p {
  margin: 0;
  font-size: 14px;
}

/* Pagination */
.pagination {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 16px;
  padding: 20px;
  border: 1px solid rgba(255, 121, 0, 0.1);
  box-shadow: 0 8px 32px rgba(255, 121, 0, 0.1);
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
}

.pagination-btn {
  width: 40px;
  height: 40px;
  border: none;
  border-radius: 10px;
  background: linear-gradient(135deg, #f8fafc, #e2e8f0);
  color: #374151;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  font-size: 14px;
}

.pagination-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #ff7900, #e66b00);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 14px rgba(255, 121, 0, 0.3);
}

.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-info {
  text-align: center;
}

.pagination-info span {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 4px;
}

.pagination-info small {
  color: #9ca3af;
  font-size: 12px;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(8px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 20px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-50px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.modal-header {
  padding: 30px 30px 0 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #f3f4f6;
  margin-bottom: 30px;
  padding-bottom: 20px;
}

.modal-header h3 {
  margin: 0;
  font-size: 24px;
  font-weight: 700;
  color: #ff7900;
  display: flex;
  align-items: center;
  gap: 12px;
}

.modal-close {
  width: 40px;
  height: 40px;
  border: none;
  border-radius: 10px;
  background: #f3f4f6;
  color: #6b7280;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.modal-close:hover {
  background: #ef4444;
  color: white;
  transform: rotate(90deg);
}

.modal-body {
  padding: 0 30px;
}

.form-group {
  margin-bottom: 24px;
}

.form-row {
  display: flex;
  gap: 20px;
}

.form-row .form-group {
  flex: 1;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #374151;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-group label i {
  color: #ff7900;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 14px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 16px;
  background: white;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #ff7900;
  box-shadow: 0 0 0 3px rgba(255, 121, 0, 0.1);
}

.file-input {
  padding: 12px !important;
  background: #f9fafb !important;
  border: 2px dashed #d1d5db !important;
  cursor: pointer;
}

.file-input:hover {
  background: #f3f4f6 !important;
  border-color: #ff7900 !important;
}

.image-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 16px;
}

.image-preview img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 12px;
  border: 2px solid #e5e7eb;
  transition: all 0.3s ease;
}

.image-preview img:hover {
  transform: scale(1.05);
  border-color: #ff7900;
}

.modal-footer {
  padding: 30px;
  border-top: 1px solid #f3f4f6;
  display: flex;
  justify-content: flex-end;
  gap: 16px;
  margin-top: 30px;
}

.modal-delete {
  width: 400px;
  max-width: 90%;
}

.modal-body p {
  margin: 0;
  font-size: 1rem;
  color: #333;
}

.error {
  color: #ef4444;
  font-size: 12px;
  margin-top: 4px;
  display: block;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .header {
    flex-direction: column;
    text-align: center;
  }

  .header-stats {
    width: 100%;
    justify-content: center;
  }

  .toolbar {
    flex-direction: column;
    align-items: stretch;
  }

  .search-section,
  .filters-section,
  .actions-section {
    width: 100%;
  }

  .filters-section {
    justify-content: center;
  }
}

@media (max-width: 768px) {
  .products-page {
    padding: 15px;
  }

  .header,
  .toolbar,
  .table-container,
  .pagination {
    border-radius: 16px;
    padding: 20px;
  }

  .header-content h2 {
    font-size: 24px;
  }

  .stat-card {
    min-width: 100px;
    padding: 16px;
  }

  .data-table {
    font-size: 14px;
  }

  .data-table th,
  .data-table td {
    padding: 10px 8px;
  }

  .form-row {
    flex-direction: column;
  }

  .modal-content {
    margin: 10px;
    border-radius: 16px;
  }

  .modal-header,
  .modal-body,
  .modal-footer {
    padding-left: 20px;
    padding-right: 20px;
  }

  .product-info {
    flex-direction: column;
    text-align: center;
    gap: 8px;
  }

  .action-buttons {
    flex-direction: column;
    gap: 4px;
  }

  .actions-cell {
    min-width: 100px;
  }

  /* Ẩn một số cột trên mobile */
  .data-table .image-column,
  .data-table .image-cell {
    display: none;
  }
}

@media (max-width: 480px) {
  .header-stats {
    flex-direction: column;
    gap: 12px;
  }

  .stat-card {
    width: 100%;
  }

  .filters-section {
    flex-direction: column;
  }

  .filter-group select {
    min-width: 100%;
  }

  .table-container {
    overflow-x: auto;
  }

  .data-table {
    min-width: 800px;
  }

  /* Ẩn thêm cột trên mobile nhỏ */
  .data-table th:nth-child(4),
  .data-table td:nth-child(4),
  .data-table th:nth-child(6),
  .data-table td:nth-child(6) {
    display: none;
  }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #ff7900, #e66b00);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #e66b00, #cc5500);
}

/* Loading Animation */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.loading {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Selection Styles */
::selection {
  background: rgba(255, 121, 0, 0.2);
  color: #cc5500;
}

/* Focus Styles for Accessibility */
.btn:focus-visible,
input:focus-visible,
select:focus-visible,
button:focus-visible {
  outline: 2px solid #ff7900;
  outline-offset: 2px;
}

/* Smooth Transitions */
* {
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Print Styles */
@media print {
  .toolbar,
  .pagination,
  .actions-cell,
  .toast-notification,
  .modal-overlay {
    display: none !important;
  }

  .products-page {
    background: white !important;
  }

  .header,
  .table-container {
    background: white !important;
    box-shadow: none !important;
    border: 1px solid #ddd !important;
  }
}
</style>