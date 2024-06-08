<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-section">
          <h3> {{ $t('links') }}</h3>
          <ul>
            <li><a href="#"> {{ $t('home') }}</a></li>
            <li><a href="#"> {{ $t('us') }}</a></li>
            <li><a href="#"> {{ $t('services') }}</a></li>
          </ul>
        </div>
        <div class="footer-section">                                                                                 
          <h3> {{ $t('contact') }}</h3>
          <p> {{ $t('address') }} 123 Calle Mallorca, Islas Baleares</p>
          <p> {{ $t('phone') }} +123 456 789</p>
          <p> {{ $t('email') }} <a href="mailto:fgonzalezviejo@gmail.com">booknow@gmail.com</a></p>
        </div>
        <div class="footer-section">
          <h3>{{ $t('social') }}</h3>
          <ul class="social-icons">
            <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-github"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 Book Now. {{ $t('copyright') }}</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer'
}
</script>

<style scoped>

.footer {
  background-color: #333;
  color: #fff;
  text-align: center;
  width: 100%;
  margin-top: 6vh
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.footer-content {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.footer-section {
  flex: 1;
  margin-right: 20px;
}

.footer-section h3 {
  font-size: 18px;
  margin-bottom: 20px;
}

.footer-section ul {
  list-style: none;
  padding: 0;
}

.footer-section ul li {
  margin-bottom: 10px;
}

.footer-section ul li a {
  color: #fff;
  text-decoration: none;
}

.social-icons {
  list-style: none;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.social-icons li {
  margin-right: 10px;
}

.social-icons li a {
  color: #fff;
  font-size: 24px;
}

.footer-bottom {
  background-color: #222;
  padding: 10px 0;
  text-align: center;
}

.footer-bottom p {
  margin: 0;
}
a{

  color: white;
}
</style>
