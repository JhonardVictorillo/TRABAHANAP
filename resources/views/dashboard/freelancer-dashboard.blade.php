
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="{{asset ('css/freelancer.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  
</head>
  <body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
            <a href="#" id ="profile-link" class="profile-link">
            <h3>{{ Auth::user()->firstname }}</h3>
            </a>
            <span>{{ Auth::user()->role }}</span>
        </div>
        <ul class="sidebar-links">
        <li>
            <a href="" id="dashboard-link" class="">
                <span class="material-symbols-outlined">dashboard</span>Dashboard
            </a>
        </li>
        <li>
            <a href="#"  id="post-link"      class="">
                <span class="material-symbols-outlined">post_add</span>Post
            </a>
        </li>
        <li>
            <a href=""  id="appointment-link" class="">
                <span class="material-symbols-outlined">event</span>Appointment
            </a>
        </li>
        <li>
            <a href=""  id="message-link" class="">
                <span class="material-symbols-outlined">chat</span>Messages <span class="message-count">5</span>
            </a>
        </li>
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-symbols-outlined">logout</span>Logout
            </a>
        </li>
    </ul>
    </aside>
    <main class="main-content">
        
          
    <section id="dashboard-overview" class="section">
                <h2>Welcome,{{ Auth::user()->firstname }}</h2>
                <div class="stats-cards">
                    <div class="card">
                        <h3>Total Posts</h3>
                        <p>15</p>
                    </div>
                    <div class="card">
                        <h3>Active Appointments</h3>
                        <p>3</p>
                    </div>
                    <div class="card">
                        <h3>New Messages</h3>
                        <p>5</p>
                    </div>
                </div>
            </section>

            <section id="posts-section" class="section">
                <h2>Manage Posts</h2>
                <button class="new-post-btn">Create New Post</button>
                
                                    <!-- Create Post Form -->
            <form id="create-post-form" method="POST" action="{{ route('posts.store') }}"  enctype="multipart/form-data"style="display: none;">
                        @csrf

                        <!-- Pre-filled Category -->
               <label for="category">Category:</label>
              <input type="text" id="category" value="{{ $freelancerCategory ? $freelancerCategory->name : 'No category assigned' }}"  readonly class="form-control">

                                <!-- Sub-Services -->
                <label for="sub_services">Sub-Services:</label>
                <div id="sub-services-container">
                    <input type="text" name="sub_services[]" placeholder="Sub-Service" required>
                    @if ($errors->has('sub_services.*'))
                        <div class="error-message" style="color: red;">
                            {{ $errors->first('sub_services.*') }}
                        </div>
                    @endif
                </div>
                <button type="button" id="add-sub-service">Add Another Sub-Service</button>

                <!-- Description -->
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Post Description" required></textarea>
                @if ($errors->has('description'))
                    <div class="error-message" style="color: red;">
                        {{ $errors->first('description') }}
                    </div>
                @endif

                <!-- Recent Works Images -->
                <label for="post_picture">Recent Works:</label>
                <div id="recent-works-container">
                    <input type="file" name="post_picture[]" accept="image/*" multiple required>
                    @if ($errors->has('post_picture.*'))
                        <div class="error-message" style="color: red;">
                            {{ $errors->first('post_picture.*') }}
                        </div>
                    @endif
                </div>
                <!-- <button type="button" id="add-recent-work">Add Another Recent Work</button> -->

                <!-- Submit Button -->
                <button type="submit">Create Post</button>
                <button type="button" onclick="closeForm()">Close</button>
            </form>
                <hr>
                <h2>Created Posts</h2>
    <div class="post-container">
        @foreach ($posts as $post)
            <div class="freelancer-card">
                <img src="{{ asset('storage/' . ($post->freelancer->profile_picture ?? 'defaultprofile.jpg')) }}" alt="Freelancer Photo" class="freelancer-photo">
                <h3>{{ $post->freelancer->firstname ?? 'Unknown' }}{{ $post->freelancer->lastname ?? 'Unknown' }}</h3>
                <p>  @if($post->freelancer->categories->isEmpty())
                    Category Not Assigned
                @else
                    @foreach($post->freelancer->categories as $category)
                        {{ $category->name }}
                    @endforeach
                @endif
            </p>
                <div class="rating">
                    <i class="fas fa-star"></i> 5.0 (7,849) <!-- Example, replace with actual data if needed -->
                </div>

                <div class="sub-services">
                    @foreach (json_decode($post->sub_services) as $subService)
                        <span>{{ $subService }}</span>
                    @endforeach
                </div>

                <p class="description">
                    {{ $post->description }}
                </p>

                <div class="recent-work">

                @php
                    $postPictures = json_decode($post->post_picture);
                    $displayedPictures = array_slice($postPictures, 0, 3);
                @endphp
                @foreach ($displayedPictures as $imagePath)
                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Work Image" style="width:60px; height: auto; margin: 5px;">
                @endforeach
                    
                @if (count($postPictures) > 3)
                    <span class="more-work">+{{ count($postPictures) - 3 }} More</span>
                @endif
                </div>

                <button class="edit-post">
                    <i class="fas fa-edit"></i> Edit Post
                </button>
            </div>
        @endforeach
    </div>
            </section>

             <!-- Appointments Section -->
             <section id="appointments-section" class = "section">
                <h2>Upcoming Appointments</h2>
                <div class="calendar-view">
                    <!-- Placeholder for calendar -->
                    <p>Calendar Widget</p>
                </div>
                <div class="appointments">
                                <ul>
                    @foreach($unreadNotifications as $notification)
                        <li>
                            {{ $notification->data['customer_name'] }} booked an appointment on {{ $notification->data['date'] }} at {{ $notification->data['time'] }}
                            <form action="{{ route('appointments.accept', $notification->data['appointment_id']) }}"  method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Accept</button>
                            </form>
                            <form action="{{ route('appointments.decline', $notification->data['appointment_id']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                </div>
            </section>

               <!-- Messages Section -->
               <section id ="messages-section" class = "section">
                <h2>Messages</h2>
                <div class="messages-list">
                    <div class="message-item">
                        <p><strong>Client:</strong> Jane Smith</p>
                        <p>Last message: "Thank you for your service!"</p>
                    </div>
                    <!-- Additional message items -->
                </div>
            </section>

            <!-- profilepage section -->
            <section id="profile-section" class="section">
            <div class="profile-section">
            <div class="profile-info">
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
                <h2>{{ $user->firstname }} {{ $user->lastname }}</h2>
                <a href="#" class="edit-profile"><i class='bx bx-edit'></i>Edit Profile</a>
                <div class="profile-status">
                    <p><span class="status">Status:</span><span class="unpublished">Unpublished</span></p>
                    <p class="member">Member since January 10, 2023</p>
                    <button class="publish-button">Publish</button>
                </div>
            </div>

            <div class="profile">
                <div class="profile-header">
                    <h3><i class='bx bx-user'></i><span>Profile</span></h3>
                </div>
                <div class="profile-details">
                    <div class="service-category">
                        <h4>Service Category</h4>
                        @if($user->categories->isEmpty())
                        <span>No categories selected</span>
                    @else
                        @foreach($user->categories as $category)
                            <span>{{ $category->name }}</span>
                        @endforeach
                    @endif
                       
                    </div>
                    <div class="personal-details">
                        <p><strong>First Name:</strong> <span>{{ $user->firstname }}</span></p>
                        <p><strong>Last Name:</strong> <span>{{ $user->lastname }}</span></p>
                        <p><strong>Email:</strong> <span>{{ $user->email }}</span></p>
                        <p><strong>Contact No.:</strong> <span>{{ $user->contact_number }}</span></p>
                        <p><strong>Address:</strong> <span>{{ $user->province }} , {{ $user->city }}</span></p>
                        
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
            
        </div>

        <div class="ratings-section">
            <h3>Ratings</h3>
            <div class="rating-stars">
                <span class="star">★ ★ ★ ★ ★</span> <span class="rate">out of 5 • 0 reviews</span>
            </div>
            <ul class="rating-breakdown">
                <li><span class="label">5 star:</span><span class="value">0</span></li>
                <li><span class="label">4 star:</span><span class="value">0</span></li>
                <li><span class="label">3 star:</span><span class="value">0</span></li>
                <li><span class="label">2 star:</span><span class="value">0</span></li>
                <li><span class="label">1 star:</span><span class="value">0</span></li>
            </ul>
        </div>
        

     
        <div class="recent-works">
            <h3>Recent Works</h3>
            <div class="work-gallery">
            @forelse ($user->posts as $post)
            @foreach (json_decode($post->post_picture ?? '[]') as $imagePath)
                <img src="{{ asset('storage/' . $imagePath) }}" alt="Work Image" class="work-item">
            @endforeach
        @empty
            <p>No recent works available.</p>
        @endforelse
           <div class="add-work"><span class="material-symbols-outlined">add</span>Add Work</div>
            </div>
        </div>
