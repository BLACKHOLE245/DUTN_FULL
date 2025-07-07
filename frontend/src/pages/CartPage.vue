<template>
  <section class="cart-section">
    <div class="container">
      <h2 class="cart-title">Giỏ Hàng</h2>
      <div class="cart-container">
        <div class="cart-table">
          <table>
            <thead>
              <tr>
                <th class="col-stt">STT</th>
                <th class="col-image">Hình ảnh</th>
                <th class="col-name">Tên SP</th>
                <th class="col-price">Giá tiền</th>
                <th class="col-quantity">Số lượng</th>
                <th class="col-total">Tổng</th>
                <th class="col-remove">Xóa</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="col-stt">1</td>
                <td class="col-image">
                  <div class="cart-item">
                    <div class="cart-item-img">
                      <img src="../assets/images/product-1.jpg" alt="Nike Air Max 270">
                    </div>
                  </div>
                </td>
                <td class="col-name">
                  <div class="cart-item-info">
                    <span class="cart-item-title">Nike Air Max 270</span>
                    <span class="cart-item-size">Kích thước: 40</span>
                  </div>
                </td>
                <td class="col-price cart-item-price">2.990.000₫</td>
                <td class="col-quantity">
                  <div class="quantity-input">
                    <button class="quantity-btn">-</button>
                    <input type="number" value="1" min="1" max="10">
                    <button class="quantity-btn">+</button>
                  </div>
                </td>
                <td class="col-total cart-item-price">2.990.000₫</td>
                <td class="col-remove"><button class="remove-btn">Xóa</button></td>
              </tr>
              <tr>
                <td class="col-stt">2</td>
                <td class="col-image">
                  <div class="cart-item">
                    <div class="cart-item-img">
                      <img src="../assets/images/product-1.jpg" alt="Adidas Ultraboost 22">
                    </div>
                  </div>
                </td>
                <td class="col-name">
                  <div class="cart-item-info">
                    <span class="cart-item-title">Adidas Ultraboost 22</span>
                    <span class="cart-item-size">Kích thước: 41</span>
                  </div>
                </td>
                <td class="col-price cart-item-price">4.200.000₫</td>
                <td class="col-quantity">
                  <div class="quantity-input">
                    <button class="quantity-btn">-</button>
                    <input type="number" value="2" min="1" max="10">
                    <button class="quantity-btn">+</button>
                  </div>
                </td>
                <td class="col-total cart-item-price">8.400.000₫</td>
                <td class="col-remove"><button class="remove-btn">Xóa</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="cart-summary">
          <h3 class="summary-title">Tóm tắt đơn hàng</h3>
          <div class="summary-item">
            <span>Tạm tính</span>
            <span>11.390.000₫</span>
          </div>
          <div class="summary-item">
            <span>Phí vận chuyển</span>
            <span>50.000₫</span>
          </div>
          <div class="summary-item summary-total">
            <span>Tổng cộng</span>
            <span>11.440.000₫</span>
          </div>
          <router-link to="/pay" class="checkout-btn">Thanh toán</router-link>
          <a href="#" class="continue-shopping">Tiếp tục mua sắm</a>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.container { 
  max-width: 1280px;
  margin-inline: auto;
  padding-inline: 15px;
}

.cart-section { 
  padding-block: var(--section-padding); 
}

.cart-title {
  font-size: var(--fs-2);
  font-weight: var(--fw-600);
  margin-bottom: 30px;
  text-align: center;
}

.cart-container {
  display: grid;
  grid-template-columns: 1fr;
  gap: 30px;
}

.cart-table {
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 5px 15px hsla(0, 0%, 0%, 0.1);
  padding: 20px;
  overflow-x: auto;
}

.cart-table table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

.cart-table th,
.cart-table td {
  padding: 12px 8px;
  font-size: var(--fs-7);
  color: var(--onyx);
  vertical-align: middle;
  border-bottom: 1px solid var(--gainsboro);
}

.cart-table th {
  font-weight: var(--fw-600);
  color: var(--rich-black-fogra-29);
  text-transform: uppercase;
  border-bottom: 2px solid var(--gainsboro);
}

