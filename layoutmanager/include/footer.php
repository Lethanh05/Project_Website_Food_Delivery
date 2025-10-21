<div id="footer">
    <div class="footer-container">
    <div class="footer-top">
      <div class="logo-section">
        <img src="../assets/img/logo1.png" alt="Five Friends Logo">
        <h1>Five Friends</h1>
      </div>
      <div class="social-icons" aria-label="Social media links">
        <a href="https://www.twitter.com" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="https://www.facebook.com" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="footer-middle">
      <div class="colorlib-col">
        <h2>Về chúng tôi</h2>
        <ul>
          <li>
            <i class="fa-solid fa-location-dot"></i>
            <span>02 Võ Oanh, Phường 25, Bình Thạnh, HCM, VN</span>
          </li>
          <li>
            <i class="fa-solid fa-phone-volume"></i>
            <span>0986 123 456</span>
          </li>
          <li>
            <i class="fa-solid fa-envelope"></i>
            <span>admin@fivefriend.webshop</span>
          </li>
          <li>
            <i class="fa-solid fa-calendar"></i>
            <span>Thứ 2 - Chủ Nhật: 7:00 AM - 11:00 PM</span>
          </li>
        </ul>
      </div>
      <div class="best-sellers">
        <h2>Best Sellers</h2>
        <img src="../assets/img/combo/best-seller.jpg" width="300" height="180" />
        <p>Combo Brew Tắc + Bánh Mì Que</p>
        <div class="stars" aria-label="5 star rating">
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
          <i class="fas fa-star" aria-hidden="true"></i>
        </div>
      </div>
      <div class="map-section">
        <h2>Google Map</h2>
        <iframe class="map-container" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.0887067578224!2d106.71414257480538!3d10.804517789345978!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175293dceb22197%3A0x755bb0f39a48d4a6!2sHo%20Chi%20Minh%20City%20University%20of%20Transport!5e0!3m2!1sen!2sus!4v1751371344833!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 Five Friends WebShop. All rights reserved.</p>
    </div>
    </div>
</div>
<style>
    #footer {
      margin-top: 20px;
      background-color: var(--footer-color);
    }

    .footer-container {
      width: 100%;
      padding: 20px;
      background-color: var(--footer-color);
      color: #e6e6a7;
      font-family: 'Poppins', sans-serif;
    }
    /* Footer Top */
    .footer-top {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #5a7f2f;
      padding-bottom: 24px;
    }
    .logo-section {
      display: flex;
      align-items: center;
      min-width: 200px;
    }
    .logo-section img {
      width: 100px;
      height: auto;
      object-fit: contain;
    }
    .logo-section h1 {
      margin: 0;
      font-size: 1.3rem;
      font-weight: bold;
      color: #CCCCCC;
    }
    .social-icons {
      display: flex;
      gap: 24px;
      margin-top: 16px;
      margin-right: 50px;
    }
    @media (min-width: 768px) {
      .social-icons {
        margin-top: 0;
      }
    }
    .social-icons a {
      border: 1px solid #e6e6a7;
      border-radius: 9999px;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e6e6a7;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    .social-icons a:hover {
      border-color: #CCCCCC;
      color: #CCCCCC;
    }
    /* Footer Middle */
    .footer-middle {
      margin-top: 40px;
      display: flex;
      flex-wrap: wrap;
      gap: 32px;
      justify-content: space-between;
    }
    .colorlib-col {
      flex: 1 1 250px;
      min-width: 250px;
    }
    .colorlib-col h2 {
      color: #CCCCCC;
      font-size: 1.125rem;
      margin-bottom: 24px;
    }
    .colorlib-col ul {
      list-style: none;
      padding: 0;
      margin: 0;
      color: #a0a07a;
      font-size: 0.875rem;
    }
    .colorlib-col ul li {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
    }
    .colorlib-col ul li i {
      font-size: 16px;
      color: #a0a07a;
      min-width: 20px;
      text-align: center;
    }
    /* Best Sellers */
    .best-sellers {
      flex: 1 1 300px;
      min-width: 300px;
    }
    .best-sellers h2 {
      color: #CCCCCC;
      font-size: 1.125rem;
      margin-bottom: 24px;
    }
    .best-sellers img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      margin-bottom: 12px;
      border-radius: 4px;
    }
    .best-sellers p {
      margin: 0 0 8px 0;
      color: #e6e6a7;
      font-size: 0.875rem;
    }
    .stars {
      color: #e6e6a7;
      font-size: 1.25rem;
      letter-spacing: 2px;
    }
    /* Map Section */
    .map-section {
      flex: 1 1 300px;
      min-width: 300px;
    }
    .map-section h2 {
      color: #CCCCCC;
      font-size: 1.125rem;
      margin-bottom: 24px;
    }
    .map-container {
      width: 100%;
      height: 180px;
      border: 0;
      border-radius: 4px;
      box-shadow: 0 0 8px rgba(0,0,0,0.15);
    }
    /* Footer Bottom */
    .footer-bottom {
      border-top: 1px solid #5a7f2f;
      margin-top: 40px;
      padding-top: 24px;
      font-size: 0.875rem;
      color: #a0a07a;
      text-align: center;
    }
    /* Responsive adjustments */
    @media (max-width: 767px) {
      .footer-middle {
        flex-direction: column;
      }
      .best-sellers, .map-section, .colorlib-col {
        min-width: 100%;
      }
      .social-icons {
        justify-content: flex-start;
      }
    }
</style>
