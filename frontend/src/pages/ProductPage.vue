<template>
    <section class="products-section">
    <div class="container">
      <div class="products-container">
        
        <!-- Sidebar -->
        <aside class="sidebar">
          <!-- Price Filter -->
          <div class="filter-section">
            <h3 class="filter-title">Khoảng giá</h3>
            <div class="price-range">
              <div class="price-inputs">
                <input type="number" class="price-input" placeholder="Từ" min="0" max="10000000">
                <input type="number" class="price-input" placeholder="Đến" min="0" max="10000000">
              </div>
              <input type="range" class="price-slider" min="0" max="10000000" value="5000000">
            </div>
          </div>

          <!-- Category Filter -->
          <div class="filter-section">
            <h3 class="filter-title">Danh mục</h3>
            <ul class="category-list">
              <li class="category-item">
                <input type="checkbox" id="sneakers" class="category-checkbox">
                <label for="sneakers" class="category-label">Giày thể thao</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="boots" class="category-checkbox">
                <label for="boots" class="category-label">Giày boot</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="casual" class="category-checkbox">
                <label for="casual" class="category-label">Giày casual</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="formal" class="category-checkbox">
                <label for="formal" class="category-label">Giày công sở</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="sandals" class="category-checkbox">
                <label for="sandals" class="category-label">Dép & sandal</label>
              </li>
            </ul>
          </div>

          <!-- Brand Filter -->
          <div class="filter-section">
            <h3 class="filter-title">Thương hiệu</h3>
            <ul class="category-list">
              <li class="category-item">
                <input type="checkbox" id="nike" class="category-checkbox">
                <label for="nike" class="category-label">Nike</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="adidas" class="category-checkbox">
                <label for="adidas" class="category-label">Adidas</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="puma" class="category-checkbox">
                <label for="puma" class="category-label">Puma</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="converse" class="category-checkbox">
                <label for="converse" class="category-label">Converse</label>
              </li>
              <li class="category-item">
                <input type="checkbox" id="vans" class="category-checkbox">
                <label for="vans" class="category-label">Vans</label>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Products Content -->
        <div class="products-content">
          <div class="products-header">
            <h2 class="products-title">Tất cả sản phẩm</h2>
            <select class="sort-select">
              <option>Sắp xếp theo</option>
              <option>Giá thấp đến cao</option>
              <option>Giá cao đến thấp</option>
              <option>Mới nhất</option>
              <option>Bán chạy nhất</option>
            </select>
          </div>

          <div class="products-grid">
            <!-- Product 1 -->
            <article class="product-card">
              <div class="card-banner">
                <span class="card-badge">-20%</span>
                <img src="../assets/images/product-1.jpg" alt="Nike Air Max" class="product-img">
                <div class="card-actions">
                  <button class="action-btn">♡</button>
                  <button class="action-btn">👁</button>
                  <button class="action-btn">⚖</button>
                </div>
              </div>
              <div class="card-content">
                <p class="card-category">Giày thể thao</p>
                <h3 class="card-title">Nike Air Max 270</h3>
                <div class="card-price">
                  <span class="current-price">2.990.000₫</span>
                  <span class="old-price">3.490.000₫</span>
                </div>
                <button class="add-to-cart">Thêm vào giỏ</button>
              </div>
            </article>

            <!-- Product 2 -->
            <article class="product-card">
              <div class="card-banner">
                <span class="card-badge">Hot</span>
                <img src="../assets/images/product-1.jpg" alt="Adidas Ultraboost" class="product-img">
                <div class="card-actions">
                  <button class="action-btn">♡</button>
                  <button class="action-btn">👁</button>
                  <button class="action-btn">⚖</button>
                </div>
              </div>
              <div class="card-content">
                <p class="card-category">Giày thể thao</p>
                <h3 class="card-title">Adidas Ultraboost 22</h3>
                <div class="card-price">
                  <span class="current-price">4.200.000₫</span>
                </div>
                <button class="add-to-cart">Thêm vào giỏ</button>
              </div>
            </article>

            <!-- Product 3 -->
            <article class="product-card">
              <div class="card-banner">
                <img src="../assets/images/product-1.jpg" alt="Converse Chuck Taylor" class="product-img">
                <div class="card-actions">
                  <button class="action-btn">♡</button>
                  <button class="action-btn">👁</button>
                  <button class="action-btn">⚖</button>
                </div>
              </div>
              <div class="card-content">
                <p class="card-category">Giày casual</p>
                <h3 class="card-title">Converse Chuck Taylor All Star</h3>
                <div class="card-price">
                  <span class="current-price">1.690.000₫</span>
                </div>
                <button class="add-to-cart">Thêm vào giỏ</button>
              </div>
            </article>

            <!-- Product 4 -->
            <article class="product-card">
              <div class="card-banner">
                <span class="card-badge">New</span>
                <img src="../assets/images/product-1.jpg" alt="Vans Old Skool" class="product-img">
                <div class="card-actions">
                  <button class="action-btn">♡</button>
                  <button class="action-btn">👁</button>
                  <button class="action-btn">⚖</button>
                </div>
              </div>
              <div class="card-content">
                <p class="card-category">Giày casual</p>
                <h3 class="card-title">Vans Old Skool</h3>
                <div class="card-price">
                  <span class="current-price">1.890.000₫</span>
                </div>
                <button class="add-to-cart">Thêm vào giỏ</button>
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
  .container { 
    max-width: 1280px;
    margin-inline: auto;
  }

  .logo {
    font-size: var(--fs-3);
    font-weight: var(--fw-700);
    color: var(--bittersweet);
    font-family: var(--ff-josefin-sans);
  }

  .nav-menu {
    display: none;
  }

  .products-section {
    padding-block: 40px;
  }

  .products-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
  }

  .sidebar {
    background: var(--cultured);
    padding: 30px 20px;
    border-radius: 10px;
    height: fit-content;
  }

  .filter-section {
    margin-bottom: 30px;
  }

  .filter-title {
    font-size: var(--fs-5);
    font-weight: var(--fw-600);
    color: var(--rich-black-fogra-29);
    margin-bottom: 15px;
    font-family: var(--ff-josefin-sans);
  }

  .price-range {
    margin-bottom: 20px;
  }

  .price-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
  }

  .price-input {
    flex: 1;
    padding: 10px;
    border: 1px solid var(--gainsboro);
    border-radius: 5px;
    font-size: var(--fs-7);
  }

  .price-slider {
    width: 100%;
    height: 5px;
    background: var(--gainsboro);
    border-radius: 5px;
    outline: none;
  }

  .price-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    background: var(--bittersweet);
    border-radius: 50%;
    cursor: pointer;
  }

  .category-list {
    list-style: none;
  }

  .category-item {
    margin-bottom: 10px;
  }

  .category-checkbox {
    display: none;
  }

  .category-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: var(--fs-7);
    color: var(--onyx);
    transition: var(--transition-1);
  }

  .category-label::before {
    content: '';
    width: 18px;
    height: 18px;
    border: 2px solid var(--gainsboro);
    border-radius: 3px;
    margin-right: 10px;
    transition: var(--transition-1);
  }

  .category-checkbox:checked + .category-label::before {
    background: var(--bittersweet);
    border-color: var(--bittersweet);
  }

  .category-label:hover {
    color: var(--bittersweet);
  }

  .products-content {
    display: flex;
    flex-direction: column;
  }

  .products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
  }

  .products-title {
    font-size: var(--fs-2);
    font-weight: var(--fw-600);
    color: var(--rich-black-fogra-29);
  }

  .sort-select {
    padding: 10px 15px;
    border: 1px solid var(--gainsboro);
    border-radius: 5px;
    font-size: var(--fs-7);
    background: var(--white);
  }

  .products-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
  }

  .product-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px hsla(0, 0%, 0%, 0.1);
    transition: var(--transition-2);
  }

  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px hsla(0, 0%, 0%, 0.15);
  }

  .card-banner {
    height: 280px;
  }

  .product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition-2);
  }

  .product-card:hover .product-img {
    transform: scale(1.1);
  }

  .card-badge {
    top: 15px;
    left: 15px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: var(--fs-8);
    font-weight: var(--fw-500);
  }

  .card-actions {
    position: absolute;
    top: 15px;
    right: -50px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transition: var(--transition-1);
  }

  .product-card:hover .card-actions {
    right: 15px;
    opacity: 1;
  }

  .action-btn {
    width: 40px;
    height: 40px;
    background: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--rich-black-fogra-29);
    font-size: 18px;
    transition: var(--transition-1);
  }

  .action-btn:hover {
    background: var(--bittersweet);
    color: var(--white);
  }

  .card-content {
    padding: 20px;
  }

  .card-category {
    font-size: var(--fs-8);
    color: var(--onyx);
    text-transform: uppercase;
    margin-bottom: 8px;
    font-family: var(--ff-roboto);
  }

  .card-title {
    font-size: var(--fs-6);
    font-weight: var(--fw-600);
    color: var(--rich-black-fogra-29);
    margin-bottom: 12px;
    line-height: 1.3;
  }

  .card-price {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
  }

  .current-price {
    font-size: var(--fs-5);
    font-weight: var(--fw-700);
    color: var(--bittersweet);
  }

  .old-price {
    font-size: var(--fs-7);
    color: var(--gray-x-11-gray);
    text-decoration: line-through;
  }

  .add-to-cart {
    width: 100%;
    background: var(--bittersweet);
    color: var(--white);
    padding: 12px;
    border-radius: 5px;
    font-size: var(--fs-7);
    font-weight: var(--fw-500);
    transition: var(--transition-1);
  }

  .add-to-cart:hover {
    background: var(--salmon);
  }

  .menu-toggle {
    display: block;
    background: var(--bittersweet);
    color: var(--white);
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 20px;
  }

  @media (min-width: 768px) {
    .products-container {
      grid-template-columns: 1fr 2fr;
    }
    
    .products-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .sidebar {
      position: sticky;
      top: 120px;
    }
  }

  @media (min-width: 992px) {
    .products-container {
      grid-template-columns: 1fr 3fr;
    }
    
    .products-grid {
      grid-template-columns: repeat(3, 1fr);
    }
    
    .menu-toggle {
      display: none;
    }
    
    .nav-menu {
      display: flex;
      gap: 30px;
    }
    
    .nav-link {
      color: var(--rich-black-fogra-29);
      font-weight: var(--fw-500);
      transition: var(--transition-1);
    }
    
    .nav-link:hover {
      color: var(--bittersweet);
    }
  }

  @media (min-width: 1200px) {
    .card-banner {
      height: 320px;
    }
  }
</style>