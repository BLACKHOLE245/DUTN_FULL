<template>
      <main>
    <article>
      <section class="section hero" style="background-image: url('/src/assets/images/hero-banner.png')">
        <div class="container">
          <h2 class="h1 hero-title">Bộ Sưu Tập<strong style="color: hsl(5, 100%, 69%);">Mùa Hè Mới</strong></h2>
          <p class="hero-text">
            Hãy nhanh chóng tận dụng những lợi ích thay thế trong khi dẫn đầu các chất xúc tác cho sự thay đổi. 
            Tận dụng toàn cầu một loạt các khả năng lãnh đạo đã được mở rộng.
          </p>
          <button class="btn btn-primary">
            <span>Mua Ngay</span>
            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
          </button>
        </div>
      </section>
      <section class="section collection">
        <div class="container">
          <ul class="collection-list has-scrollbar">
            <li>
              <div class="collection-card" style="background-image: url('/src/assets/images/collection-1.jpg')">
                <h3 class="h4 card-title">Bộ sưu tập nam</h3>
                <a href="#" class="btn btn-secondary">
                  <span>Khám phá tất cả</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
            <li>
              <div class="collection-card" style="background-image: url('/src/assets/images/collection-2.jpg')">
                <h3 class="h4 card-title">Bộ sưu tập nữ</h3>
                <a href="#" class="btn btn-secondary">
                  <span>Khám phá tất cả</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
            <li>
              <div class="collection-card" style="background-image: url('/src/assets/images/collection-3.jpg')">
                <h3 class="h4 card-title">Bộ sưu tập thể thao</h3>
                <a href="#" class="btn btn-secondary">
                  <span>Khám phá tất cả</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </section>
      <section class="section product">
        <div class="container">
          <h2 class="h2 section-title">Sản phẩm bán chạy nhất</h2>
          <ul class="filter-list">
            <li><button class="filter-btn active">Tất cả</button></li>
            <li><button class="filter-btn">Nike</button></li>
            <li><button class="filter-btn">Adidas</button></li>
            <li><button class="filter-btn">Puma</button></li>
            <li><button class="filter-btn">Bata</button></li>
            <li><button class="filter-btn">Apex</button></li>
          </ul>
          <ul class="product-list">
            <li class="product-item">
              <div class="product-card" tabindex="0">
                <figure class="card-banner">
                  <img src="../assets/images/product-1.jpg" width="312" height="350" loading="lazy" alt="Running Sneaker Shoes" class="image-contain">
                  <div class="card-badge">Mới</div>
                  <ul class="card-action-list">
                    <li class="card-action-item">
                      <button class="card-action-btn" aria-labelledby="card-label-1">
                        <ion-icon name="cart-outline"></ion-icon>
                      </button>
                      <div class="card-action-tooltip" id="card-label-1">Thêm vào giỏ hàng</div>
                    </li>
                    <li class="card-action-item">
                      <button class="card-action-btn" aria-labelledby="card-label-2">
                        <ion-icon name="heart-outline"></ion-icon>
                      </button>
                      <div class="card-action-tooltip" id="card-label-2">Thêm vào danh sách yêu thích</div>
                    </li>
                    <li class="card-action-item">
                      <button class="card-action-btn" aria-labelledby="card-label-3">
                        <ion-icon name="eye-outline"></ion-icon>
                      </button>
                      <div class="card-action-tooltip" id="card-label-3">Xem nhanh</div>
                    </li>
                    <li class="card-action-item">
                      <button class="card-action-btn" aria-labelledby="card-label-4">
                        <ion-icon name="repeat-outline"></ion-icon>
                      </button>
                      <div class="card-action-tooltip" id="card-label-4">So sánh</div>
                    </li>
                  </ul>
                </figure>
                <div class="card-content">
                  <div class="card-cat">
                    <a href="#" class="card-cat-link">Nam </a> /
                    <a href="#" class="card-cat-link">Nữ </a>
                  </div>
                  <h3 class="h3 card-title"><a href="#">Running Sneaker Shoes</a></h3>
                  <data class="card-price" value="180.85">$180.85</data>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </section>
      <section class="section cta">
        <div class="container">
          <ul class="cta-list">
            <li>
              <div class="cta-card" style="background-image: url('/src/assets/images/cta-1.jpg')">
                <p class="card-subtitle">Giày Adidas</p>
                <h3 class="h2 card-title">Khuyến mại mùa hè giảm giá 50%</h3>
                <a href="#" class="btn btn-link">
                  <span>Mua ngay</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
            <li>
              <div class="cta-card" style="background-image: url('/src/assets/images/cta-2.jpg')">
                <p class="card-subtitle">Giày Nike</p>
                <h3 class="h2 card-title">Giúp bạn luôn thể thao</h3>
                <a href="#" class="btn btn-link">
                  <span>Mua ngay</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </section>
      <section class="section special">
        <div class="container">
          <div class="special-banner" style="background-image: url('/src/assets/images/special-banner.jpg')">
            <h2 class="h3 banner-title">Phiên bản xu hướng mới</h2>
            <a href="#" class="btn btn-link">
              <span>Khám phá tất cả</span>
              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </div>
          <div class="special-product">
            <h2 class="h2 section-title">
              <span class="text">Nike đặc biệt</span>
              <span class="line"></span>
            </h2>
            <ul class="has-scrollbar">
              <li class="product-item">
                <div class="product-card" tabindex="0">
                  <figure class="card-banner">
                    <img src="../assets/images/product-1.jpg" width="312" height="350" loading="lazy" alt="Running Sneaker Shoes" class="image-contain">
                    <div class="card-badge">Mới</div>
                    <ul class="card-action-list">
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-1">
                          <ion-icon name="cart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-1">Thêm vào giỏ hàng</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-2">
                          <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-2">Thêm vào danh sách yêu thích</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-3">
                          <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-3">Xem nhanh</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-4">
                          <ion-icon name="repeat-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-4">So sánh</div>
                      </li>
                    </ul>
                  </figure>
                  <div class="card-content">
                    <div class="card-cat">
                      <a href="#" class="card-cat-link">Nam</a> /
                      <a href="#" class="card-cat-link">Nữ</a>
                    </div>
                    <h3 class="h3 card-title"><a href="#">Running Sneaker Shoes</a></h3>
                    <data class="card-price" value="180.85">$180.85</data>
                  </div>
                </div>
              </li>
              <li class="product-item">
                <div class="product-card" tabindex="0">
                  <figure class="card-banner">
                    <img src="../assets/images/product-2.jpg" width="312" height="350" loading="lazy" alt="Leather Nams Slipper" class="image-contain">
                    <ul class="card-action-list">
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-1">
                          <ion-icon name="cart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-1">Thêm vào giỏ hàng</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-2">
                          <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-2">Thêm vào danh sách yêu thích</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-3">
                          <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-3">Xem nhanh</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-4">
                          <ion-icon name="repeat-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-4">So sánh</div>
                      </li>
                    </ul>
                  </figure>
                  <div class="card-content">
                    <div class="card-cat">
                      <a href="#" class="card-cat-link">Nam</a> /
                      <a href="#" class="card-cat-link">Sports</a>
                    </div>
                    <h3 class="h3 card-title"><a href="#">Leather Nams Slipper</a></h3>
                    <data class="card-price" value="190.85">$190.85</data>
                  </div>
                </div>
              </li>
              <li class="product-item">
                <div class="product-card" tabindex="0">
                  <figure class="card-banner">
                    <img src="../assets/images/product-3.jpg" width="312" height="350" loading="lazy" alt="Simple Fabric Shoe" class="image-contain">
                    <div class="card-badge">Mới</div>
                    <ul class="card-action-list">
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-1">
                          <ion-icon name="cart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-1">Thêm vào giỏ hàng</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-2">
                          <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-2">Thêm vào danh sách yêu thích</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-3">
                          <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-3">Xem nhanh</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-4">
                          <ion-icon name="repeat-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-4">So sánh</div>
                      </li>
                    </ul>
                  </figure>
                  <div class="card-content">
                    <div class="card-cat">
                      <a href="#" class="card-cat-link">Nam</a> /
                      <a href="#" class="card-cat-link">Nữ</a>
                    </div>
                    <h3 class="h3 card-title"><a href="#">Simple Fabric Shoe</a></h3>
                    <data class="card-price" value="160.85">$160.85</data>
                  </div>
                </div>
              </li>
              <li class="product-item">
                <div class="product-card" tabindex="0">
                  <figure class="card-banner">
                    <img src="../assets/images/product-4.jpg" width="312" height="350" loading="lazy" alt="Air Jordan 7 Retro" class="image-contain">
                    <div class="card-badge"> -25%</div>
                    <ul class="card-action-list">
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-1">
                          <ion-icon name="cart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-1">Thêm vào giỏ hàng</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-2">
                          <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-2">Thêm vào danh sách yêu thích</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-3">
                          <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-3">Xem nhanh</div>
                      </li>
                      <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-4">
                          <ion-icon name="repeat-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-4">So sánh</div>
                      </li>
                    </ul>
                  </figure>
                  <div class="card-content">
                    <div class="card-cat">
                      <a href="#" class="card-cat-link">Nam</a> /
                      <a href="#" class="card-cat-link">Sports</a>
                    </div>
                    <h3 class="h3 card-title"><a href="#">Air Jordan 7 Retro</a></h3>
                    <data class="card-price" value="170.85">$170.85 <del>$200.21</del></data>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </section>
      <section class="section service">
        <div class="container">
          <ul class="service-list">
            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img src="../assets/images/service-1.png" width="53" height="28" loading="lazy" alt="Service icon">
                </div>
                <div>
                  <h3 class="h4 card-title">Miễn phí vận chuyển</h3>
                  <p class="card-text">Tất cả các đơn hàng trên <span>$150</span></p>
                </div>
              </div>
            </li>
            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img src="../assets/images/service-2.png" width="43" height="35" loading="lazy" alt="Service icon">
                </div>
                <div>
                  <h3 class="h4 card-title">Thanh toán nhanh</h3>
                  <p class="card-text">Thanh toán an toàn 100%</p>
                </div>
              </div>
            </li>
            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img src="../assets/images/service-3.png" width="40" height="40" loading="lazy" alt="Service icon">
                </div>
                <div>
                  <h3 class="h4 card-title">Trả hàng miễn phí</h3>
                  <p class="card-text">Hoàn tiền trong vòng 14 ngày</p>
                </div>
              </div>
            </li>
            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img src="../assets/images/service-4.png" width="40" height="40" loading="lazy" alt="Service icon">
                </div>
                <div>
                  <h3 class="h4 card-title">Hỗ trợ 24/7</h3>
                  <p class="card-text">Nhận hỗ trợ nhanh chóng</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </section>
      <section class="section insta-post">
        <ul class="insta-post-list has-scrollbar">
          <li class="insta-post-item">
            <img src="../assets/images/insta-1.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-2.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-3.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-4.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-5.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-6.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-7.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
          <li class="insta-post-item">
            <img src="../assets/images/insta-8.jpg" width="100" height="100" loading="lazy" alt="Instagram post" class="insta-post-banner image-contain">
            <a href="#" class="insta-post-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>
        </ul>
      </section>
    </article>
  </main>
</template>