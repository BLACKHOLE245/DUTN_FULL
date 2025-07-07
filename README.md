📁 DATN/
│
├── 📂 backend/
│   ├── 📂 app/
│   │   ├── 📂 Http/
│   │   │   └── 📂 Controllers/
│   │   │       └── 📂 Api/
│   │   │           ├── AuthController.php
│   │   │           ├── BrandController.php
│   │   │           ├── CategoryController.php
│   │   │           ├── CommentController.php
│   │   │           ├── ProductController.php
│   │   │           ├── UserController.php
│   │   │           └── VoucherController.php
│   │   └── 📂 Models/
│   │       ├── Brand.php
│   │       ├── Category.php
│   │       ├── Comment.php
│   │       ├── Product.php
│   │       ├── Role.php
│   │       ├── User.php
│   │       └── Voucher.php
│   │
│   ├── 📂 public/
│   │   └── 📂 storage/
│   │       └── 📂 products/   (Chứa hình ảnh sản phẩm)
│   │
│   └── 📄 routes/api.php
│       → Định nghĩa các API routes cho:
│         • Auth (login/register)
│         • Users CRUD + Ban/Toggle
│         • Products, Brands, Categories
│         • Vouchers, Comments
│
├── 📂 frontend/
│   ├── 📂 src/
│   │   ├── 📂 components/
│   │   │   └── 📂 admin/
│   │   │       ├── Header.vue
│   │   │       └── Toolbar.vue
│   │   │
│   │   ├── 📂 layouts/
│   │   │   ├── DefaultLayout.vue   (Giao diện người dùng)
│   │   │   └── AdminLayout.vue     (Giao diện quản trị)
│   │   │
│   │   ├── 📂 pages/
│   │   │   ├── 📂 admin/
│   │   │   │   ├── AdminDashboard.vue
│   │   │   │   ├── AdminCustomers.vue
│   │   │   │   ├── AdminCategories.vue
│   │   │   │   ├── AdminProducts.vue
│   │   │   │   ├── AdminVouchers.vue
│   │   │   │   └── AdminComments.vue
│   │   │   └── 📂 (frontend)
│   │   │       ├── HomePage.vue
│   │   │       ├── AboutPage.vue
│   │   │       ├── BlogPage.vue
│   │   │       ├── ContactPage.vue
│   │   │       ├── CartPage.vue
│   │   │       ├── DetailPage.vue
│   │   │       ├── FavouritePage.vue
│   │   │       ├── LoginPage.vue
│   │   │       ├── ProductPage.vue
│   │   │       └── PayPage.vue
│   │   │
│   │   ├── 📄 router/index.js
│   │   │   → Router có thay đổi, xem lại giúp nha
│   │   │
│   │   ├── 📄 App.vue
│   │   │   → Router có thay đổi, xem lại giúp nha
│   │   └── 📄 main.js
│   │       → Router có thay đổi, xem lại giúp nha
│
└── Chúc cho nhóm chúng ta hoàn thành dự án thật suôn sẻ, hợp tác ăn ý và đạt được kết quả như kỳ vọng ! ❤️

