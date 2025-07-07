<template>
  <div class="admin-dashboard">
    <!-- ======================= Cards ================== -->
    <div class="cardBox">
      <div class="card">
        <div>
          <div class="numbers">1,504</div>
          <div class="cardName">Lượt Xem</div>
        </div>
        <div class="iconBx">
          <ion-icon name="eye-outline"></ion-icon>
        </div>
      </div>

      <div class="card">
        <div>
          <div class="numbers">80</div>
          <div class="cardName">Bán Hàng</div>
        </div>
        <div class="iconBx">
          <ion-icon name="cart-outline"></ion-icon>
        </div>
      </div>

      <div class="card">
        <div>
          <div class="numbers">284</div>
          <div class="cardName">Bình Luận</div>
        </div>
        <div class="iconBx">
          <ion-icon name="chatbubbles-outline"></ion-icon>
        </div>
      </div>

      <div class="card">
        <div>
          <div class="numbers">$7,842</div>
          <div class="cardName">Kiếm Tiền</div>
        </div>
        <div class="iconBx">
          <ion-icon name="cash-outline"></ion-icon>
        </div>
      </div>
    </div>

    <!-- ================ Order Details List ================= -->
    <div class="details">
      <div class="recentOrders">
        <div class="cardHeader">
          <h2>Đơn Hàng Gần Đây</h2>
          <router-link to="/admin/orders" class="btn">Xem Tất Cả</router-link>
        </div>

        <table>
          <thead>
            <tr>
              <td>Tên Sản Phẩm</td>
              <td>Giá</td>
              <td>Thanh Toán</td>
              <td>Trạng Thái</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in recentOrders" :key="order.id">
              <td>{{ order.productName }}</td>
              <td>{{ order.price }}</td>
              <td>{{ order.payment }}</td>
              <td>
                <span :class="['status', order.status.toLowerCase()]">
                  {{ order.statusText }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ================= New Customers ================ -->
      <div class="recentCustomers">
        <div class="cardHeader">
          <h2>Khách Hàng</h2>
        </div>

        <table>
          <tr v-for="customer in recentCustomers" :key="customer.id">
            <td width="60px">
              <div class="imgBx">
                <img :src="customer.avatar" :alt="customer.name">
              </div>
            </td>
            <td>
              <h4>{{ customer.name }} <br> <span>{{ customer.location }}</span></h4>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

// Data mẫu cho đơn hàng
const recentOrders = reactive([
  {
    id: 1,
    productName: 'Nike Air Max',
    price: '$120',
    payment: 'Paid',
    status: 'delivered',
    statusText: 'Delivered'
  },
  {
    id: 2,
    productName: 'Adidas Ultraboost',
    price: '$110',
    payment: 'Due',
    status: 'pending',
    statusText: 'Pending'
  },
  {
    id: 3,
    productName: 'Puma Suede',
    price: '$80',
    payment: 'Paid',
    status: 'return',
    statusText: 'Return'
  },
  {
    id: 4,
    productName: 'Converse Chuck',
    price: '$60',
    payment: 'Due',
    status: 'inprogress',
    statusText: 'In Progress'
  },
  {
    id: 5,
    productName: 'Vans Old Skool',
    price: '$70',
    payment: 'Paid',
    status: 'delivered',
    statusText: 'Delivered'
  }
])

// Data mẫu cho khách hàng
const recentCustomers = reactive([
  {
    id: 1,
    name: 'David',
    location: 'Italy',
    avatar: '/assets/imgs/customer02.jpg'
  },
  {
    id: 2,
    name: 'Amit',
    location: 'India',
    avatar: '/assets/imgs/customer01.jpg'
  },
  {
    id: 3,
    name: 'John',
    location: 'USA',
    avatar: '/assets/imgs/customer02.jpg'
  },
  {
    id: 4,
    name: 'Sarah',
    location: 'UK',
    avatar: '/assets/imgs/customer01.jpg'
  },
  {
    id: 5,
    name: 'Mike',
    location: 'Canada',
    avatar: '/assets/imgs/customer02.jpg'
  }
])
</script>

<!-- <style scoped>
.admin-dashboard {
  padding: 20px;
}

.cardBox {
  position: relative;
  width: 100%;
  padding: 20px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-gap: 30px;
}

.cardBox .card {
  position: relative;
  background: #fff;
  padding: 30px;
  border-radius: 20px;
  display: flex;
  justify-content: space-between;
  cursor: pointer;
  box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
}

.cardBox .card .numbers {
  position: relative;
  font-weight: 500;
  font-size: 2.5rem;
  color: #287bff;
}

.cardBox .card .cardName {
  color: #999;
  font-size: 1.1rem;
  margin-top: 5px;
}

.cardBox .card .iconBx {
  font-size: 3.5rem;
  color: #999;
}

.cardBox .card:hover {
  background: #287bff;
}

.cardBox .card:hover .numbers,
.cardBox .card:hover .cardName,
.cardBox .card:hover .iconBx {
  color: #fff;
}

.details {
  position: relative;
  width: 100%;
  padding: 20px;
  display: grid;
  grid-template-columns: 2fr 1fr;
  grid-gap: 30px;
  margin-top: 10px;
}

.details .recentOrders {
  position: relative;
  display: grid;
  min-height: 500px;
  background: #fff;
  padding: 20px;
  box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
  border-radius: 20px;
}

.details .cardHeader {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.cardHeader h2 {
  font-weight: 600;
  color: #287bff;
}

.cardHeader .btn {
  position: relative;
  padding: 5px 10px;
  background: #287bff;
  text-decoration: none;
  color: #fff;
  border-radius: 6px;
}

.details table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.details table thead td {
  font-weight: 600;
}

.details .recentOrders table tr {
  color: #222;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.details .recentOrders table tr:last-child {
  border-bottom: none;
}

.details .recentOrders table tbody tr:hover {
  background: #287bff;
  color: #fff;
}

.details .recentOrders table tr td {
  padding: 10px;
}

.details .recentOrders table tr td:last-child {
  text-align: end;
}

.details .recentOrders table tr td:nth-child(2) {
  text-align: end;
}

.details .recentOrders table tr td:nth-child(3) {
  text-align: center;
}

.status.delivered {
  padding: 2px 4px;
  background: #8de02c;
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
}

.status.pending {
  padding: 2px 4px;
  background: #e9b10a;
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
}

.status.return {
  padding: 2px 4px;
  background: #f00;
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
}

.status.inprogress {
  padding: 2px 4px;
  background: #1795ce;
  color: #fff;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
}

.recentCustomers {
  position: relative;
  display: grid;
  min-height: 500px;
  padding: 20px;
  background: #fff;
  box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
  border-radius: 20px;
}

.recentCustomers .imgBx {
  position: relative;
  width: 40px;
  height: 40px;
  border-radius: 50px;
  overflow: hidden;
}

.recentCustomers .imgBx img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.recentCustomers table tr td {
  padding: 12px 10px;
}

.recentCustomers table tr td h4 {
  font-size: 16px;
  font-weight: 500;
  line-height: 1.2rem;
}

.recentCustomers table tr td h4 span {
  font-size: 14px;
  color: #999;
}

.recentCustomers table tr:hover {
  background: #287bff;
  color: #fff;
}

.recentCustomers table tr:hover td h4 span {
  color: #fff;
}

/* Responsive */
@media (max-width: 991px) {
  .cardBox {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .details {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .cardBox {
    grid-template-columns: repeat(1, 1fr);
  }
  
  .cardBox .card .numbers {
    font-size: 1.8rem;
  }
  
  .cardBox .card .iconBx {
    font-size: 2.5rem;
  }
}
</style> -->