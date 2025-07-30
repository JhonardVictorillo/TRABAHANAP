<main class="main-content">
  <div class="profile-section" id="profileSection">
    <div class="profile-container">
      <!-- Improved Sidebar Layout -->
      <div class="profile-card">
        <!-- Redesigned Profile Header -->
        <div class="profile-header-layout">
          <div class="profile-image-wrapper">
            <div class="profile-image-container">
              <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-image" onerror="this.src='{{ asset('images/defaultprofile.jpg') }}'"/>
              
              <button class="edit-profile-photo-btn" onclick="openUpdateProfileModal()" title="Update Profile Photo">
                <i class='bx bxs-camera'></i>
              </button>
              
              
            </div>
          </div>
          
          <div class="profile-header-info">
            <h2 class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</h2>
            <p class="profile-role">{{ ucfirst(Auth::user()->role) }}</p>
            
           
            
          </div>
        </div>
        
        <div class="status-section">
          <span class="status-label">Status:</span>
          @if($user->is_verified)
            <span class="status-value verified"><i class='bx bxs-check-circle'></i> Verified</span>
          @else
            <span class="status-value not-verified"><i class='bx bxs-time-five'></i> Pending Verification</span>
          @endif
        </div>
        
        <div class="member-info">
          <p class="member-since"><i class='bx bx-calendar'></i> Member since: {{ $user->created_at->format('F d, Y') }}</p>
          <p class="availability"><i class='bx bx-time-five'></i> Response Time: Within 24 hours</p>
        </div>

        <div class="profile-actions">
                  
          <button class="share-profile-btn" onclick="shareProfile()">
            <i class='bx bx-share-alt'></i> Share Profile
          </button>
        </div>
      </div>
      
      <!-- Main Profile Content -->
      <div class="profile-main">
        <div class="profile-header">
          <div class="profile-title">
            <h2>Professional Profile</h2>
            <p class="profile-tagline">{{ $user->bio ?? 'Experienced professional ready to help with your projects' }}</p>
          </div>
          
          <button class="edit-profile-btn" onclick="openUpdateProfileModal()" title="Edit Profile">
            <i class='bx bxs-edit-alt'></i> Edit Profile
          </button>
        </div>
  
        <!-- Categories -->
        <div class="service-categories-section">
          <h3><i class='bx bx-category-alt'></i> Services Offered</h3>
          <div class="service-categories">
            @if($user->categories->isEmpty())
              <span class="category empty-category">No services selected</span>
            @else
              @foreach($user->categories as $category)
                <span class="category">{{ $category->name }}</span>
              @endforeach
            @endif
          </div>
        </div>
  
        <!-- Info Section -->
        <div class="info-section">
          <h3><i class='bx bx-info-circle'></i> Contact Information</h3>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-icon"><i class='bx bx-envelope'></i></div>
              <div class="info-content">
                <span class="info-label">Email</span>
                <span class="info-value"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon"><i class='bx bx-phone'></i></div>
              <div class="info-content">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ $user->contact_number }}</span>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon"><i class='bx bx-map'></i></div>
              <div class="info-content">
                <span class="info-label">Location</span>
                <span class="info-value">{{ $user->city }}, {{ $user->province }}</span>
              </div>
            </div>
            
            <div class="info-item">
              <div class="info-icon"><i class='bx bx-briefcase'></i></div>
              <div class="info-content">
                <span class="info-label">Experience</span>
                <span class="info-value">{{ $user->experience_level ?? 'Not specified' }}</span>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Ratings Section -->
        <div class="ratings-section">
          <h3><i class='bx bx-star'></i> Client Ratings & Reviews</h3>
          <div class="ratings-summary">
            <div class="rating-score">
              <span class="rating-number">{{ number_format($averageRating, 1) }}</span>
              <div class="stars">
                @for ($i = 1; $i <= 5; $i++)
                  @if ($i <= $averageRating)
                    <i class='bx bxs-star'></i>
                  @elseif ($i - 0.5 <= $averageRating)
                    <i class='bx bxs-star-half'></i>
                  @else
                    <i class='bx bx-star'></i>
                  @endif
                @endfor
              </div>
              <span class="rating-count">{{ $ratingBreakdown->sum() }} Reviews</span>
            </div>
            
            <div class="rating-bars">
              @for ($i = 5; $i >= 1; $i--)
                <div class="rating-bar">
                  <span class="bar-label">{{ $i }} stars</span>
                  <div class="bar-container">
                    <div class="bar-fill" style="width: {{ $ratingBreakdown->sum() > 0 ? ($ratingBreakdown[$i] / $ratingBreakdown->sum()) * 100 : 0 }}%;"></div>
                  </div>
                  <span class="bar-count">{{ $ratingBreakdown[$i] }}</span>
                </div>
              @endfor
            </div>
          </div>
          
          @if($ratingBreakdown->sum() > 0)
            <div class="view-all-reviews">
              <button class="view-reviews-btn" onclick="showReviewsTable()">
                <i class='bx bx-message-detail'></i> View All Reviews
              </button>
            </div>
          @endif
        </div>
  
        <!-- Recent Works -->
        <div class="recent-works">
          <h3><i class='bx bx-images'></i> Portfolio</h3>
          @if($user->posts->isEmpty())
            <div class="empty-portfolio">
              <i class='bx bx-image-add'></i>
              <p>No portfolio items yet</p>
              <button class="add-work-btn" onclick="showAddPortfolioModal()">
                <i class='bx bx-plus'></i> Add Work
              </button>
            </div>
          @else
            <div class="works-grid">
              @foreach ($user->posts as $post)
                @foreach ($post->pictures as $index => $picture)
                  @if($index < 6) <!-- Limit to 6 images for cleaner display -->
                    <div class="work-item" onclick="openPortfolioModal('{{ asset('storage/' . $picture->image_path) }}', '{{ $post->title ?? 'Portfolio Item' }}', '{{ $post->description ?? '' }}')">
                      <img src="{{ asset('storage/' . $picture->image_path) }}" alt="{{ $post->title ?? 'Work Image' }}" />
                      <div class="work-overlay">
                        <span class="work-title">{{ $post->title ?? 'View' }}</span>
                      </div>
                    </div>
                  @endif
                @endforeach
              @endforeach
            </div>
            
            @if(count($user->posts) > 2) <!-- Show view all button if more than 2 posts -->
              <div class="view-all-works">
                <button class="view-all-btn" onclick="showPortfolioPage()">
                  <i class='bx bx-images'></i> View All Works
                </button>
              </div>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Add the Portfolio Modal -->
  <div id="portfolioModal" class="portfolio-modal" style="display: none;">
  <div class="portfolio-modal-content">
    <span class="portfolio-modal-close">&times;</span>
    <div class="portfolio-modal-body">
      <img id="portfolioModalImage" src="" alt="Portfolio Item">
      <div class="portfolio-modal-details">
        <h3 id="portfolioModalTitle"></h3>
        <p id="portfolioModalDescription"></p>
      </div>
    </div>
  </div>
