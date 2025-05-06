 <!-- profilepage section -->
 <!-- <section id="profile-section" class="section">
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
</section> -->


 
<div class="profile-section" id="profileSection" >
      <div class="profile-container">
        <!-- Sidebar -->
        <div class="profile-card">
          <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/defaultprofile.jpg') }}"  alt="Profile Picture" class="profile-image" />
          <h2 class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</h2>
          <p class="profile-role">{{ Auth::user()->role }}</p>
        
          <div class="status-section">
            <span class="status-label">Status:</span>
            <span class="status-value" id="status">Verified</span>
          </div>
          <p class="member-since">Member since: January 21, 2023</p>

          
          <button class="toggle-btn" id="toggleButton" onclick="toggleStatus()">
            <i class='bx bx-log-in-circle' ></i> Unpublish
          </button>
          
        </div>
        
        <!-- Main Profile Content -->
        <div class="profile-main">
          <div class="profile-header">
            <h2>Profile  <button class="edit-profile-icon-btn" onclick="openModal()" title="Edit Profile">
              <i class='bx bxs-edit-alt'></i>
            </button>
          </h2>
           
          </div>
    
          <!-- Categories -->
          <div class="service-categories">
          @if($user->categories->isEmpty())
            <span class="category">No categories selected</span>
            @else
            @foreach($user->categories as $category)
            <span class="category">{{ $category->name }}</span>
            @endforeach
                    @endif
          </div>
    
          <!-- Info Section -->
          <div class="info-section">
            <p><strong>Email:</strong> <a href="">{{ $user->email }}</a></p>
            <p><strong>Contact No:</strong>{{$user->contact_number }}</p>
            <p><strong>Location:</strong> {{ $user->province }} , {{ $user->city }}</p>
            <p><strong>Experience Level:</strong> {{ $user->experience_level ?? 'Not specified' }}</p> 
          </div>
    
          <!-- Ratings Section -->
          <div class="ratings-section">
            <h3>Ratings</h3>
            <div class="stars">⭐️⭐️⭐️⭐️⭐️</div>
            <p> {{ $averageRating }} / 5 • {{ $ratingBreakdown->sum() }} Reviews</p>
            <div class="rating-bars">
              <div class="rating-bar"><span>5 stars</span> <div class="bar"></div> <span>{{ $ratingBreakdown[5] }}</span></div>
              <div class="rating-bar"><span>4 stars</span> <div class="bar"></div> <span>{{ $ratingBreakdown[4] }}</span></div>
              <div class="rating-bar"><span>3 stars</span> <div class="bar"></div> <span>{{ $ratingBreakdown[3] }}</span></div>
              <div class="rating-bar"><span>2 stars</span> <div class="bar"></div> <span>{{ $ratingBreakdown[2] }}</span></div>
              <div class="rating-bar"><span>1 stars</span> <div class="bar"></div> <span>{{ $ratingBreakdown[1] }}</span></div>
            </div>
          </div>
    
          <!-- Recent Works -->
          <div class="recent-works">
            <h3>Recent Works</h3>
            <div class="works-grid">
            @forelse ($user->posts as $post)
            @foreach (json_decode($post->post_picture ?? '[]') as $imagePath)
              <img src="{{ asset('storage/' . $imagePath) }}" alt="Work 3" />
              @endforeach
            @empty
                <p>No recent works available.</p>
            @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>

 <!-- Profile Edit Modal -->
