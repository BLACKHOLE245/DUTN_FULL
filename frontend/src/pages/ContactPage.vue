<template>
  <main>
    <section class="contact-section section">
      <div class="container contact-container">
        <div class="contact-map">
          <h2 class="h2">Vị trí của chúng tôi</h2>
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d582.4798973769348!2d106.62593440798723!3d10.853943123377373!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752a20d8555e69%3A0x743b1e9558fb89e0!2sQTSC%209%20Building!5e0!3m2!1svi!2s!4v1750005476686!5m2!1svi!2s"
            width="100%"
            height="400"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
        <div class="contact-details">
          <div class="contact-info">
            <h2 class="h2">Liên hệ với chúng tôi</h2>
            <ul class="contact-info-list">
              <li class="contact-info-item">
                <ion-icon name="location-outline"></ion-icon>
                <address>Đ. Số 3, Tân Hưng Thuận, Quận 12, Hồ Chí Minh, Việt Nam</address>
              </li>
              <li class="contact-info-item">
                <ion-icon name="mail-outline"></ion-icon>
                <a href="mailto:info@example.com">lovesan2704@gmail.com</a>
              </li>
              <li class="contact-info-item">
                <ion-icon name="call-outline"></ion-icon>
                <a href="tel:+1234567890">+84 987654321</a>
              </li>
            </ul>
          </div>
          <div class="contact-form">
            <h2 class="h2">Gửi tin nhắn cho chúng tôi</h2>
            <form>
              <div class="form-group">
                <label for="name">Họ và tên</label>
                <input
                  type="text"
                  id="name"
                  required
                  placeholder="Nhập tên của bạn"
                />
              </div>
              <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input
                  type="email"
                  id="email"
                  required
                  placeholder="Nhập email của bạn"
                />
              </div>
              <div class="form-group">
                <label for="message">Lời nhắn</label>
                <textarea
                  id="message"
                  required
                  placeholder="Điền tin nhắn của bạn"
                ></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<style scoped>
.contact-section {
  background: var(--cultured);
}

.contact-container {
  display: grid;
  gap: 40px;
}

.contact-map .h2 {
  margin-bottom: 20px;
}

.contact-map iframe {
  width: 100%;
  height: 400px;
  border: 1px solid var(--gainsboro);
  border-radius: 4px;
}

.contact-details {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.contact-info .h2 {
  margin-bottom: 20px;
}

.contact-info-list {
  display: grid;
  gap: 20px;
}

.contact-info-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
}

.contact-info-item ion-icon {
  font-size: 24px;
  color: var(--bittersweet);
  --ionicon-stroke-width: 40px;
}

.contact-info-item address,
.contact-info-item a {
  color: var(--onyx);
  font-family: var(--ff-roboto);
  font-size: var(--fs-7);
}

.contact-info-item a:is(:hover, :focus) {
  color: var(--bittersweet);
}

.contact-form .h2 {
  margin-bottom: 20px;
}

.contact-form form {
  display: grid;
  gap: 15px;
}

.form-group {
  display: grid;
  gap: 10px;
}

.form-group label {
  color: var(--rich-black-fogra-29);
  font-family: var(--ff-roboto);
  font-size: var(--fs-7);
  font-weight: var(--fw-500);
}

.form-group input,
.form-group textarea {
  background: var(--white);
  border: 1px solid var(--gainsboro);
  padding: 12px 15px;
  font-size: var(--fs-7);
  color: var(--onyx);
  transition: var(--transition-1);
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: var(--bittersweet);
  outline: none;
}

.btn.btn-primary {
  background: var(--bittersweet);
  color: var(--white);
  padding: 14px;
  border: 1px solid transparent;
  border-radius: 6px;
  font-size: var(--fs-7);
  font-weight: var(--fw-500);
  cursor: pointer;
  transition: background 0.3s ease;
  display: flex; /* Thêm flexbox để căn giữa */
  justify-content: center; /* Căn giữa ngang */
  align-items: center; /* Căn giữa dọc */
}

.btn.btn-primary:hover {
  color: rgb(255, 110, 97);
  background: none;
  border: 1px solid rgb(255, 110, 97);
}

@media (min-width: 575px) {
  :root {
    --fs-1: 4rem;
  }

  .container {
    max-width: 650px;
    margin-inline: auto;
  }

  .contact-container {
    grid-template-columns: 1fr 1fr;
    gap: 30px;
  }
}

@media (min-width: 768px) {
  :root {
    --fs-2: 3rem;
  }

  .container {
    max-width: 720px;
  }

  .contact-container {
    gap: 50px;
  }
}

@media (min-width: 992px) {
  :root {
    --fs-3: 2.4rem;
  }

  .container {
    max-width: 970px;
  }
}

@media (min-width: 1200px) {
  :root {
    --fs-1: 5rem;
    --fs-2: 3.6rem;
  }

  .container {
    max-width: 1280px;
  }

  .contact-map iframe {
    height: 500px;
  }
}
</style>