</div>

 <!-- Profile Edit Modal -->
 <div id="editProfileModal" class="p-modal">
  <div class="p-modal-content">
    <div class="p-modal-header">
      <h2 class="p-modal-title">Edit Profile</h2>
      <span class="p-close" onclick="document.getElementById('editProfileModal').style.display='none'">&times;</span>
    </div>
    
    <form id="editProfileForm" method="POST" action="{{ route('freelancer.updateProfile') }}" enctype="multipart/form-data">
      @csrf
       @method('PUT')
      
      <!-- Profile Picture Upload -->
     <div class="p-profile-upload">
      <div class="p-avatar-container">
        <div class="p-avatar-wrapper" id="avatarWrapper">
          <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}" alt="Profile" class="p-avatar-preview" id="avatarPreview">
          <div class="p-avatar-overlay">
            <i class='bx bx-camera'></i>
            <span>Change</span>
          </div>
        </div>
        <input type="file" name="profile_picture" id="profilePictureInput" class="p-avatar-input" accept="image/*">
      </div>
      <div class="p-upload-info">
        <h4>Profile Picture</h4>
        <p>Recommended: 300x300px, Max 2MB</p>
      </div>
    </div>
      
      <!-- Tab Navigation -->
      <div class="p-tabs">
        <button type="button" class="p-tab-btn p-active" data-tab="personal">
          <i class='bx bx-user'></i> Personal Info
        </button>
        <button type="button" class="p-tab-btn" data-tab="contact">
          <i class='bx bx-phone'></i> Contact Details
        </button>
        <button type="button" class="p-tab-btn" data-tab="services">
          <i class='bx bx-category'></i> Services & Skills
        </button>
        <button type="button" class="p-tab-btn" data-tab="rates">
          <i class='bx bx-money'></i> Rate Options
        </button>
        <button type="button" class="p-tab-btn" data-tab="bio">
          <i class='bx bx-message-square-detail'></i> Bio
        </button>
      </div>

      <!-- Personal Info Tab -->
      <div class="p-tab-content p-active" id="personal-tab">
        <div class="p-form-group">
          <label for="firstname" class="p-label">First Name</label>
          <div class="p-input-icon">
            <i class='bx bx-user'></i>
            <input type="text" id="firstname" name="firstname" value="{{ $user->firstname }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="lastname" class="p-label">Last Name</label>
          <div class="p-input-icon">
            <i class='bx bx-user'></i>
            <input type="text" id="lastname" name="lastname" value="{{ $user->lastname }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="email" class="p-label">Email Address</label>
          <div class="p-input-icon">
            <i class='bx bx-envelope'></i>
            <input type="email" id="email" name="email" value="{{ $user->email }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="experience_level" class="p-label">Experience Level</label>
          <div class="p-input-icon">
            <i class='bx bx-medal'></i>
            <select id="experience_level" name="experience_level" class="p-select" required>
              <option value="">Select level</option>
              <option value="Beginner" {{ $user->experience_level === 'Beginner' ? 'selected' : '' }}>Beginner</option>
              <option value="Intermediate" {{ $user->experience_level === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
              <option value="Expert" {{ $user->experience_level === 'Expert' ? 'selected' : '' }}>Expert</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Contact Details Tab -->
      <div class="p-tab-content" id="contact-tab">
        <div class="p-form-group">
          <label for="contact_number" class="p-label">Contact Number</label>
          <div class="p-input-icon">
            <i class='bx bx-phone'></i>
            <input type="text" id="contact_number" name="contact_number" value="{{ $user->contact_number ?? '' }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="province" class="p-label">Province</label>
          <div class="p-input-icon">
            <i class='bx bx-map'></i>
            <input type="text" id="province" name="province" value="{{ $user->province ?? '' }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="city" class="p-label">City</label>
          <div class="p-input-icon">
            <i class='bx bx-buildings'></i>
            <input type="text" id="city" name="city" value="{{ $user->city ?? '' }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="zipcode" class="p-label">Zipcode</label>
          <div class="p-input-icon">
            <i class='bx bxs-map-pin'></i>
            <input type="text" id="zipcode" name="zipcode" value="{{ $user->zipcode ?? '' }}" class="p-input" required>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="google_map_link" class="p-label">Google Map Link (Optional)</label>
          <div class="p-input-icon">
            <i class='bx bx-map-alt'></i>
            <input type="text" id="google_map_link" name="google_map_link" value="{{ $user->google_map_link ?? '' }}" class="p-input" placeholder="https://maps.google.com/...">
          </div>
        </div>
      </div>

      <!-- Services & Skills Tab -->
      <div class="p-tab-content" id="services-tab">
        <!-- Categories Section -->
        <div class="p-form-group">
          <label class="p-label">Service Categories</label>
          
          <!-- Multi-select for existing categories -->
          <div class="p-categories-select">
            <select multiple name="categories[]" class="p-select-multiple">
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $user->categories->contains($category->id) ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          
          <!-- Category request section -->
          <div class="p-category-request-section">
            <div class="p-separator">
              <span>Can't find what you're looking for?</span>
            </div>
            
            <div class="p-input-with-hint">
              <div class="p-input-icon">
                <i class='bx bx-plus-circle'></i>
                <input type="text" id="category_request" name="category_request" 
                       value="{{ $user->category_request }}" class="p-input" 
                       placeholder="Suggest a new service category">
              </div>
              <div class="p-input-hint">
                <i class='bx bx-info-circle'></i>
                Your suggestion will be reviewed by our team
              </div>
            </div>
            
            <!-- Show status of previous requests if any -->
            @if($user->category_request)
              <div class="p-request-status">
                <div class="p-request-status-badge">
                  <i class='bx bx-time-five'></i> Pending Review
                </div>
                <p class="p-request-info">
                  Your category request "<strong>{{ $user->category_request }}</strong>" is being reviewed.
                </p>
              </div>
            @elseif($latestRequest = $user->categoryRequests()->latest()->first())
              <div class="p-request-status {{ $latestRequest->status === 'approved' ? 'p-request-approved' : 'p-request-declined' }}">
                <div class="p-request-status-badge">
                  @if($latestRequest->status === 'approved')
                    <i class='bx bx-check-circle'></i> Approved
                  @else
                    <i class='bx bx-x-circle'></i> Declined
                  @endif
                </div>
                <p class="p-request-info">
                  Your category request "<strong>{{ $latestRequest->category_name }}</strong>" was {{ $latestRequest->status }}.
                  @if($latestRequest->admin_notes)
                    <br><span class="p-admin-notes">{{ $latestRequest->admin_notes }}</span>
                  @endif
                </p>
              </div>
            @endif
          </div>
        </div>
        
        <!-- Skills section -->
        <div class="p-form-group">
          <label for="skills" class="p-label">Skills</label>
          <div class="p-tags-input">
            <input type="text" id="skillInput" class="p-input" placeholder="Type a skill and press Enter">
            <div class="p-tags-container" id="skills-container">
              @if(isset($user->skills))
                @foreach(json_decode($user->skills) as $skill)
                  <span class="p-tag" data-value="{{ $skill }}">
                    {{ $skill }}
                    <i class='bx bx-x p-tag-remove'></i>
                    <input type="hidden" name="skills[]" value="{{ $skill }}">
                  </span>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
      
      <!-- Rate Options Tab (New Separate Tab) -->
      <div class="p-tab-content" id="rates-tab">
        <div class="p-section-header">
          <h4><i class='bx bx-money'></i> Set Your Service Rates</h4>
          <p class="p-section-desc">Enable at least one rate option to let clients know your pricing</p>
        </div>

        <div class="p-rate-options">
          <div class="p-rate-option">
            <div class="p-rate-header">
              <label for="hourly_rate" class="p-label">Hourly Rate (₱)</label>
              <div class="p-rate-toggle">
                <input type="checkbox" id="enable_hourly" class="p-rate-checkbox" 
                      {{ $user->hourly_rate ? 'checked' : '' }}>
                <label for="enable_hourly" class="p-toggle-label"></label>
              </div>
            </div>
            <div class="p-input-icon {{ $user->hourly_rate ? '' : 'p-disabled' }}">
              <i class='bx bx-time'></i>
              <input type="number" id="hourly_rate" name="hourly_rate" min="0" step="10"
                    value="{{ $user->hourly_rate ?? '' }}" class="p-input"
                    placeholder="Rate per hour" {{ $user->hourly_rate ? '' : 'disabled' }}>
            </div>
          </div>
          
          <div class="p-rate-option">
            <div class="p-rate-header">
              <label for="daily_rate" class="p-label">Daily Rate (₱)</label>
              <div class="p-rate-toggle">
                <input type="checkbox" id="enable_daily" class="p-rate-checkbox" 
                      {{ $user->daily_rate ? 'checked' : '' }}>
                <label for="enable_daily" class="p-toggle-label"></label>
              </div>
            </div>
            <div class="p-input-icon {{ $user->daily_rate ? '' : 'p-disabled' }}">
              <i class='bx bx-calendar-day'></i>
              <input type="number" id="daily_rate" name="daily_rate" min="0" step="100"
                    value="{{ $user->daily_rate ?? '' }}" class="p-input"
                    placeholder="Rate per day" {{ $user->daily_rate ? '' : 'disabled' }}>
            </div>
          </div>
          
          <div class="p-rate-option">
            <div class="p-rate-header">
              <label for="weekly_rate" class="p-label">Weekly Rate (₱)</label>
              <div class="p-rate-toggle">
                <input type="checkbox" id="enable_weekly" class="p-rate-checkbox" 
                      {{ $user->weekly_rate ? 'checked' : '' }}>
                <label for="enable_weekly" class="p-toggle-label"></label>
              </div>
            </div>
            <div class="p-input-icon {{ $user->weekly_rate ? '' : 'p-disabled' }}">
              <i class='bx bx-calendar-week'></i>
              <input type="number" id="weekly_rate" name="weekly_rate" min="0" step="500"
                    value="{{ $user->weekly_rate ?? '' }}" class="p-input"
                    placeholder="Rate per week" {{ $user->weekly_rate ? '' : 'disabled' }}>
            </div>
          </div>
        </div>
      </div>

      <!-- Bio Tab -->
      <div class="p-tab-content" id="bio-tab">
        <div class="p-form-group">
          <label for="bio" class="p-label">Professional Bio</label>
          <div class="p-textarea-container">
            <textarea id="bio" name="bio" rows="4" class="p-textarea" placeholder="Write a short bio describing your skills and expertise">{{ $user->bio ?? '' }}</textarea>
            <span class="p-char-count"><span id="bioCount">{{ strlen($user->bio ?? '') }}</span>/300</span>
          </div>
        </div>
        
        <div class="p-form-group">
          <label for="specialization" class="p-label">Specialization</label>
          <div class="p-input-icon">
            <i class='bx bx-medal'></i>
            <input type="text" id="specialization" name="specialization" value="{{ $user->specialization ?? '' }}" class="p-input" placeholder="Your main area of expertise or focus">
          </div>
        </div>
      </div>

      <!-- Modal Footer - Outside all tab content sections -->
      <div class="p-modal-footer">
        <button type="button" class="p-btn-cancel" onclick="document.getElementById('editProfileModal').style.display='none'">
          <i class='bx bx-x'></i> Cancel
        </button>
        <button type="submit" class="p-btn-save">
          <i class='bx bx-save'></i> Save Changes
        </button>
      </div>
    </form>
  </div>
  </div>
    

    <!-- Dashboard Section -->
    <div class="dashboard-section" id="dashboardSection" style="display: none;">
      <div class="content-header">
      </div>
      <div class="dashboard-content">
        <div class="stats-cards">
          <div class="card" id="clientsCard">
            <div class="card-icon">
              <span class="material-symbols-outlined">group</span>
            </div>
            <div class="card-info">
              <h3>Clients</h3>
              <p>{{ $totalClients }}</p>
            </div>
          </div>
          <div class="card" id="appointmentsCard">
            <div class="card-icon">
              <span class="material-symbols-outlined">calendar_today</span>
            </div>
            <div class="card-info">
              <h3>Appointments</h3>
              <p>{{ $totalAppointments }}</p>
            </div>
          </div>
          <div class="card" id="reviewsCard">
            <div class="card-icon">
              <span class="material-symbols-outlined">star</span>
            </div>
            <div class="card-info">
              <h3>Reviews</h3>
              <p>{{ $averageRating }}</p>
            </div>
          </div>
        </div>
        <div class="tables">
          <div class="table-container" id="clientsTable">
            <h3>Total Clients</h3>
            <table class="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Joined Date</th>
                </tr>
              </thead>
              <tbody>
              @forelse ($clients as $client)
                <tr>
                  <td>{{ $client->firstname }} {{ $client->lastname }}</td>
                  <td>{{ $client->email }}</td>
                  <td>{{ $client->contact_number ?? 'N/A' }}</td>
                  <td>{{ $client->created_at->format('Y-m-d') }}</td>
                </tr>
              
              @empty
                <tr>
                  <td colspan="4">No clients found.</td>
                </tr>
              @endforelse
              </tbody>
            </table>
          </div>
          <div class="table-container" id="appointmentsTable" style="display: none;">
            <h3>Total Appointments</h3>
            <table class="table">
              <thead>
                <tr>
                  <th>Client Name</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @forelse ($appointments as $appointment)
                  <tr>
                    <td>{{ $appointment->customer->firstname }} {{ $appointment->customer->lastname }}</td>
                    <td>{{ $appointment->date }}</td>
                    <td>{{ $appointment->time }}</td>
                    <td>{{ ucfirst($appointment->status) }}</td>
                    <td>
                      @if ($appointment->status != 'completed')
                        <button class="btn-mark-done" data-id="{{ $appointment->id }}">Mark as Done</button>
                      @else
                        Completed
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5">No appointments found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="table-container" id="reviewsTable" style="display: none;">
            <h3>Client Reviews</h3>
            <table class="table">
              <thead>
                <tr>
                  <th>Client Name</th>
                  <th>Rating</th>
                  <th>Comment</th>
                </tr>
              </thead>
              <tbody>
              @forelse ($reviews as $review)
                    <tr>
                      <td>{{ $review->customer->firstname }} {{ $review->customer->lastname }}</td>
                      <td>
                        @for ($i = 0; $i < $review->rating; $i++)
                          ⭐
                        @endfor
                      </td>
                      <td>{{ $review->review  ?? 'No comment' }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3">No reviews found.</td>
                    </tr>
                  @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("editProfileModal");
    const openModalBtn = document.querySelector(".edit-profile-btn");
    const closeModalBtn = modal.querySelector(".p-close");

    function openUpdateProfileModal() {
        modal.style.display = "block";
    }

    function closeUpdateProfileModal() {
        modal.style.display = "none";
    }

    openModalBtn.addEventListener("click", openUpdateProfileModal);
    closeModalBtn.addEventListener("click", closeUpdateProfileModal);

    // Close modal when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            closeUpdateProfileModal();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const profilePictureInput = document.getElementById('profile_picture');
    const currentProfilePicture = document.getElementById('currentProfilePicture');
    const newProfilePicturePreview = document.getElementById('newProfilePicturePreview');

    profilePictureInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                newProfilePicturePreview.src = e.target.result;
                newProfilePicturePreview.classList.remove('hidden');
                currentProfilePicture.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        } else {
            // If no file is selected, reset the preview
            newProfilePicturePreview.src = '#';
            newProfilePicturePreview.classList.add('hidden');
            currentProfilePicture.classList.remove('hidden');
        }
    });
});