/* Column widths and alignments */
.col-stt { 
  width: 5%; 
  text-align: center; 
}
.col-image { 
  width: 15%; 
  text-align: center; 
}
.col-name { 
  width: 25%; 
  text-align: left; 
}
.col-price { 
  width: 15%; 
  text-align: right; 
}
.col-quantity { 
  width: 15%; 
  text-align: right; 
}
.col-total { 
  width: 15%; 
  text-align: right; 
}
.col-remove { 
  width: 10%; 
  text-align: right; 
}

.cart-item {
  display: flex;
  align-items: center;
  gap: 10px;
  justify-content: center;
}

.cart-item-img {
  width: 80px;
  height: 80px;
  border-radius: 5px;
  overflow: hidden;
  margin: 0 auto;
}

.cart-item-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cart-item-info {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.cart-item-title {
  font-size: var(--fs-6);
  font-weight: var(--fw-500);
  color: var(--rich-black-fogra-29);
}

.cart-item-size {
  font-size: var(--fs-8);
  color: var(--onyx);
}

.quantity-input {
  display: flex;
  align-items: center;
  gap: 6px;
  width: 90px;
  margin-left: auto;
}

.quantity-btn {
  width: 32px;
  height: 32px;
  background: var(--cultured);
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--fs-7);
  transition: var(--transition-1);
  border: none;
  cursor: pointer;
}

.quantity-btn:hover {
  background: var(--bittersweet);
  color: var(--white);
}

.quantity-input input {
  padding: 6px;
  border: 1px solid var(--gainsboro);
  border-radius: 5px;
  text-align: center;
  font-size: var(--fs-7);
  width: 40px;
}

.cart-item-price {
  font-size: var(--fs-7);
  font-weight: var(--fw-600);
  color: var(--bittersweet);
  margin-left: auto;
}

.remove-btn {
  color: var(--bittersweet);
  font-size: var(--fs-7);
  background: var(--cultured);
  padding: 6px 10px;
  border-radius: 5px;
  transition: var(--transition-1);
  border: none;
  cursor: pointer;
  display: block;
  margin-left: auto;
}

.remove-btn:hover {
  background: var(--salmon);
  color: var(--white);
}

.cart-summary {
  background: var(--white);
  border-radius: 10px;
  box-shadow: 0 5px 15px hsla(0, 0%, 0%, 0.1);
  padding: 20px;
}

.summary-title {
  font-size: var(--fs-4);
  font-weight: var(--fw-600);
  margin-bottom: 20px;
  text-align: center;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  font-size: var(--fs-7);
  color: var(--onyx);
}

.summary-item span:last-child {
  font-weight: var(--fw-600);
  color: var(--rich-black-fogra-29);
}

.summary-total {
  border-top: 1px solid var(--gainsboro);
  padding-top: 15px;
  margin-top: 15px;
  font-size: var(--fs-6);
  font-weight: var(--fw-700);
  color: var(--bittersweet);
}

.checkout-btn {
  width: 100%;
  background: var(--bittersweet);
  color: var(--white);
  padding: 12px;
  border-radius: 5px;
  font-size: var(--fs-7);
  font-weight: var(--fw-500);
  transition: var(--transition-1);
  border: 1px solid transparent;
  cursor: pointer;
  display: flex; /* Thêm flexbox để căn giữa */
  justify-content: center; /* Căn giữa ngang */
  align-items: center; /* Căn giữa dọc */
}

.checkout-btn:hover {
  color: rgb(255, 110, 97);
  background: none;
  border: 1px solid rgb(255, 110, 97);
}

.continue-shopping {
  display: block;
  text-align: center;
  margin-top: 15px;
  font-size: var(--fs-7);
  color: var(--bittersweet);
  transition: var(--transition-1);
}

.continue-shopping:hover {
  color: var(--salmon);
}

.empty-cart {
  text-align: center;
  font-size: var(--fs-6);
  color: var(--onyx);
  padding: 40px;
  background: var(--cultured);
  border-radius: 10px;
}

@media (min-width: 768px) {
  .cart-container { 
    grid-template-columns: 2fr 1fr; 
  }
  .cart-table { 
    overflow-x: visible; 
  }
  .cart-table th, 
  .cart-table td { 
    padding: 15px 10px; 
  }
}

@media (min-width: 1200px) {
  .cart-item-img { 
    width: 100px; 
    height: 100px; 
  }
}
</style>