
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}"> <!-- Adjust if necessary -->
   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <header class="header">
      <h1>Trabahanap.</h1>

    
    <div class="header-center">
      <input type="text" placeholder="What service are you looking for today?" class="search-bar">
      <button class="search-button">
        <i class="fas fa-search"></i>
      </button>
    </div>


    <div class="header-right">
            <i class="bx bx-bell" id="notification-icon" data-text="Notifications">
            @if($notifications->count() > 0)
        <span class="notification-count">{{ $notifications->count() }}</span>
          @endif
            </i>

        <!-- Notification Dropdown -->
        <div id="notification-section" class="notification-section" style="display: none;">
            <h3>Notifications</h3>
            <ul id="notification-list">
                <!-- Notifications will be populated here -->
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li>{{ $notification->data['message'] }}</li>
                @endforeach
            </ul>
        </div>

        <i class='bx bx-envelope' data-text="Messages"></i>
        <i class='bx bxs-heart' data-text="Favorites"></i>
        <div class="profile">
          <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-img">
          <div class="dropdown-menu">
            <div class="profile-info">
              <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-thumb">
              <div class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</div>
            </div>
            <hr>
            <a href="{{ route('customer.profile') }}">
              <i class='bx bx-user-circle'></i>Profile</a>
            <a href="#">
              <i class='bx bx-cog' ></i>Settings</a>
              <a href="{{ route('customer.appointments') }}">
              <i class='bx bx-calendar'></i>Appointments</a>
            <a href="#">
              <i class='bx bx-help-circle'></i> Help</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class='bx bx-log-in-circle' ></i>Logout
            </a>
        </div>
    </div>
  </header>
  

  <main  id="main-content" class="freelancers-section">
    <h2>Top Freelancers</h2>
                <div class="freelancer-cards">
                  
                @foreach ($posts as $post)
              <div class="freelancer-card">
                <img src="{{ asset('storage/' . $post->freelancer->profile_picture) }}" alt="Freelancer Photo" class="freelancer-photo">
                <h3>{{ $post->freelancer->firstname }} {{ $post->freelancer->lastname }}</h3>
                <p>  @if($post->freelancer->categories->isEmpty())
                    Category Not Assigned
                @else
                    @foreach($post->freelancer->categories as $category)
                        {{ $category->name }}
                    @endforeach
                @endif
                <div class="rating">
                  <i class="fas fa-star"></i> {{ $post->rating }} ({{ $post->review_count }})
                </div>
                <div class="sub-services">
                @foreach ($post->sub_services as $subService)
                    <span>{{ $subService }}</span>
                  @endforeach
                </div>
                <p class="description">{{ $post->description }}</p>


                <div class="recent-work">
                @php
                    $postPictures = json_decode($post->post_picture);
                    $displayedPictures = array_slice($postPictures, 0, 3);
                @endphp
                  @foreach ($displayedPictures as $imagePath)
                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Work Image">
                  @endforeach
                  @if (count($postPictures) > 3)
                    <span class="more-work">+{{ count($postPictures) - 3 }} More</span>
                @endif
                </div>
                
                <button class="see-profile">
                <a href="{{ route('freelancer.profile', $post->freelancer->id) }}">See Profile</a>
              </button>
              </div>
            @endforeach
                  
         
      
    </div>
    <section class="popular-services">
        <h2>Popular Services</h2>
        <div class="service-cards">
          <div class="service-card">
            <img src="images/housekeeping.jpg" alt="House Keeping" class="service-image">
            <h3>House Keeping</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/grooming.jpg" alt="Grooming" class="service-image">
            <h3>Grooming</h3>
            <div class="favorite-icon liked">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/art&media.jpg" alt="Art & Media" class="service-image">
            <h3>Art & Media</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/technician.jpg" alt="Art & Media" class="service-image">
            <h3>Technician</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/housekeeping.jpg" alt="House Keeping" class="service-image">
            <h3>House Keeping</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/art&media.jpg" alt="Art & Media" class="service-image">
            <h3>Art & Media</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/technician.jpg" alt="Art & Media" class="service-image">
            <h3>Technician</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/housekeeping.jpg" alt="House Keeping" class="service-image">
            <h3>House Keeping</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
        </div>
        
          
        </div>
      </section>

     
  </main>

 
   
 
<footer class="footer">
  <div class="footer-container">
  
    <div class="footer-section">
      <h2>Trabahanap.</h2>
      <p>Connecting clients with the best freelancers for any service. Quality and satisfaction guaranteed!</p>
    </div>

    
    <div class="footer-section">
      <h3>Quick Links</h3>
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms of Service</a></li>
      </ul>
    </div>

    <div class="footer-section">
      <h3>Follow Us</h3>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>

 
    <div class="footer-section">
      <h3>Contact Us</h3>
      <p><i class="fas fa-envelope"></i> @trabahanap.com</p>
      <p><i class="fas fa-phone-alt"></i> +63 123 456 7890</p>
    </div>
  </div>

</footer>


  <script src="clie.js"></script>
</body>
</html>


 

   
      <!-- Success message -->
      @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

   
     
    <script>
    
        
              // succes message time duration
              document.addEventListener('DOMContentLoaded', function () {
              const alert = document.querySelector('.alert-success');
              if (alert) {
                  setTimeout(() => {
                      alert.remove();
                  }, 3000); // 3 seconds
              }
          });
              // ***********drop down functionality***********************************
      document.querySelector('.profile').addEventListener('click', () => {
          const dropdown = document.querySelector('.dropdown-menu');
          dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        document.querySelectorAll('.favorite-icon').forEach(icon => {
          icon.addEventListener('click', () => {
            icon.classList.toggle('liked');
            const heart = icon.querySelector('i');
        
            if (icon.classList.contains('liked')) {
              heart.classList.remove('far'); 
              heart.classList.add('fas');   
            } else {
              heart.classList.remove('fas');
              heart.classList.add('far');
            }
          });
        });
  

        document.addEventListener("DOMContentLoaded", function() {
          const profilepageLink = document.getElementById('profile-link');
          const profilepageSection = document.getElementById('profile-section');



        })

        document.addEventListener('DOMContentLoaded', function () {
        const notificationIcon = document.getElementById('notification-icon');
        const notificationSection = document.getElementById('notification-section');

        notificationIcon.addEventListener('click', function () {
            if (notificationSection.style.display === 'none' || notificationSection.style.display === '') {
                notificationSection.style.display = 'block';
            } else {
                notificationSection.style.display = 'none';
            }
        });
    }); 


     


    </script>
</body>
</html>