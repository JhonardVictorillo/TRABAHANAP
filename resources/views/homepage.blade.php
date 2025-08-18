<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MinglaGawa - Discover Freelance Services</title>
  <link rel="stylesheet" href="{{asset('css/home.css')}}" />
   <link rel="stylesheet" href="{{asset('css/homeHeader.css')}}" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
</head>
<body>
 
@include('homepage.homepageHeader')

  <section class="home" id="home">
    <div class="tagline" data-aos="fade-right" data-aos-duration="800">
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
    <div class="hero-image" data-aos="fade-left" data-aos-duration="800">
      <img src="images/pic1.png" alt="Service Illustration" />
    </div>
  </section>

  <section class="category-container" id="category">
  <h2 class="category-title" data-aos="fade-up">Explore Our Categories</h2>

  <div class="categories-container" data-aos="fade-up">
    <div class="categories-scroll" id="categoriesScroll">
    @forelse ($categories as $category)
        <div class="category-scroll-item">
          @if ($category->image_path)
            <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" />
          @else
            <img src="{{ asset('images/default-category.jpg') }}" alt="{{ $category->name }}" />
          @endif
          <div class="category-content">
            <h3>{{ $category->name }}</h3>
            <p>{{ Str::limit($category->description, 80) ?: 'Professional services in this category' }}</p>
          </div>
        </div>
      @empty
        <div class="no-categories">
          <p>No categories available at the moment.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

  <!-- Post Services Section -->
  <section class="post-services-section" id="post-services">
  <div class="section-header">
    <h2>Explore Services</h2>
    <div class="carousel-controls">
      <button class="carousel-control" id="prevService">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="carousel-control" id="nextService">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <div class="post-services-carousel" id="servicesCarousel">
    @foreach ($posts as $post)
      <div class="post-card">
        <div>
          <div class="post-card-header">
            <img
              src="{{ $post->freelancer && $post->freelancer->profile_picture ? asset('storage/' . $post->freelancer->profile_picture) : asset('images/defaultprofile.png') }}"
              alt="{{ $post->freelancer->firstname ?? 'Freelancer' }}"
              class="post-card-profile"
            />
            <div class="post-card-info">
              <div class="post-card-name">
                {{ $post->freelancer->firstname ?? 'N/A' }} {{ $post->freelancer->lastname ?? '' }}
              </div>
              <div class="post-card-category">
                {{ $post->freelancer->categories->pluck('name')->first() ?? 'No Category' }}
              </div>
              <div class="post-card-rating">
              <span class="star-yellow">&#9733;</span>
                <span class="rating-value">
                {{ $post->average_rating }} 
                </span>
                <span class="rating-count">
                ({{ $post->review_count }})
                </span>
              </div>
            </div>
          </div>
          <div class="post-card-tags">
            @if($post->subServices && $post->subServices->isNotEmpty())
              @foreach ($post->subServices as $subService)
                <span class="post-card-tag">{{ $subService->sub_service }}</span>
              @endforeach
            @else
              <span class="post-card-tag" style="background:#f3f3f3; color:#888;">No sub-services</span>
            @endif
          </div>
          <div class="post-card-desc">
            {{ $post->description ?? 'No description available' }}
          </div>
          <div class="post-card-images">
            @php
              $postPictures = $post->pictures->take(3);
            @endphp
            @if ($postPictures->isNotEmpty())
              @foreach ($postPictures as $picture)
                <img
                  src="{{ asset('storage/' . $picture->image_path) }}"
                  alt="Recent work"
                />
              @endforeach
            @endif
          </div>
        </div>
        <button onclick="showLoginPopup()" class="post-card-btn">
          See Profile
        </button>
      </div>
    @endforeach
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

@include('homepage.homepageFooter')

  
  <div class="login-popup-overlay" id="login-popup">
  <div class="login-popup-modal">
    <div class="login-popup-header">
      <div class="login-popup-icon">
        <i class="fas fa-user"></i>
      </div>
      <h3>Login Required</h3>
    </div>
    <p class="login-popup-text">
      Please login or register to view this profile.
    </p>
    <div class="login-popup-actions">
      <a href="{{route('login')}}" class="login-popup-btn logbtn">Login</a>
      <a href="{{route('register.form')}}" class="login-popup-btn regisbtn">Register</a>
    </div>
    <button class="login-popup-close" onclick="closeLoginPopup()">
      <i class="fas fa-times"></i> Close
    </button>
  </div>
</div>

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
    
    

    // Login popup functions
    function showLoginPopup() {
      document.getElementById('login-popup').style.display = 'flex';
      document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    function closeLoginPopup() {
      document.getElementById('login-popup').style.display = 'none';
      document.body.style.overflow = ''; // Enable scrolling
    }
    
    // Close popup when clicking outside
    document.getElementById('login-popup').addEventListener('click', (e) => {
      if (e.target === document.getElementById('login-popup')) {
        closeLoginPopup();
      }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('servicesCarousel');
  const prevBtn = document.getElementById('prevService');
  const nextBtn = document.getElementById('nextService');
  
  if (!carousel || !prevBtn || !nextBtn) return;
  
  // Scroll amount (width of one card + gap)
  const scrollAmount = 320; // Adjust based on your card width + gap
  
  // Scroll carousel left
  prevBtn.addEventListener('click', () => {
    carousel.scrollBy({
      left: -scrollAmount,
      behavior: 'smooth'
    });
    
    // Check if we can scroll further left after scrolling
    setTimeout(() => {
      prevBtn.classList.toggle('disabled', carousel.scrollLeft <= 0);
      nextBtn.classList.toggle('disabled', 
        carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth);
    }, 500);
  });
  
  // Scroll carousel right
  nextBtn.addEventListener('click', () => {
    carousel.scrollBy({
      left: scrollAmount,
      behavior: 'smooth'
    });
    
    // Check if we can scroll further right after scrolling
    setTimeout(() => {
      prevBtn.classList.toggle('disabled', carousel.scrollLeft <= 0);
      nextBtn.classList.toggle('disabled', 
        carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth);
    }, 500);
  });
  
  // Set initial button states
  prevBtn.classList.add('disabled'); // Start with prev button disabled
});

    
   
  </script>
</body>
</html>
