 <header class="header">
    <div class="logo-header">
      <a href="{{ route('homepage') }}" >
        <span class="mingla-part">Mingla</span><span class="gawa-part">Gawa</span>
      </a>
    </div>
    
    <!-- Mobile menu toggle button -->
    <button class="menu-toggle" id="menu-toggle" aria-label="Toggle navigation menu">
      <i class="fas fa-bars"></i>
    </button>
    
    <!-- Center navigation links -->
    <nav class="navbar" id="navbar">
      <!-- Get the current route name -->
      @php
        $currentRoute = Route::currentRouteName();
        $isHomepage = $currentRoute == 'homepage';
      @endphp
      
      <div class="nav-links">
        <a href="{{ $isHomepage ? '#home' : route('homepage') . '#home' }}" 
           class="{{ $isHomepage ? 'active' : '' }}">Home</a>
        <a href="{{ $isHomepage ? '#category' : route('homepage') . '#category' }}">Categories</a>
        <a href="{{ $isHomepage ? '#how-it-works' : route('homepage') . '#how-it-works' }}">How It Works</a>
        <a href="{{ $isHomepage ? '#footer' : route('homepage') . '#footer' }}">Contact</a>
      </div>
      
      <div class="auth-buttons">
        <a href="{{ route('login') }}">
          <button class="logbtn {{ $currentRoute == 'login' ? 'active-btn' : '' }}">Login</button>
        </a>
        <a href="{{ route('register.form') }}">
          <button class="regisbtn {{ $currentRoute == 'register.form' ? 'active-btn' : '' }}">Register</button>
        </a>
      </div>
    </nav>
</header>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('menu-toggle');
      const navbar = document.getElementById('navbar');

      if (menuToggle && navbar) {
        menuToggle.addEventListener('click', () => {
          navbar.classList.toggle('active');
          menuToggle.classList.toggle('active');
          
          // Change the icon between bars and times
          const icon = menuToggle.querySelector('i');
          if (icon) {
            if (icon.classList.contains('fa-bars')) {
              icon.classList.remove('fa-bars');
              icon.classList.add('fa-times');
            } else {
              icon.classList.remove('fa-times');
              icon.classList.add('fa-bars');
            }
          }
        });
        
        // Reset when window resizes
        window.addEventListener('resize', () => {
          if (window.innerWidth > 768) {
            navbar.classList.remove('active');
            menuToggle.classList.remove('active');
            const icon = menuToggle.querySelector('i');
            if (icon && icon.classList.contains('fa-times')) {
              icon.classList.remove('fa-times');
              icon.classList.add('fa-bars');
            }
          }
        });
      }

      // Close mobile menu when clicking on a link
      const navLinks = document.querySelectorAll('.navbar a');
      navLinks.forEach(link => {
        link.addEventListener('click', () => {
          if (navbar) {
            navbar.classList.remove('active');
            if (menuToggle) {
              menuToggle.classList.remove('active');
              const icon = menuToggle.querySelector('i');
              if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
              }
            }
          }
        });
      });

      // Only apply smooth scroll on homepage
      const isHomepage = window.location.pathname === '/' || 
                        window.location.pathname === '/home' || 
                        window.location.pathname.endsWith('/wHUB');
      
      if (isHomepage) {
        const hashLinks = document.querySelectorAll('.navbar a[href^="#"]');
        hashLinks.forEach(link => {
          link.addEventListener('click', (e) => {
            const sectionId = link.getAttribute('href').substring(1);
            const section = document.getElementById(sectionId);
            if (section) {
              e.preventDefault();
              navLinks.forEach(l => l.classList.remove('active'));
              link.classList.add('active');
              section.scrollIntoView({ behavior: 'smooth' });
            }
          });
        });
      }
    });
  </script>