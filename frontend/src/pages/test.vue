<template>
  <div>
    <h2>Đăng ký</h2>
    <form @submit.prevent="register">
      <input v-model="registerForm.name_user" placeholder="Tên người dùng" required><br>
      <input v-model="registerForm.email" type="email" placeholder="Email" required><br>
      <input v-model="registerForm.password" type="password" placeholder="Mật khẩu" required><br>
      <input v-model="registerForm.password_confirmation" type="password" placeholder="Xác nhận mật khẩu" required><br>
      <input v-model="registerForm.phone" placeholder="Số điện thoại"><br>
      <input v-model="registerForm.address" placeholder="Địa chỉ"><br>
      <input v-model="registerForm.role_id" type="number" placeholder="Role ID" required><br>
      <button type="submit">Đăng ký</button>
    </form>

    <h2>Đăng nhập</h2>
    <form @submit.prevent="login">
      <input v-model="loginForm.email" type="email" placeholder="Email" required><br>
      <input v-model="loginForm.password" type="password" placeholder="Mật khẩu" required><br>
      <button type="submit">Đăng nhập</button>
    </form>

    <div v-if="response">
      <h3>Kết quả:</h3>
      <pre>{{ response }}</pre>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      apiUrl: 'http://localhost:8000/api',
      registerForm: {
        name_user: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        address: '',
        role_id: ''
      },
      loginForm: {
        email: '',
        password: ''
      },
      response: null
    };
  },
  methods: {
    async register() {
      try {
        const res = await fetch(`${this.apiUrl}/register`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(this.registerForm)
        });
        const data = await res.json();
        this.response = data;
      } catch (err) {
        this.response = { error: 'Không thể kết nối tới API.' };
      }
    },
    async login() {
      try {
        const res = await fetch(`${this.apiUrl}/login`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(this.loginForm)
        });
        const data = await res.json();
        this.response = data;
      } catch (err) {
        this.response = { error: 'Không thể kết nối tới API.' };
      }
    }
  }
};
</script>

<style scoped>
input {
  margin-bottom: 5px;
  padding: 5px;
}
</style>
