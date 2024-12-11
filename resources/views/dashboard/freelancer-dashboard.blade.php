
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
                <h3>{{ $post->freelancer->firstname ?? 'Unknown' }} , {{ $post->freelancer->lastname ?? 'Unknown' }}</h3>
                <p>  @if($post->freelancer->categories->isEmpty())
                    Category Not Assigned
                @else
                    @foreach($post->freelancer->categories as $category)
                        {{ $category->name }}
                    @endforeach
                @endif
            </p>
                <div class="rating">
                                @php
                        $averageRating = $post->averageRating(); // Calculate average rating
                        $totalReviews = $post->totalReviews();   // Total number of reviews
                        $starCount = floor($averageRating);      // Full stars
                        $halfStar = ($averageRating - $starCount) >= 0.5; // Half-star logic
                    @endphp

                    <!-- Display full stars -->
                    @for ($i = 0; $i < $starCount; $i++)
                        <i class="fas fa-star"></i>
                    @endfor

                    <!-- Display half star if necessary -->
                    @if ($halfStar)
                        <i class="fas fa-star-half-alt"></i>
                    @endif

                    <!-- Display empty stars -->
                    @for ($i = $starCount + ($halfStar ? 1 : 0); $i < 5; $i++)
                        <i class="far fa-star"></i>
                    @endfor

                    <!-- Display average rating and total reviews -->
                    <span>{{ number_format($averageRating, 1) }} / 5 • {{ $totalReviews }} reviews</span><!-- Example, replace with actual data if needed -->
                </div>

                <div class="sub-services">
                @foreach ($post->sub_services as $subService)
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
           @foreach($appointments as $appointment)
                <li>
                     {{ $appointment->name ?? 'Unknown' }} booked an appointment on {{ $appointment->date }} at 
                    {{ $appointment->time }}<br>
                    <strong>Status:</strong> 
                    <span class="status {{ strtolower($appointment->status) }}">
                        {{ ucfirst($appointment->status) }}
                    </span>

                    @if($appointment->status === 'pending')
                        <!-- Accept Button -->
                        <form action="{{ route('appointments.accept', $appointment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Accept</button>
                        </form>

                        <!-- Decline Button -->
                        <form action="{{ route('appointments.decline', $appointment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Decline</button>
                        </form>
                    @else
                        <!-- Display final status -->
                        <!-- <strong>Final Status:</strong> {{ ucfirst($appointment->status) }} -->
                    @endif

                    @if($appointment->status === 'accepted')
                        <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Completed</button>
                        </form>
                    @endif

                    <!-- View Details Button -->
                    <button type="button" class="btn btn-primary view-details" 
                        data-customer="{{ $appointment->name ?? 'N/A' }}" 
                        data-date="{{ $appointment->date }}" 
                        data-time="{{ $appointment->time }}" 
                        data-contact="{{ $appointment->contact ?? 'N/A' }}" 
                        data-notes="{{ $appointment->notes ?? 'N/A' }}" 
                        data-status="{{ ucfirst($appointment->status) }}">
                        View Details
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Appointment Details Modal -->
    <div id="appointmentDetailsModal" class="appointmentDetailsModal">
        <div class="divContent">
            <h3>Appointment Details</h3>
            <p><strong>Customer:</strong> <span id="modalCustomer"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
            <p><strong>Time:</strong> <span id="modalTime"></span></p>
            <p><strong>Contact:</strong> <span id="modalContact"></span></p>
            <p><strong>Notes:</strong> <span id="modalNotes"></span></p>
            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            <button id="closeModal" style="margin-top: 10px;">Close</button>
        </div>
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
            <span class="star">
            <span class="star">
                    @for($i = 0; $i < floor($averageRating); $i++)
                        ★
                    @endfor
                    @if($averageRating - floor($averageRating) >= 0.5)
                        ★
                    @else
                        ☆
                    @endif
                </span>
                <span class="rate">
                    {{ $averageRating }} / 5 • {{ $ratingBreakdown->sum() }} reviews
                </span>
            </div>
            <ul class="rating-breakdown">
            <li><span class="label">5 star:</span><span class="value">{{ $ratingBreakdown[5] }}</span></li>
                <li><span class="label">4 star:</span><span class="value">{{ $ratingBreakdown[4] }}</span></li>
                <li><span class="label">3 star:</span><span class="value">{{ $ratingBreakdown[3] }}</span></li>
                <li><span class="label">2 star:</span><span class="value">{{ $ratingBreakdown[2] }}</span></li>
                <li><span class="label">1 star:</span><span class="value">{{ $ratingBreakdown[1] }}</span></li>
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
       
       document.addEventListener("DOMContentLoaded", function () {
    // Manage visibility of sections
    const sections = {
        dashboard: document.getElementById("dashboard-overview"),
        posts: document.getElementById("posts-section"),
        appointments: document.getElementById("appointments-section"),
        messages: document.getElementById("messages-section"),
        profile: document.getElementById("profile-section")
    };

            const links = {
                dashboard: document.getElementById("dashboard-link"),
                posts: document.getElementById("post-link"),
                appointments: document.getElementById("appointment-link"),
                messages: document.getElementById("message-link"),
                profile: document.getElementById("profile-link")
            };

            Object.values(sections).forEach((section) => (section.style.display = "none"));
            sections.dashboard.style.display = "block";

            Object.keys(links).forEach((key) => {
                links[key].addEventListener("click", function (event) {
                    event.preventDefault();
                    Object.values(sections).forEach((section) => (section.style.display = "none"));
                    sections[key].style.display = "block";
                });
            });
            
        

     
  //************************* */ post parts javascript*********************
     
  
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


        // Function to reset the form fields
        function resetFormFields() {
            const subServicesContainer = document.getElementById('sub-services-container');
            subServicesContainer.innerHTML = '<input type="text" name="sub_services[]" placeholder="Sub-Service" required>';
            
            const recentWorksContainer = document.getElementById('recent-works-container');
            recentWorksContainer.innerHTML = '<input type="file" name="recent_works[]" accept="image/*" required>';
        }


        // succes message time duration
      
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    alert.remove();
                }, 3000); // 3 seconds
            }
    

    ///////////// appointment view details

    const viewButtons = document.querySelectorAll('.view-details');
    const modal = document.getElementById('appointmentDetailsModal');
    const closeModal = document.getElementById('closeModal');

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modalCustomer').textContent = this.dataset.customer;
            document.getElementById('modalDate').textContent = this.dataset.date;
            document.getElementById('modalTime').textContent = this.dataset.time;
            document.getElementById('modalContact').textContent = this.dataset.contact;
            document.getElementById('modalNotes').textContent = this.dataset.notes;
            document.getElementById('modalStatus').textContent = this.dataset.status;
            modal.style.display = 'flex';
        });
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

        // Function to close the form and reset fields
        function closeForm() {
            const createPostForm = document.getElementById("create-post-form");
            createPostForm.style.display = 'none'; // Hide the form
            resetFormFields(); // Reset the form fields
        }
});
    </script>
</body>
</html>












