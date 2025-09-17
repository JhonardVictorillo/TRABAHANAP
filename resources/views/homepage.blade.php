<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MinglaGawa - Discover Freelance Services</title>
  <!-- Manifest for PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
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
   <p class="category-subtitle" data-aos="fade-up" data-aos-delay="100" style="text-align: center; max-width: 700px; margin: 0 auto 30px; color: #6b7280; font-size: 1rem;">
    Browse through our diverse range of service categories to find skilled professionals in Minglanilla
  </p>
  
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
     <div>
      <h2>Featured Service Providers</h2>
      <p style="color: #6b7280; font-size: 1rem; max-width: 600px;">
        Discover top-rated service providers in Minglanilla ready to help with your projects
      </p>
    </div>
    <div class="carousel-controls">
      <button class="carousel-control" id="prevService">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="carousel-control" id="nextService">
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <!-- Category Filter -->
  <div style="margin-bottom: 20px; display: flex; align-items: center;">
    <span style="margin-right: 10px; font-weight: 500; color: #555;">Filter by:</span>
    <select id="categoryFilter" style="padding: 8px 15px; border-radius: 20px; border: 1px solid #e0e0e0; background: white; min-width: 180px; font-size: 14px;">
      <option value="all">All Categories</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
      @endforeach
    </select>
  </div>

 <div class="post-services-carousel" id="servicesCarousel">
  @forelse($topPostsPerFreelancer as $post)
    <div class="post-card" data-category="{{ $post->freelancer->categories->pluck('id')->first() }}">
      <div>
        @if($post->average_rating >= 4.5)
          <div style="position: absolute; top: 10px; right: 10px; background: #FFD700; color: #333; font-weight: 600; font-size: 0.8rem; padding: 4px 10px; border-radius: 20px; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-award"></i> Top Rated
          </div>
        @endif

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
              <i class="fas fa-briefcase" style="margin-right: 4px; color: #666;"></i>
              {{ $post->freelancer->categories->pluck('name')->first() ?? 'No Category' }}
            </div>
            <div class="post-card-rating">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= floor($post->average_rating))
                  <span class="star-yellow">&#9733;</span>
                @elseif($i - 0.5 <= $post->average_rating)
                  <span class="star-yellow" style="position: relative;">
                    <span style="position: absolute; overflow: hidden; width: 50%;">&#9733;</span>
                    <span style="color: #ccc;">&#9733;</span>
                  </span>
                @else
                  <span style="color: #ccc;">&#9733;</span>
                @endif
              @endfor
              <span class="rating-value">
                {{ number_format($post->average_rating, 1) }}
              </span>
              <span class="rating-count">
                ({{ $post->review_count }})
              </span>
            </div>
          </div>
        </div>

        <div style="margin-top: 10px; display: flex; justify-content: space-between; font-size: 0.9rem; color: #555;">
          <div style="display: flex; align-items: center; gap: 4px;">
            <i class="fas fa-clock" style="color: #666;"></i>
            {{ $post->service_duration }} min
          </div>
          <div style="display: flex; align-items: center; gap: 4px;">
            <i class="fas fa-map-marker-alt" style="color: #666;"></i>
            @if($post->location_restriction == 'minglanilla_only')
              Minglanilla
            @else
              Open
            @endif
          </div>
          <div style="display: flex; align-items: center; gap: 4px;">
            <i class="fas fa-tag" style="color: #666;"></i>
            ₱{{ $post->rate }}/{{ $post->rate_type }}
          </div>
        </div>

        <div class="post-card-tags">
          @if($post->subServices && $post->subServices->isNotEmpty())
            @foreach ($post->subServices->take(3) as $subService)
              <span class="post-card-tag">{{ $subService->sub_service }}</span>
            @endforeach
            @if($post->subServices->count() > 3)
              <span class="post-card-tag" style="background: #d1d5db;">+{{ $post->subServices->count() - 3 }}</span>
            @endif
          @else
            <span class="post-card-tag" style="background:#f3f3f3; color:#888;">No sub-services</span>
          @endif
        </div>

        <div class="post-card-desc">
          {{ Str::limit($post->description, 80) ?? 'No description available' }}
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
        <i class="fas fa-user" style="margin-right: 6px;"></i> View Profile
      </button>
    </div>
  @empty
    <div style="width: 100%; text-align: center; padding: 30px 0;">
      <p style="color: #666; font-size: 1rem;">No professionals available at the moment.</p>
    </div>
  @endforelse
</div>
  
  <!-- See All Professionals Button -->
  <div style="text-align: center; margin-top: 25px;">
    <button onclick="showLoginPopup()" style="background: transparent; border: 2px solid #2563eb; color: #2563eb; padding: 10px 24px; border-radius: 25px; font-weight: 600; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
      <i class="fas fa-search"></i> See All Professionals
    </button>
  </div>
</section>


  <section class="how-it-works" id="how-it-works">
  <div class="section-container">
    <h2 class="section-title" data-aos="fade-up">How It Works</h2>
    
    <div class="steps-container">
      <div class="step" data-aos="fade-up" data-aos-delay="100">
        <div class="step-circle">
          <span class="step-number">1</span>
        </div>
        <div class="step-content">
          <h3>Search & Discover</h3>
          <p>Find the services that match your needs using our advanced search.</p>
        </div>
      </div>
      
      <div class="step" data-aos="fade-up" data-aos-delay="200">
        <div class="step-circle">
          <span class="step-number">2</span>
        </div>
        <div class="step-content">
          <h3>Connect</h3>
          <p>Reach out to freelance professionals and discuss your project.</p>
        </div>
      </div>
      
      <div class="step" data-aos="fade-up" data-aos-delay="300">
        <div class="step-circle">
          <span class="step-number">3</span>
        </div>
        <div class="step-content">
          <h3>Collaborate</h3>
          <p>Work together to complete projects and achieve success.</p>
        </div>
      </div>
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


<!-- Add this modal markup just before </body> -->
<div id="location-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:99999;background:rgba(0,0,0,0.45);align-items:center;justify-content:center;">
  <div style="background:#fff;padding:32px 24px;border-radius:16px;max-width:340px;text-align:center;box-shadow:0 8px 32px rgba(37,99,235,0.12); position:relative;">
    <button id="close-location-modal" style="position:absolute;top:12px;right:12px;color:#000;border:none;border-radius:50%;width:32px;height:32px;font-size:1.2rem;cursor:pointer;">&times;</button>
    <h2 id="location-modal-title" style="color:#2563eb;font-size:1.5rem;margin-bottom:10px;">Service Unavailable</h2>
    <p id="location-modal-message" style="font-size:1.1rem;margin-bottom:18px;">You are outside Minglanilla. This service is only available in Minglanilla, Cebu.</p>
    <button id="retry-location" style="display:none;background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:1rem;cursor:pointer;">Try Again</button>
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
  // Category filter functionality
  const categoryFilter = document.getElementById('categoryFilter');
  const professionalCards = document.querySelectorAll('.post-card');
  
  if (categoryFilter) {
    categoryFilter.addEventListener('change', function() {
      const selectedCategory = this.value;
      
      professionalCards.forEach(card => {
        if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }


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

    
   if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js');
}


  </script>
</body>
</html>