// Portfolio Modal Functions
function openPortfolioModal(imageSrc, title, description) {
  const modal = document.getElementById('portfolioModal');
  const modalImage = document.getElementById('portfolioModalImage');
  const modalTitle = document.getElementById('portfolioModalTitle');
  const modalDesc = document.getElementById('portfolioModalDescription');
  
  modalImage.src = imageSrc;
  modalTitle.textContent = title;
  modalDesc.textContent = description;
  
  modal.style.display = 'block';
}

// Close portfolio modal
document.addEventListener('DOMContentLoaded', function() {
  const portfolioModal = document.getElementById('portfolioModal');
  const closePortfolioBtn = portfolioModal.querySelector('.portfolio-modal-close');
  
  closePortfolioBtn.addEventListener('click', function() {
    portfolioModal.style.display = 'none';
  });
  
  window.addEventListener('click', function(event) {
    if (event.target === portfolioModal) {
      portfolioModal.style.display = 'none';
    }
  });
});

// Show tabs
function showReviewsTable() {
  // Show the reviews tab
  document.getElementById('dashboardSection').style.display = 'block';
  document.getElementById('profileSection').style.display = 'none';
  
  // Show reviews table
  document.getElementById('clientsTable').style.display = 'none';
  document.getElementById('appointmentsTable').style.display = 'none';
  document.getElementById('reviewsTable').style.display = 'block';
  
  // Update active nav item - assuming you have nav items
  const navItems = document.querySelectorAll('.nav-item');
  navItems.forEach(item => item.classList.remove('active'));
  document.querySelector('[data-section="dashboard"]').classList.add('active');
}


