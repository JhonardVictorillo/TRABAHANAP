<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MinglaGawa - Discover Freelance Services</title>
  <link rel="stylesheet" href="{{('css/home.css')}}" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
</head>
<body>
 
  <header class="header">
    <div class="logo-header">
      <!-- <span class="logo-icon">MG</span> -->
      Mingla<span class="brand-span">Gawa</span>
    </div>
    <nav class="navbar">
      <a href="#home" class="active">Home</a>
      <a href="#category">Categories</a>
      <a href="#how-it-works">How It Works</a>
      <a href="#contact">Contact</a>
      <a href="{{route('login')}}"><button class="logbtn">Login</button></a>
      <a href="{{route('register.form')}}"><button class="regisbtn">Register</button></a>
    </nav>
  </header>


  <section class="home" id="home">
    <div class="tagline" data-aos="fade-right">
      <h1>
        Discover <span class="highlight">freelance services</span><br>
        that perfectly align with<br>
        your business goals.
      </h1>
      <p>Helping you find the right service for your needs</p>
      <div class="search-container">
        <div class="search-box">
          <input type="text" placeholder="Search...." />
          <span class="icon">
            <i class='bx bx-search'></i>
          </span>
        </div>
      </div>
    </div>
    <div class="hero-image" data-aos="fade-left">
      <img src="images/pic1.png" alt="Service Illustration" />
    </div>
  </section>

  <section class="category-container" id="category">
    <h2 data-aos="fade-up">Explore Our Categories</h2>
    <div class="categories">
  
      <div class="category" data-aos="fade-up" data-aos-delay="100">
        <img src="images/art&media.jpg" alt="Art & Media" />
        <h3>Art & Media</h3>
        <p>Graphic design, photography, video editing, etc.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      <div class="category" data-aos="fade-up" data-aos-delay="150">
        <img src="images/grooming.jpg" alt="Grooming" />
        <h3>Grooming</h3>
        <p>Pet grooming, personal care, hair styling, etc.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      <div class="category" data-aos="fade-up" data-aos-delay="200">
        <img src="images/technician.jpg" alt="Technicians" />
        <h3>Technicians</h3>
        <p>Electrical repairs, maintenance, installation services.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      <div class="category" data-aos="fade-up" data-aos-delay="250">
        <img src="images/housekeeping.jpg" alt="Housekeeping" />
        <h3>Housekeeping</h3>
        <p>Cleaning, laundry and housekeeping services.</p>
        <a href="#" class="btn">View Services</a>
      </div>
    <div class="category" data-aos="fade-up" data-aos-delay="100">
        <img src="images/food&pastries.jpg" alt="Art & Media" />
        <h3>Food & Pastries</h3>
        <p>Graphic design, photography, video editing, etc.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      <div class="category" data-aos="fade-up" data-aos-delay="150">
        <img src="images/party-events.jpg" alt="Grooming" />
        <h3>Party-events</h3>
        <p>Pet grooming, personal care, hair styling, etc.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      <div class="category" data-aos="fade-up" data-aos-delay="200">
        <img src="images/fashion&beauty.jpg" alt="Technicians" />
        <h3>Fashion & Beauty</h3>
        <p>Electrical repairs, maintenance, installation services.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      <div class="category" data-aos="fade-up" data-aos-delay="250">
        <img src="images/plumber.jpg" alt="Housekeeping" />
        <h3>Plumber</h3>
        <p>Cleaning, laundry and housekeeping services.</p>
        <a href="#" class="btn">View Services</a>
      </div>
      
    </div>
  </section>

 
  <section class="how-it-works" id="how-it-works">
    <h2 data-aos="fade-up">How It Works</h2>
    <div class="steps">
      <div class="step" data-aos="fade-up" data-aos-delay="100">
        <div class="step-number">1</div>
        <h3>Search & Discover</h3>
        <p>Find the services that match your needs using our advanced search.</p>
      </div>
      <div class="step" data-aos="fade-up" data-aos-delay="200">
        <div class="step-number">2</div>
        <h3>Connect</h3>
        <p>Reach out to freelance professionals and discuss your project.</p>
      </div>
      <div class="step" data-aos="fade-up" data-aos-delay="300">
        <div class="step-number">3</div>
        <h3>Collaborate</h3>
        <p>Work together to complete projects and achieve success.</p>
      </div>
    </div>
  </section>

  <section class="contact" id="contact">
    <h2 data-aos="fade-up">Contact Us</h2>
    <p data-aos="fade-up">Have questions? We are here to help you.</p>
    <div class="contact-details" data-aos="fade-up" data-aos-delay="100">
      <p><i class="fas fa-envelope"></i> Email: minglagawa@gmail.com</p>
      <p><i class="fas fa-phone"></i> Phone: +1234567890</p>
      <p><i class="fas fa-map-marker-alt"></i> Upper Pakigne Minglanilla</p>
    </div>
  </section>


  <footer class="footer" id="footer">
    <div class="container">
      <div class="row">
        <div class="footer-col">
          <h4>About</h4>
          <p>MinglaGawa connects you with top-rated freelance services for all your business needs.</p>
        </div>
        <div class="footer-col">
          <h4>Team</h4>
          <ul>
            <li><a href="#">Jhonard</a></li>
            <li><a href="#">Cristian</a></li>
            <li><a href="#">ELjohn</a></li>
            <li><a href="#">Aljun</a></li>
            <li><a href="#">Kathlen</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contact</h4>
          <ul>
            <li><a href="#">support@minglagawa.com</a></li>
            <li><a href="#">+1234567890</a></li>
            <li><a href="#">Upper Pakigne Minglanilla</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Follow Us</h4>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    AOS.init({ duration: 800, once: true });
    
    // Service buttons popup
    const serviceButtons = document.querySelectorAll('.category .btn');
    const messagePopup = document.createElement('div');
    
    function showPopup(message) {
      messagePopup.classList.add('popup-message');
      messagePopup.innerHTML = `
        <div class="popup-content">
          <p>${message}</p>
          <button class="close-popup">Close</button>
        </div>
      `;
      document.body.appendChild(messagePopup);
      const closeBtn = messagePopup.querySelector('.close-popup');
      closeBtn.addEventListener('click', () => {
        messagePopup.style.display = 'none';
      });
    }
    
    serviceButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const isLoggedIn = localStorage.getItem('isLoggedIn');
        if (!isLoggedIn) {
          showPopup("Please log in or register to view our services.");
        } else {
          window.location.href = '/services-page.html';
        }
      });
    });
    
    // Navigation active link and smooth scroll
    const navLinks = document.querySelectorAll('.navbar a');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        navLinks.forEach(link => link.classList.remove('active'));
        link.classList.add('active');
        const sectionId = link.getAttribute('href').substring(1);
        const section = document.getElementById(sectionId);
        if (section) {
          e.preventDefault();
          section.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
  </script>
</body>
</html>