</section>

          <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

       
    </main>

    
     
    <script>
       
        document.addEventListener("DOMContentLoaded", function() {
            const dashboardLink = document.getElementById('dashboard-link');
            const postLink = document.getElementById('post-link');
            const appointmentLink = document.getElementById('appointment-link');
            const messageLink = document.getElementById('message-link');
            const profilepageLink = document.getElementById('profile-link')
            const dashboardSection = document.getElementById('dashboard-overview');
            const postSection = document.getElementById('posts-section');
            const appointmentSection = document.getElementById('appointments-section');
            const messageSection = document.getElementById('messages-section');
            const profilepageSection = document.getElementById('profile-section');
            // Show Dashboard by default
            dashboardSection.style.display = 'block';
            postSection.style.display = 'none';
            appointmentSection.style.display = 'none';
            messageSection.style.display = 'none';
             profilepageSection .style.display ='none';

            // Toggle to Users section when "Users" is clicked
            dashboardLink.addEventListener('click', function(event) {
                event.preventDefault();
                postSection.style.display = 'none';
                appointmentSection.style.display = 'none';
                messageSection.style.display = 'none';
                dashboardSection.style.display = 'block';
                profilepageSection .style.display ='none'
            });

            // Toggle back to Dashboard section when "Dashboard" is clicked
            postLink.addEventListener('click', function(event) {
                event.preventDefault();
                postSection.style.display = 'block';
                appointmentSection.style.display = 'none';
                messageSection.style.display = 'none';
                dashboardSection.style.display = 'none';
                profilepageSection .style.display ='none'
            });

            appointmentLink.addEventListener('click', function(event) {
                event.preventDefault();
                postSection.style.display = 'none';
                appointmentSection.style.display = 'block';
                messageSection.style.display = 'none';
                dashboardSection.style.display = 'none';
                profilepageSection .style.display ='none'
            });

            messageLink.addEventListener('click', function(event) {
                event.preventDefault();
                postSection.style.display = 'none';
                appointmentSection.style.display = 'none';
                messageSection.style.display = 'block';
                dashboardSection.style.display = 'none';
                profilepageSection .style.display ='none'
            });
            profilepageLink.addEventListener('click', function(event) {
                event.preventDefault();
                postSection.style.display = 'none';
                appointmentSection.style.display = 'none';
                messageSection.style.display = 'none';
                dashboardSection.style.display = 'none';
                profilepageSection .style.display ='block'
            });
        });
      
        // succes message time duration
        document.addEventListener('DOMContentLoaded', function () {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(() => {
            alert.remove();
        }, 3000); // 3 seconds
    }
});

     //************************* */ post parts javascript*********************
     
