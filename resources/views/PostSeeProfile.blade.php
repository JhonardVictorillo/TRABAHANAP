<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabahanap - Client</title>
  <link rel="stylesheet" href="{{ asset('css/PostSeeProfile.css') }}">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
       
        
       .review-container {
            width: 100%;
            margin: auto;
        }

        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .reviews {
            display: none; /* Initially hidden */
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: left;
        }

        .review {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        .review:last-child {
            border-bottom: none;
        }

        .customer-name {
            font-weight: bold;
            color: #333;
        }

        .stars {
            color: gold;
            font-size: 18px;
        }

        .comment {
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>

  
  <!-- Freelancer Profile -->
  <div class="freelancer-profile">
    <div class="profile-header">
      <img src="{{ asset('storage/' . $freelancer->profile_picture) }}" alt="Freelancer Photo" class="profile-photo">
      <div class="profile-details">
        <h2>{{ $freelancer->firstname }} {{ $freelancer->lastname }}</h2>
        <div class="service-category">
        @if($freelancer->categories->isEmpty())
                        <span>No categories selected</span>
                    @else
                        @foreach($freelancer->categories as $category)
                            <span>{{ $category->name }}</span>
                        @endforeach
                    @endif
      </div>
        <div class="personal-details">
          <p><strong>First Name:</strong> <span>{{ $freelancer->firstname }}</span></p>
          <p><strong>Last Name:</strong> <span>{{ $freelancer->lastname }}</span></p>
          <p><strong>Email:</strong> <span>{{ $freelancer->email }}</span></p>
          <p><strong>Contact No:</strong> <span>{{ $freelancer->contact_number }}</span></p>
          <p><strong>Address:</strong> <span>{{ $freelancer->province }},{{ $freelancer->city }},{{ $freelancer->zipcode }}</span></p>
          
          <div class="social-media">
              <p><strong>Social Media:</strong></p>
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
      </div>
      </div>
    </div>
    
    <div class="profile-description">
      <h3>About Me</h3>
      <p>
        I specialize in art and media design, offering services like flyer design, photo editing, and branding.
      </p>
    </div>
    
    <div class="portfolio">
      <h3>Recent Works</h3>
      <div class="portfolio-items">
      @forelse ($freelancer->posts as $post)
            @foreach (json_decode($post->post_picture ?? '[]') as $imagePath)
                <img src="{{ asset('storage/' . $imagePath) }}" alt="Work Image" class="work-item">
            @endforeach
        @empty
            <p>No recent works available.</p>
        @endforelse
      </div>
    </div>

    <div class="review-container">
        <button class="button" onclick="toggleReviews()">Reviews</button>

        <div class="reviews" id="reviewsContainer">
        @if ($reviews->isNotEmpty())
        @foreach ($reviews as $review)
                  <div class="review">
                      <p class="customer-name">
                          {{ $review->customer->firstname ?? 'Unknown' }} {{ $review->customer->lastname ?? '' }}
                      </p> 
                      <p class="stars">
                          @for ($i = 0; $i < $review->rating; $i++)
                              ★
                          @endfor
                      </p>
                     
                      <p class="comment">{{ $review->review }}</p>
                  </div>
              @endforeach
          @else
              <p>No reviews yet.</p>
          @endif
        </div>
    </div>

    <a href="{{ route('customer.dashboard') }}">
    <button onclick="goBack()" class="back-btn" >Back</button>
    </a>
    <button class="book-now-btn" onclick="openBookingModal()">
      <i class='bx bx-calendar'></i> Book Now
  </button>
  
  </div>


  
<div id="booking-modal" class="modal">
  <div class="modal-content"> 
  
    <span class="close" onclick="closeBookingModal()">&times;</span>
    <h2>Request Date & Time</h2>

    <form   method="POST" action="{{ route('book.appointment') }}">
    @csrf
      
    <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
    <input type="hidden" name="post_id" value="{{ $post->id ?? '' }}">

      <label for="date">Date:</label>
      <input type="date" id="date" name="date" required>

      <!-- Hidden input field for selected time -->
    <input type="hidden" id="selected-time" name="time" required>
    

      <label>Time:</label>
      <div class="time-picker">
        <button type="button" class="time-btn">09:00 AM</button>
        <button type="button" class="time-btn">09:30 AM</button>
        <button type="button" class="time-btn">10:00 AM</button>
        <button type="button" class="time-btn">10:30 PM</button>
        <button type="button" class="time-btn">11:00 AM</button>
        <button type="button" class="time-btn">11:30 AM</button>
        <button type="button" class="time-btn">12:00 PM</button>
        <button type="button" class="time-btn">12:30 PM</button>
        <button type="button" class="time-btn">01:00 PM</button>
        <button type="button" class="time-btn">01:30 PM</button>
        <button type="button" class="time-btn">02:00 PM</button>
        <button type="button" class="time-btn">02:30 PM</button>
        <button type="button" class="time-btn">03:00 PM</button>
        <button type="button" class="time-btn">03:30 PM</button>
        <button type="button" class="time-btn">04:00 PM</button>
        <button type="button" class="time-btn">04:30 PM</button>
        <button type="button" class="time-btn">05:00 PM</button>
        <button type="button" class="time-btn">05:30 PM</button>
        <button type="button" class="time-btn">06:00 PM</button>
        <button type="button" class="time-btn">06:30 PM</button>
        <button type="button" class="time-btn">07:00 PM</button>
        
      </div>
      @if ($errors->has('time'))
    <div class="alert alert-danger">
        {{ $errors->first('time') }}
    </div>
@endif

<div class="contact-info">
  <div class="input-group">
    <label for="name">Your Name:</label>
    <input type="text" id="name" name="name" value="{{ old('name', isset($user) ? $user->firstname .' '. $user->lastname:'') }}"  placeholder="Enter your full name" required>
    @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
  </div>
  <div class="input-group">
    <label for="address">Your Address:</label>
    <input type="text" id="address" name="address" value="{{ old('address', isset($user) ? $user->province.','.$user->city:'') }}" placeholder="Enter your address" required>
    @error('address')
            <div class="text-danger">{{ $message }}</div>
        @enderror
  </div>
</div>

<div class="contact-info">
  <div class="input-group">
    <label for="contact">Contact Number:</label>
    <input type="text" id="contact" name="contact"  value="{{ old('contact', isset($user) ? $user->contact_number:'') }}" placeholder="Enter your contact number" required>
    @error('contact')
            <div class="text-danger">{{ $message }}</div>
        @enderror
  </div>
  <div class="input-group">
    <label for="notes">Appointment Request:</label>
    <textarea id="notes" name="notes" placeholder="Any special requests  (Optional)"></textarea>
  </div>
</div>


      
      <button type="submit" class="submit-btn">Request Appointment</button>
      @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    </form>
  </div>
</div>


@if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <script>
     // Function to open the booking modal
function openBookingModal() {
  document.getElementById("booking-modal").style.display = "block";
}

// Function to close the booking modal
function closeBookingModal() {
  document.getElementById("booking-modal").style.display = "none";
}

function toggleReviews() {
        var reviewsContainer = document.getElementById("reviewsContainer");
        if (reviewsContainer.style.display === "none" || reviewsContainer.style.display === "") {
            reviewsContainer.style.display = "block"; // Show reviews
        } else {
            reviewsContainer.style.display = "none"; // Hide reviews
        }
    }
// // Show freelancer profile and hide main content
// function showFreelancerProfile() {
//   document.getElementById('main-content').style.display = 'none';
//   document.getElementById('freelancer-profile').style.display = 'block';
// }

// // Hide freelancer profile and show main content
// function hideFreelancerProfile() {
//   document.getElementById('freelancer-profile').style.display = 'none';
//   document.getElementById('main-content').style.display = 'block'
// }

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".time-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      // Remove 'selected' class from other buttons
      document.querySelectorAll(".time-btn").forEach((b) => b.classList.remove("selected"));
      // Add 'selected' class to clicked button
      this.classList.add("selected");
      // Update the value of the hidden input field
      document.getElementById("selected-time").value = this.textContent.trim();
    });
  });

 

  const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    alert.remove();
                }, 3000); // 3 seconds
            }



        // Show reviews when button is clicked
       
});



  </script>
</body>

</html>
