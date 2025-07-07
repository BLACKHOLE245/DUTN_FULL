<template>
  <main>
    <!-- Hero Banner -->
    <section class="hero" style="background-image: url('https://via.placeholder.com/1280x580?text=ShoeTrendz+Banner');">
      <div class="container">
        <h1 class="h1 hero-title">
          Discover Your <strong>Perfect Pair</strong>
        </h1>
        <p class="hero-text">
          Explore the latest trends in footwear at ShoeTrendz. From sneakers to boots, find the style that fits you.
        </p>
        <router-link to="/shop" class="btn btn-primary">Shop Now</router-link>
      </div>
    </section>

    <!-- About Section -->
    <section class="section">
      <div class="container">
        <h2 class="h2 section-title">About ShoeTrendz</h2>
        <p class="hero-text" style="max-width: 60ch; margin-bottom: 20px;">
          ShoeTrendz is your one-stop destination for high-quality, stylish footwear. We are passionate about offering a diverse range of shoes that cater to every taste and occasion, ensuring comfort and durability with every step.
        </p>
        <ul style="font-family: var(--ff-roboto); color: var(--onyx); font-size: var(--fs-7); line-height: 1.8;">
          <li><strong>Mission:</strong> To provide trendy and comfortable shoes that empower our customers to express their unique style.</li>
          <li><strong>Vision:</strong> To be the leading online footwear retailer, known for exceptional quality and customer satisfaction.</li>
          <li><strong>Values:</strong> Quality, Style, and Sustainability.</li>
        </ul>
      </div>
    </section>

    <!-- Features Section -->
    <section class="section special">
      <div class="container">
        <h2 class="h2 section-title">
          <span class="text">Why Choose Us</span>
          <span class="line"></span>
        </h2>
        <ul class="service-list">
          <li class="service-item" v-for="feature in features" :key="feature.title">
            <div class="service-card">
              <div class="card-icon">
                <img :src="feature.icon" :alt="feature.title + ' Icon'">
              </div>
              <div>
                <h3 class="h3 card-title">{{ feature.title }}</h3>
                <p class="card-text" v-html="feature.text"></p>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </section>

    <!-- Interesting Facts Section -->
    <section class="section">
      <div class="container">
        <h2 class="h2 section-title">Fun Facts About ShoeTrendz</h2>
        <div class="cta-list">
          <div class="cta-card" v-for="fact in facts" :key="fact.title" :style="{ backgroundImage: `url(${fact.image})` }">
            <p class="card-subtitle">{{ fact.subtitle }}</p>
            <h3 class="h3 card-title">{{ fact.title }}</h3>
            <p class="hero-text">{{ fact.text }}</p>
            <router-link :to="fact.link" class="btn btn-link">{{ fact.linkText }}</router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-top section">
        <div class="container">
          <div class="footer-brand">
            <router-link to="/" class="logo">
              <img src="https://via.placeholder.com/150x50?text=ShoeTrendz" alt="ShoeTrendz Logo" width="150" height="50">
            </router-link>
            <ul class="social-list">
              <li v-for="social in socialLinks" :key="social.name">
                <a :href="social.url" class="social-link">
                  <ion-icon :name="social.icon"></ion-icon>
                </a>
              </li>
            </ul>
          </div>
          <div class="footer-link-box">
            <ul class="footer-list">
              <li>
                <h3 class="h3 footer-list-title">Contact Us</h3>
              </li>
              <li v-for="contact in contacts" :key="contact.text">
                <a :href="contact.url" class="footer-link">
                  <ion-icon :name="contact.icon"></ion-icon>
                  <span class="footer-link-text">{{ contact.text }}</span>
                </a>
              </li>
            </ul>
            <ul class="footer-list">
              <li>
                <h3 class="h3 footer-list-title">Quick Links</h3>
              </li>
              <li v-for="link in quickLinks" :key="link.name">
                <router-link :to="link.path" class="footer-link">{{ link.name }}</router-link>
              </li>
            </ul>
            <ul class="footer-list">
              <li>
                <h3 class="h3 footer-list-title">Customer Service</h3>
              </li>
              <li v-for="service in customerServices" :key="service.name">
                <router-link :to="service.path" class="footer-link">{{ service.name }}</router-link>
              </li>
            </ul>
            <ul class="footer-list">
              <li>
                <h3 class="h3 footer-list-title">Newsletter</h3>
              </li>
              <li>
                <p class="newsletter-text">Subscribe to get the latest updates and offers.</p>
              </li>
              <li>
                <div class="newsletter-form">
                  <input type="email" v-model="newsletterEmail" class="newsletter-input" placeholder="Enter your email">
                  <button class="btn btn-primary" @click="subscribeNewsletter">Subscribe</button>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p class="copyright">
          © 2025 <router-link to="/" class="copyright-link">ShoeTrendz</router-link>. All Rights Reserved.
        </p>
      </div>
    </footer>
  </main>
</template>

<script>
export default {
  name: 'Home',
  data() {
    return {
      newsletterEmail: '',
      features: [
        {
          title: 'Free Shipping',
          text: 'Enjoy <span>free shipping</span> on all orders over $50.',
          icon: 'https://via.placeholder.com/30?text=Icon',
        },
        {
          title: 'Premium Quality',
          text: 'Crafted with <span>top materials</span> for lasting comfort.',
          icon: 'https://via.placeholder.com/30?text=Icon',
        },
        {
          title: 'Easy Returns',
          text: '<span>Hassle-free</span> 30-day return policy.',
          icon: 'https://via.placeholder.com/30?text=Icon',
        },
      ],
      facts: [
        {
          subtitle: 'Did You Know?',
          title: 'Over 10,000 Pairs Sold!',
          text: 'Our customers have styled their lives with over 10,000 pairs of shoes from ShoeTrendz in just one year.',
          image: 'https://via.placeholder.com/600x400?text=Fact+1',
          link: '/shop',
          linkText: 'Explore Collection',
        },
        {
          subtitle: 'Eco-Friendly',
          title: 'Sustainable Materials',
          text: 'We use recycled materials in 50% of our shoe collections to promote a greener planet.',
          image: 'https://via.placeholder.com/600x400?text=Fact+2',
          link: '/about',
          linkText: 'Learn More',
        },
      ],
      socialLinks: [
        { name: 'Facebook', url: '#', icon: 'logo-facebook' },
        { name: 'Twitter', url: '#', icon: 'logo-twitter' },
        { name: 'Instagram', url: '#', icon: 'logo-instagram' },
      ],
      contacts: [
        { text: '123 Fashion Street, City', url: '#', icon: 'location-outline' },
        { text: '+123 456 7890', url: 'tel:+1234567890', icon: 'call-outline' },
        { text: 'support@shoetrendz.com', url: 'mailto:support@shoetrendz.com', icon: 'mail-outline' },
      ],
      quickLinks: [
        { name: 'Shop', path: '/shop' },
        { name: 'About', path: '/about' },
        { name: 'FAQ', path: '/faq' },
      ],
      customerServices: [
        { name: 'Returns', path: '/returns' },
        { name: 'Shipping', path: '/shipping' },
        { name: 'Privacy Policy', path: '/privacy' },
      ],
    };
  },
  methods: {
    subscribeNewsletter() {
      if (this.newsletterEmail) {
        alert(`Subscribed with ${this.newsletterEmail}!`);
        this.newsletterEmail = '';
      } else {
        alert('Please enter a valid email address.');
      }
    },
  },
};
</script>