// Share profile
function shareProfile() {
  // Get the current URL
  const profileUrl = window.location.href;
  
  // Check if the Web Share API is available
  if (navigator.share) {
    navigator.share({
      title: `${document.querySelector('.profile-name').textContent}'s Profile`,
      text: 'Check out this freelancer profile!',
      url: profileUrl,
    })
    .catch(error => console.log('Error sharing:', error));
  } else {
    // Fallback - copy to clipboard
    const tempInput = document.createElement('input');
    document.body.appendChild(tempInput);
    tempInput.value = profileUrl;
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    
    // Show a notification
    alert('Profile link copied to clipboard!');
  }
}

// Add this to your existing JavaScript
document.addEventListener("DOMContentLoaded", function() {
  // Tab switching
  const tabButtons = document.querySelectorAll('.p-tab-btn');
  const tabContents = document.querySelectorAll('.p-tab-content');
  
  tabButtons.forEach(button => {
    button.addEventListener('click', function() {
      const tabId = this.dataset.tab;
      
      // Remove active class from all tabs
      tabButtons.forEach(btn => btn.classList.remove('p-active'));
      tabContents.forEach(content => content.classList.remove('p-active'));
      
      // Add active class to current tab
      this.classList.add('p-active');
      document.getElementById(`${tabId}-tab`).classList.add('p-active');
    });
  });
  
  // Profile picture preview
  const profilePictureInput = document.getElementById('profilePictureInput');
  const avatarPreview = document.getElementById('avatarPreview');
  
  if (profilePictureInput && avatarPreview) {
    profilePictureInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          avatarPreview.src = e.target.result;
        };
        
        reader.readAsDataURL(this.files[0]);
      }
    });
  }
  
  // Skills input handling
  const skillInput = document.getElementById('skillInput');
  const skillsContainer = document.getElementById('skills-container');
  
  if (skillInput && skillsContainer) {
    skillInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        
        const skill = this.value.trim();
        if (skill) {
          // Check if skill already exists
          const existingSkills = Array.from(skillsContainer.querySelectorAll('.p-tag')).map(tag => 
            tag.getAttribute('data-value').toLowerCase()
          );
          
          if (!existingSkills.includes(skill.toLowerCase())) {
            addSkillTag(skill);
          }
          
          this.value = '';
        }
      }
    });
    
    // Remove skill tag
    skillsContainer.addEventListener('click', function(e) {
      if (e.target.classList.contains('p-tag-remove')) {
        e.target.closest('.p-tag').remove();
      }
    });
    
    function addSkillTag(skill) {
      const tag = document.createElement('span');
      tag.className = 'p-tag';
      tag.setAttribute('data-value', skill);
      tag.innerHTML = `
        ${skill}
        <i class='bx bx-x p-tag-remove'></i>
        <input type="hidden" name="skills[]" value="${skill}">
      `;
      skillsContainer.appendChild(tag);
    }
  }
  
  // Bio character count
  const bioTextarea = document.getElementById('bio');
  const bioCount = document.getElementById('bioCount');
  
  if (bioTextarea && bioCount) {
    bioTextarea.addEventListener('input', function() {
      const count = this.value.length;
      bioCount.textContent = count;
      
      if (count > 300) {
        this.value = this.value.substring(0, 300);
        bioCount.textContent = 300;
      }
    });
  }
  
  // Rate toggle functionality
  const rateCheckboxes = document.querySelectorAll('.p-rate-checkbox');
  
  rateCheckboxes.forEach(checkbox => {
    // Set initial state
    handleRateToggle(checkbox);
    
    // Handle toggle changes
    checkbox.addEventListener('change', function() {
      handleRateToggle(this);
    });
  });
  
  function handleRateToggle(checkbox) {
    const rateOption = checkbox.closest('.p-rate-option');
    const inputIcon = rateOption.querySelector('.p-input-icon');
    const input = rateOption.querySelector('input[type="number"]');
    
    if (checkbox.checked) {
      inputIcon.classList.remove('p-disabled');
      input.disabled = false;
      input.focus();
    } else {
      inputIcon.classList.add('p-disabled');
      input.disabled = true;
      input.value = '';
    }
  }
  
  // Form validation across tabs
  const editProfileForm = document.getElementById('editProfileForm');
  
  if (editProfileForm) {
    editProfileForm.addEventListener('submit', function(e) {
      // Temporarily make all tabs visible to ensure form validation works
      const tabContents = document.querySelectorAll('.p-tab-content');
      const originalDisplayStyles = [];
      
      // Store original display styles and make all tabs visible but hidden
      tabContents.forEach(content => {
        originalDisplayStyles.push(content.style.display);
        content.style.display = 'block';
        content.style.height = '0';
        content.style.overflow = 'hidden';
        content.style.opacity = '0';
      });
      
      // Check if at least one rate is enabled and has a value
      const hourlyEnabled = document.getElementById('enable_hourly').checked;
      const dailyEnabled = document.getElementById('enable_daily').checked;
      const weeklyEnabled = document.getElementById('enable_weekly').checked;
      
      let rateValid = false;
      
      if (hourlyEnabled) {
        const hourlyRate = document.getElementById('hourly_rate').value;
        if (hourlyRate && hourlyRate > 0) rateValid = true;
      }
      
      if (dailyEnabled) {
        const dailyRate = document.getElementById('daily_rate').value;
        if (dailyRate && dailyRate > 0) rateValid = true;
      }
      
      if (weeklyEnabled) {
        const weeklyRate = document.getElementById('weekly_rate').value;
        if (weeklyRate && weeklyRate > 0) rateValid = true;
      }
      
      if (!rateValid && (hourlyEnabled || dailyEnabled || weeklyEnabled)) {
        e.preventDefault();
        alert('Please enter valid values for the rate options you\'ve enabled.');
        
        // Switch to rates tab
        document.querySelector('.p-tab-btn[data-tab="rates"]').click();
        
        // Restore original display styles
        tabContents.forEach((content, index) => {
          content.style.display = originalDisplayStyles[index];
          content.style.height = '';
          content.style.overflow = '';
          content.style.opacity = '';
        });
        
        return false;
      }
      
      // Let the browser's validation run
      const isValid = editProfileForm.checkValidity();
      
      if (!isValid) {
        e.preventDefault();
        
        // Find the first invalid input
        const firstInvalidInput = editProfileForm.querySelector(':invalid');
        
        if (firstInvalidInput) {
          // Find which tab contains the invalid input
          const tabContent = firstInvalidInput.closest('.p-tab-content');
          const tabId = tabContent.id.replace('-tab', '');
          
          // Switch to that tab
          const tabButton = document.querySelector(`.p-tab-btn[data-tab="${tabId}"]`);
          if (tabButton) {
            tabButton.click();
          }
          
          // Focus the invalid input
          setTimeout(() => {
            firstInvalidInput.focus();
          }, 100);
        }
      }
      
      // Restore original display styles
      tabContents.forEach((content, index) => {
        content.style.display = originalDisplayStyles[index];
        content.style.height = '';
        content.style.overflow = '';
        content.style.opacity = '';
      });
      
      if (!isValid) {
        return false;
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function() {
  // Profile picture upload trigger
  const avatarWrapper = document.getElementById('avatarWrapper');
  const profilePictureInput = document.getElementById('profilePictureInput');
  
  if (avatarWrapper && profilePictureInput) {
    // When clicking on the avatar wrapper, trigger the file input
    avatarWrapper.addEventListener('click', function() {
      profilePictureInput.click();
    });
    
    // Handle the file change to update the preview
    profilePictureInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          document.getElementById('avatarPreview').src = e.target.result;
        };
        
        reader.readAsDataURL(this.files[0]);
      }
    });
  }
});


// Function for image preview
function pPreviewImage(event) {
  const file = event.target.files[0];
  const currentImage = document.getElementById('currentProfilePicture');
  const newImage = document.getElementById('newProfilePicturePreview');
  
  if (file) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
      newImage.src = e.target.result;
      newImage.classList.remove('p-hidden');
      currentImage.classList.add('p-hidden');
    };
    
    reader.readAsDataURL(file);
  }
}

// Update modal open function
function openUpdateProfileModal() {
  document.getElementById('editProfileModal').style.display = 'block';
}
    </script>

