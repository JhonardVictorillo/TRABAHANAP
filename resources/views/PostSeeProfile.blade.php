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
    <button onclick="goBack()" class="back-btn" >Back</button>
    <button class="book-now-btn" onclick="openBookingModal()">
      <i class='bx bx-calendar'></i> Book Now
  </button>
  
  </div>
  
  
<div id="booking-modal" class="modal">
  <div class="modal-content"> 
  
    <span class="close" onclick="closeBookingModal()">&times;</span>
    <h2>Request Date & Time</h2>

    <form method="POST" action="{{ route('book.appointment') }}">
    @csrf
      
    <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">

      <label for="date">Date:</label>
      <input type="date" id="date" name="date" required>

      <!-- Hidden input field for selected time -->
    <input type="hidden" id="selected-time" name="time">

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

<div class="contact-info">
  <div class="input-group">
    <label for="name">Your Name:</label>
    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
  </div>
  <div class="input-group">
    <label for="address">Your Address:</label>
    <input type="text" id="address" name="address" placeholder="Enter your address" required>
  </div>
</div>

<div class="contact-info">
  <div class="input-group">
    <label for="contact">Contact Number:</label>
    <input type="text" id="contact" name="contact" placeholder="Enter your contact number" required>
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


  <script>
    // Function to open the booking modal
function openBookingModal() {
  document.getElementById("booking-modal").style.display = "block";
}

// Function to close the booking modal
function closeBookingModal() {
  document.getElementById("booking-modal").style.display = "none";
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
});

function goBack() {
            window.history.back(); // Go back to the previous page
        }

  </script>
</body>

</html>