<div id="editProfileModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Edit Profile</h2>

    <form id="editProfileForm" method="POST" action="{{ route('freelancer.updateProfile') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-grid">
        <!-- Left Column -->
        <div class="form-column">

          <!-- Profile Picture -->
          <div class="form-group full-width">
            <label for="profile_picture"><i class='bx bxs-camera'></i> Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
            
            <!-- Preview Image -->
            <div class="image-preview">
              <img id="profilePicturePreview" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Preview">
            </div>
          </div>
          <!-- First Name -->
          <div class="form-group">
            <label for="firstname"><i class='bx bxs-user'></i> First Name</label>
            <div class="input-icon">
              <i class='bx bxs-user'></i>
              <input type="text" id="firstname" name="firstname" value="{{ $user->firstname }}" required>
            </div>
          </div>

          <!-- Last Name -->
          <div class="form-group">
            <label for="lastname"><i class='bx bxs-user'></i> Last Name</label>
            <div class="input-icon">
              <i class='bx bxs-user'></i>
              <input type="text" id="lastname" name="lastname" value="{{ $user->lastname }}" required>
            </div>
          </div>

          <!-- Email (readonly) -->
          <div class="form-group">
            <label for="email"><i class='bx bxs-envelope'></i> Email</label>
            <div class="input-icon">
              <i class='bx bxs-envelope'></i>
              <input type="email" id="email" name="email" value="{{ $user->email }}" readonly>
            </div>
          </div>
              
          <!-- Experience Level -->
          <div class="form-group">
          <label for="experience_level"><i class='bx bxs-briefcase'></i> Experience Level</label>
          <select id="experience_level" name="experience_level" required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              <option value="" disabled {{ !$user->experience_level ? 'selected' : '' }}>Select Experience Level</option>
              <option value="Beginner" {{ $user->experience_level === 'Beginner' ? 'selected' : '' }}>Beginner</option>
              <option value="Intermediate" {{ $user->experience_level === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
              <option value="Expert" {{ $user->experience_level === 'Expert' ? 'selected' : '' }}>Expert</option>
          </select>
      </div>

        </div>

        <!-- Right Column -->
        <div class="form-column">

          <!-- Contact Number -->
          <div class="form-group">
            <label for="contact_number"><i class='bx bxs-phone'></i> Contact Number</label>
            <div class="input-icon">
              <i class='bx bxs-phone'></i>
              <input type="text" id="contact_number" name="contact_number" value="{{ $user->contact_number }}" required>
            </div>
          </div>

          <!-- Province -->
          <div class="form-group">
            <label for="province"><i class='bx bxs-map'></i> Province</label>
            <div class="input-icon">
              <i class='bx bxs-map'></i>
              <input type="text" id="province" name="province" value="{{ $user->province }}" required>
            </div>
          </div>

          <!-- City -->
          <div class="form-group">
            <label for="city"><i class='bx bxs-city'></i> City/Municipality</label>
            <div class="input-icon">
              <i class='bx bxs-city'></i>
              <input type="text" id="city" name="city" value="{{ $user->city }}" required>
            </div>
          </div>

          <!-- Categories -->
          <div class="form-group full-width">
            <label for="categories"><i class='bx bxs-category'></i> Categories</label>
            <select id="categories" name="categories[]" multiple>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $user->categories->contains($category->id) ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Request New Category -->
          <div class="form-group full-width">
            <label for="category_request"><i class='bx bx-message-add'></i> Request New Category (Optional)</label>
            <div class="input-icon">
              <i class='bx bx-message-add'></i>
              <input type="text" id="category_request" name="category_request" placeholder="Enter new category">
            </div>
          </div>

        </div>
      </div>

      <button type="submit" class="btn-save"><i class='bx bx-save'></i> Save Changes</button>
    </form>
  </div>
</div>

<script>
     function openModal() {
  document.getElementById('editProfileModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('editProfileModal').style.display = 'none';
}

window.onclick = function(event) {
  const modal = document.getElementById('editProfileModal');
  if (event.target == modal) {
    closeModal();
  }
}

function previewSelectedImage(event) {
  const preview = document.getElementById('previewImage');
  const file = event.target.files[0];

  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function previewImage(event) {
  const input = event.target;
  const preview = document.getElementById('profilePicturePreview');

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function(e) {
      preview.src = e.target.result;
    }

    reader.readAsDataURL(input.files[0]);
  }
}
    </script>