document.addEventListener("DOMContentLoaded", function() {
    const createPostButton = document.querySelector(".new-post-btn");
    const createPostForm = document.getElementById("create-post-form");

    // Toggle the visibility of the create post form
    createPostButton.addEventListener("click", function() {
        if (createPostForm.style.display === "none" || createPostForm.style.display === "") {
            createPostForm.style.display = "block"; // Show the form
        } else {
            createPostForm.style.display = "none"; // Hide the form
        }
    });

    // Add another sub-service input
    document.getElementById('add-sub-service').addEventListener('click', function () {
    const container = document.getElementById('sub-services-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'sub_services[]'; // Ensure this is the correct name
    input.placeholder = 'Sub-Service';
    input.required = true; // Ensure the field is required
    container.appendChild(input);
});
    
//     // Add another recent work image input
//     document.getElementById('add-recent-work').addEventListener('click', function () {
//     const container = document.getElementById('recent-works-container');
//     const input = document.createElement('input');
//     input.type = 'file';
//     input.name = 'post_picture[]'; // Ensure this is the correct name
//     input.accept = 'image/*';
//     input.required = true; // Ensure the field is required
//     container.appendChild(input);
// });
 
document.getElementById("create-post-form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default submission for debugging
    const formData = new FormData(this);
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    this.submit(); // Re-enable submission after debugging
});
    // Reset the form fields on submission
    createPostForm.addEventListener('submit', function(event) {
        // Reset sub-services container to one input
        resetFormFields();
        // Optionally, you can hide the form after submission
        createPostForm.style.display = "none"; // Hide the form after submission
    });
});

// Function to reset the form fields
function resetFormFields() {
    const subServicesContainer = document.getElementById('sub-services-container');
    subServicesContainer.innerHTML = '<input type="text" name="sub_services[]" placeholder="Sub-Service" required>';
    
    const recentWorksContainer = document.getElementById('recent-works-container');
    recentWorksContainer.innerHTML = '<input type="file" name="recent_works[]" accept="image/*" required>';
}

// Function to close the form and reset fields
function closeForm() {
    const createPostForm = document.getElementById("create-post-form");
    createPostForm.style.display = 'none'; // Hide the form
    resetFormFields(); // Reset the form fields
}
    </script>
</body>
</html>











