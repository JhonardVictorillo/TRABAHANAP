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
            <span class="status-value verified status-tooltip" data-tooltip="Your profile is verified. Clients are more likely to trust verified freelancers.">
              <i class='bx bxs-check-circle'></i> Verified
            </span>
          @else
            <span class="status-value not-verified status-tooltip" data-tooltip="Your profile is pending verification. Please complete all requirements or wait for admin approval.">
              <i class='bx bxs-time-five'></i> Pending Verification
            </span>
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
            <h2>Profile</h2>
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
                <span class="info-value">  {{ $user->experience_years ? $user->experience_years . ' yrs' : 'Not specified' }}</span>
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
          <label for="experience_years" class="p-label">Years of Experience</label>
          <div class="p-input-icon">
            <i class='bx bx-medal'></i>
            <input type="number" id="experience_years" name="experience_years" class="p-input" min="0" max="100"
              value="{{ $user->experience_years ?? '' }}" placeholder="Enter number of years" required>
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
          <label for="barangay" class="p-label">Barangay</label>
          <div class="p-input-icon">
            <i class='bx bx-map-alt'></i>
            <input type="text" id="barangay" name="barangay" value="{{ $user->barangay ?? '' }}" class="p-input" required>
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
          <i class='bx bx-save'></i>
          <span class="btn-text">Save Changes</span>
          <span class="btn-spinner" style="display:none;">
            <i class="fas fa-spinner fa-spin"></i>
          </span>
        </button>
      </div>
    </form>
  </div>
  </div>
    

    <!-- Dashboard Section -->
   <div class="dashboard-section" id="dashboardSection" style="display: none;">
  <!-- Dashboard Header -->
  <div class="dashboard-header">
    <div class="header-content">
      <h1 class="dashboard-title">
        <i class='bx bx-chart-line'></i>
        Dashboard Overview
      </h1>
      <p class="dashboard-subtitle">Monitor your business performance and client interactions</p>
    </div>
    <div class="dashboard-actions">
      <button class="refresh-btn" onclick="refreshDashboard()">
        <i class='bx bx-refresh'></i>
        Refresh Data
      </button>
    </div>
  </div>

  <!-- Enhanced Stats Cards -->
  <div class="dashboard-content">
    <div class="stats-cards">
      <div class="card enhanced-card clients-card" id="clientsCard" onclick="showTable('clients')">
        <div class="card-icon">
          <i class='bx bx-group'></i>
        </div>
        <div class="card-info">
          <div class="card-number">{{ $totalClients }}</div>
          <h3>Total Clients</h3>
          <p class="card-subtitle">Active client relationships</p>
        </div>
        <div class="card-indicator active"></div>
      </div>

      <div class="card enhanced-card appointments-card" id="appointmentsCard" onclick="showTable('appointments')">
        <div class="card-icon">
          <i class='bx bx-calendar-event'></i>
        </div>
        <div class="card-info">
          <div class="card-number">{{ $totalAppointments }}</div>
          <h3>Total Appointments</h3>
          <p class="card-subtitle">All time bookings</p>
        </div>
        <div class="card-indicator"></div>
      </div>

      <div class="card enhanced-card reviews-card" id="reviewsCard" onclick="showTable('reviews')">
        <div class="card-icon">
          <i class='bx bx-star'></i>
        </div>
        <div class="card-info">
          <div class="card-number">{{ number_format($averageRating, 1) }}</div>
          <h3>Average Rating</h3>
          <p class="card-subtitle">{{ $ratingBreakdown->sum() }} total reviews</p>
        </div>
        <div class="card-indicator"></div>
      </div>
    </div>

    <!-- Enhanced Tables Section -->
   
    <div class="tables-container">
      <!-- Clients Table -->
      <div class="table-container enhanced-table-container" id="clientsTable">
        <div class="table-header">
          <div class="table-title">
            <i class='bx bx-group'></i>
            <h3>Client Management</h3>
          </div>
          <div class="table-controls">
            <div class="search-container">
              <i class='bx bx-search'></i>
           <form method="GET" action="{{ route('freelancer.dashboard') }}#clientsTable" class="search-form" style="display: flex; align-items: center;">
                <input type="hidden" name="active_tab" value="clients">
                @if(request('client_filter'))
                  <input type="hidden" name="client_filter" value="{{ request('client_filter') }}">
                @endif
                <i class='bx bx-search'></i>
                <input
                  type="text"
                  name="client_search"
                  placeholder="Search clients..."
                  class="search-input"
                  value="{{ request('client_search') }}"
                  style="margin-left: 0.5em;"
                >
                <button type="submit" style="display:none"></button>
              </form>
            </div>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="enhanced-table">
            <thead>
              <tr>
                <th>
                  <div class="th-content">
                    <i class='bx bx-user'></i>
                    Client Information
                  </div>
                </th>
                <th>
                  <div class="th-content">
                    <i class='bx bx-envelope'></i>
                    Contact Details
                  </div>
                </th>
                <th>
                  <div class="th-content">
                    <i class='bx bx-calendar'></i>
                    Member Since
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse ($clients as $client)
                <tr class="table-row">
                  <td>
                    <div class="client-profile">
                      <div class="client-avatar">
                        <img src="{{ $client->profile_picture ? asset('storage/' . $client->profile_picture) : asset('images/defaultprofile.jpg') }}" 
                             alt="{{ $client->firstname }}" onerror="this.src='{{ asset('images/defaultprofile.jpg') }}'">
                      </div>
                      <div class="client-details">
                        <span class="client-name">{{ $client->firstname }} {{ $client->lastname }}</span>
                        <span class="client-status">Active Client</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-details">
                      <div class="contact-item">
                        <i class='bx bx-envelope'></i>
                        <span>{{ $client->email }}</span>
                      </div>
                      <div class="contact-item">
                        <i class='bx bx-phone'></i>
                        <span>{{ $client->contact_number ?? 'Not provided' }}</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="date-info">
                      <span class="date">{{ $client->created_at->format('M d, Y') }}</span>
                      <span class="time-ago">{{ $client->created_at->diffForHumans() }}</span>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3">
                    <div class="empty-state">
                      <i class='bx bx-group'></i>
                      <h4>No clients yet</h4>
                      <p>Your client list will appear here once you start getting appointments</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Clients Pagination -->
        <div class="table-pagination">
            <div class="pagination-info">
                <span>Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} clients</span>
            </div>
            <div class="pagination-controls">
                @if ($clients->onFirstPage())
                    <button class="pagination-btn" disabled>
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </button>
                @else
                    <a href="{{ $clients->previousPageUrl() }}&active_tab=clients" class="pagination-btn">
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </a>
                @endif
                
                <div class="pagination-numbers">
                    @foreach ($clients->getUrlRange(1, $clients->lastPage()) as $page => $url)
                        @if ($page == $clients->currentPage())
                            <button class="page-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&active_tab=clients" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if ($clients->hasMorePages())
                    <a href="{{ $clients->nextPageUrl() }}&active_tab=clients" class="pagination-btn">
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </a>
                @else
                    <button class="pagination-btn" disabled>
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </button>
                @endif
            </div>
        </div>
      </div>
      <!-- END Clients Table -->

      <!-- Appointments Table -->
      <div class="table-container enhanced-table-container" id="appointmentsTable" style="display: none;">
        <div class="table-header">
          <div class="table-title">
            <i class='bx bx-calendar-event'></i>
            <h3>Appointment Management</h3>
          </div>
          <div class="table-controls">
            <div class="search-container">
            
              <form method="GET" action="{{ route('freelancer.dashboard') }}#appointmentsTable" class="search-form" style="display: flex; align-items: center;">
                <input type="hidden" name="active_tab" value="appointments">
                @if(request('status_filter'))
                  <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                @endif
                <i class='bx bx-search'></i>
                <input
                  type="text"
                  name="appointment_search"
                  placeholder="Search appointments..."
                  class="search-input"
                  value="{{ request('appointment_search') }}"
                  style="margin-left: 0.5em;"
                >
                <button type="submit" style="display:none"></button>
              </form>
              </div>
            <div class="filter-container">
            <form method="GET" action="{{ route('freelancer.dashboard') }}#appointmentsTable" class="filter-form" style="display: flex; align-items: center;">
                <input type="hidden" name="active_tab" value="appointments">
                @if(request('appointment_search'))
                  <input type="hidden" name="appointment_search" value="{{ request('appointment_search') }}">
                @endif
                <select name="status_filter" class="filter-select" onchange="this.form.submit()">
                  <option value="">All Status</option>
                  <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="accepted" {{ request('status_filter') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                  <option value="completed" {{ request('status_filter') == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="declined" {{ request('status_filter') == 'declined' ? 'selected' : '' }}>Declined</option>
                   <option value="cancelled" {{ request('status_filter') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                   <option value="expired" {{ request('status_filter') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <button type="submit" style="display:none"></button>
              </form>
            </div>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="enhanced-table">
            <thead>
              <tr>
                <th>
                  <div class="th-content">
                    <i class='bx bx-user'></i>
                    Client & Service
                  </div>
                </th>
                <th>
                  <div class="th-content">
                    <i class='bx bx-calendar'></i>
                    Schedule
                  </div>
                </th>
                <th>
                  <div class="th-content">
                    <i class='bx bx-check-circle'></i>
                    Status
                  </div>
                </th>
               
              </tr>
            </thead>
            <tbody>
              @forelse ($appointments as $appointment)
                <tr class="table-row">
                  <td>
                    <div class="client-profile">
                      <div class="client-avatar">
                        <img src="{{ $appointment->customer->profile_picture ? asset('storage/' . $appointment->customer->profile_picture) : asset('images/defaultprofile.jpg') }}" 
                             alt="{{ $appointment->customer->firstname }}" onerror="this.src='{{ asset('images/defaultprofile.jpg') }}'">
                      </div>
                      <div class="client-details">
                        <span class="client-name">{{ $appointment->customer->firstname }} {{ $appointment->customer->lastname }}</span>
                        <span class="service-name">{{ $appointment->post->title ?? 'General Service' }}</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="schedule-info">
                      <div class="date-time">
                        <i class='bx bx-calendar'></i>
                        <span>{{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</span>
                      </div>
                      <div class="date-time">
                        <i class='bx bx-time'></i>
                        <span>{{ \Carbon\Carbon::parse($appointment->time)->format('g:i A') }}</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="status-badge status-{{ $appointment->status }}">
                      @if($appointment->status == 'pending')
                        <i class='bx bx-clock'></i>
                      @elseif($appointment->status == 'accepted')
                        <i class='bx bx-check'></i>
                      @elseif($appointment->status == 'completed')
                        <i class='bx bx-check-circle'></i>
                      @elseif($appointment->status == 'declined')
                        <i class='bx bx-x'></i>
                      @endif
                      {{ ucfirst($appointment->status) }}
                    </div>
                  </td>
                  
                </tr>
              @empty
                <tr>
                  <td colspan="4">
                    <div class="empty-state">
                      <i class='bx bx-calendar-x'></i>
                      <h4>No appointments yet</h4>
                      <p>Your appointment history will appear here</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Appointments Pagination -->
        <div class="table-pagination">
            <div class="pagination-info">
                <span>Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments</span>
            </div>
            <div class="pagination-controls">
                @if ($appointments->onFirstPage())
                    <button class="pagination-btn" disabled>
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </button>
                @else
                    <a href="{{ $appointments->previousPageUrl() }}&active_tab=appointments" class="pagination-btn">
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </a>
                @endif
                
                <div class="pagination-numbers">
                    @foreach ($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
                        @if ($page == $appointments->currentPage())
                            <button class="page-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&active_tab=appointments" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if ($appointments->hasMorePages())
                    <a href="{{ $appointments->nextPageUrl() }}&active_tab=appointments" class="pagination-btn">
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </a>
                @else
                    <button class="pagination-btn" disabled>
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </button>
                @endif
            </div>
        </div>
      </div>
      <!-- END Appointments Table -->

      <!-- Reviews Table -->
      <div class="table-container enhanced-table-container" id="reviewsTable" style="display: none;">
        <div class="table-header">
          <div class="table-title">
            <i class='bx bx-star'></i>
            <h3>Client Reviews & Ratings</h3>
          </div>
          <div class="table-controls">
            <div class="search-container">
             <form method="GET" action="{{ route('freelancer.dashboard') }}#reviewsTable" class="search-form" style="display: flex; align-items: center;">
                  <input type="hidden" name="active_tab" value="reviews">
                  @if(request('rating_filter'))
                    <input type="hidden" name="rating_filter" value="{{ request('rating_filter') }}">
                  @endif
                  <i class='bx bx-search'></i>
                  <input
                    type="text"
                    name="review_search"
                    placeholder="Search reviews..."
                    class="search-input"
                    value="{{ request('review_search') }}"
                    style="margin-left: 0.5em;"
                  >
                  <button type="submit" style="display:none"></button>
                </form>
            </div>
            <div class="filter-container">
             <form method="GET" action="{{ route('freelancer.dashboard') }}#reviewsTable" class="filter-form" style="display: flex; align-items: center;">
                <input type="hidden" name="active_tab" value="reviews">
                @if(request('review_search'))
                  <input type="hidden" name="review_search" value="{{ request('review_search') }}">
                @endif
                <select name="rating_filter" class="filter-select" onchange="this.form.submit()">
                  <option value="">All Ratings</option>
                  <option value="5" {{ request('rating_filter') == '5' ? 'selected' : '' }}>5 Stars</option>
                  <option value="4" {{ request('rating_filter') == '4' ? 'selected' : '' }}>4 Stars</option>
                  <option value="3" {{ request('rating_filter') == '3' ? 'selected' : '' }}>3 Stars</option>
                  <option value="2" {{ request('rating_filter') == '2' ? 'selected' : '' }}>2 Stars</option>
                  <option value="1" {{ request('rating_filter') == '1' ? 'selected' : '' }}>1 Star</option>
                </select>
                <button type="submit" style="display:none"></button>
              </form>
            </div>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="enhanced-table">
            <thead>
              <tr>
                <th>
                  <div class="th-content">
                    <i class='bx bx-user'></i>
                    Client Information
                  </div>
                </th>
                <th>
                  <div class="th-content">
                    <i class='bx bx-star'></i>
                    Rating & Review
                  </div>
                </th>
                <th>
                  <div class="th-content">
                    <i class='bx bx-calendar'></i>
                    Date Received
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse ($reviews as $review)
                <tr class="table-row">
                  <td>
                    <div class="client-profile">
                      <div class="client-avatar">
                        <img src="{{ $review->customer->profile_picture ? asset('storage/' . $review->customer->profile_picture) : asset('images/defaultprofile.jpg') }}" 
                             alt="{{ $review->customer->firstname }}" onerror="this.src='{{ asset('images/defaultprofile.jpg') }}'">
                      </div>
                      <div class="client-details">
                        <span class="client-name">{{ $review->customer->firstname }} {{ $review->customer->lastname }}</span>
                        <span class="client-status">Verified Client</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="review-content">
                      <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                          @if ($i <= $review->rating)
                            <i class='bx bxs-star star-filled'></i>
                          @else
                            <i class='bx bx-star star-empty'></i>
                          @endif
                        @endfor
                        <span class="rating-number">{{ $review->rating }}/5</span>
                      </div>
                      <p class="review-text">{{ $review->review ?? 'No comment provided' }}</p>
                    </div>
                  </td>
                  <td>
                    <div class="date-info">
                      <span class="date">{{ $review->created_at->format('M d, Y') }}</span>
                      <span class="time-ago">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3">
                    <div class="empty-state">
                      <i class='bx bx-star'></i>
                      <h4>No reviews yet</h4>
                      <p>Client reviews will appear here after completed appointments</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Reviews Pagination -->
        <div class="table-pagination">
            <div class="pagination-info">
                <span>Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} reviews</span>
            </div>
            <div class="pagination-controls">
                @if ($reviews->onFirstPage())
                    <button class="pagination-btn" disabled>
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </button>
                @else
                    <a href="{{ $reviews->previousPageUrl() }}&active_tab=reviews" class="pagination-btn">
                        <i class='bx bx-chevron-left'></i>
                        Previous
                    </a>
                @endif
                
                <div class="pagination-numbers">
                    @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                        @if ($page == $reviews->currentPage())
                            <button class="page-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&active_tab=reviews" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if ($reviews->hasMorePages())
                    <a href="{{ $reviews->nextPageUrl() }}&active_tab=reviews" class="pagination-btn">
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </a>
                @else
                    <button class="pagination-btn" disabled>
                        Next
                        <i class='bx bx-chevron-right'></i>
                    </button>
                @endif
            </div>
        </div>
      </div>
      <!-- END Reviews Table -->
    </div>
    <!-- END Tables Container -->
  </div>
  <!-- END Dashboard Content -->
</div>
<!-- END Dashboard Section -->



    <script>
// ===============================================
// MAIN DASHBOARD INITIALIZATION
// ===============================================
document.addEventListener("DOMContentLoaded", function () {
    console.log('Dashboard initializing...'); // Debug log
    
    // Initialize all components
    initializeModals();
    initializeProfilePicture();
    initializePortfolioModal();
    initializeDashboardTables();
    initializeProfileTabs();
    initializeSearchAndFilters();
    initializeFormValidation();
    
    console.log('Dashboard initialization complete'); // Debug log
});

// ===============================================
// MODAL INITIALIZATION
// ===============================================
function initializeModals() {
    const modal = document.getElementById("editProfileModal");
    const openModalBtn = document.querySelector(".edit-profile-btn");
    const closeModalBtn = modal?.querySelector(".p-close");

    if (!modal || !openModalBtn || !closeModalBtn) {
        console.warn('Modal elements not found');
        return;
    }

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

    // Make openUpdateProfileModal globally available
    window.openUpdateProfileModal = openUpdateProfileModal;
}

// ===============================================
// PROFILE PICTURE HANDLING
// ===============================================
function initializeProfilePicture() {
    // Legacy profile picture handling
    const profilePictureInput = document.getElementById('profile_picture');
    const currentProfilePicture = document.getElementById('currentProfilePicture');
    const newProfilePicturePreview = document.getElementById('newProfilePicturePreview');

    if (profilePictureInput && currentProfilePicture && newProfilePicturePreview) {
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
                newProfilePicturePreview.src = '#';
                newProfilePicturePreview.classList.add('hidden');
                currentProfilePicture.classList.remove('hidden');
            }
        });
    }

    // New profile picture handling
    const avatarWrapper = document.getElementById('avatarWrapper');
    const profilePictureInputNew = document.getElementById('profilePictureInput');
    const avatarPreview = document.getElementById('avatarPreview');

    if (avatarWrapper && profilePictureInputNew) {
        avatarWrapper.addEventListener('click', function() {
            profilePictureInputNew.click();
        });

        profilePictureInputNew.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
}

// ===============================================
// PORTFOLIO MODAL
// ===============================================
function initializePortfolioModal() {
    const portfolioModal = document.getElementById('portfolioModal');
    if (!portfolioModal) return;

    const closePortfolioBtn = portfolioModal.querySelector('.portfolio-modal-close');
    
    if (closePortfolioBtn) {
        closePortfolioBtn.addEventListener('click', function() {
            portfolioModal.style.display = 'none';
        });
    }
    
    window.addEventListener('click', function(event) {
        if (event.target === portfolioModal) {
            portfolioModal.style.display = 'none';
        }
    });
}

function openPortfolioModal(imageSrc, title, description) {
    const modal = document.getElementById('portfolioModal');
    const modalImage = document.getElementById('portfolioModalImage');
    const modalTitle = document.getElementById('portfolioModalTitle');
    const modalDesc = document.getElementById('portfolioModalDescription');
    
    if (modal && modalImage && modalTitle && modalDesc) {
        modalImage.src = imageSrc;
        modalTitle.textContent = title;
        modalDesc.textContent = description;
        modal.style.display = 'block';
    }
}

// ===============================================
// DASHBOARD TABLES MANAGEMENT
// ===============================================
function initializeDashboardTables() {
    console.log('Initializing dashboard tables...'); // Debug log
    
    // Check URL parameters on page load to maintain tab state
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('active_tab');
    
    // Ensure all tables exist before proceeding
    const tablesExist = {
        clients: document.getElementById('clientsTable'),
        appointments: document.getElementById('appointmentsTable'),
        reviews: document.getElementById('reviewsTable')
    };
    
    console.log('Tables found:', tablesExist); // Debug log
    
    // Set active tab
    if (activeTab && ['clients', 'appointments', 'reviews'].includes(activeTab) && tablesExist[activeTab]) {
        console.log('Restoring active tab:', activeTab); // Debug log
        showTable(activeTab);
    } else {
        console.log('Setting default tab: clients'); // Debug log
        showTable('clients'); // Default tab
    }
    
    // Add click event listeners to cards with error handling
    const cards = {
        clients: document.getElementById('clientsCard'),
        appointments: document.getElementById('appointmentsCard'),
        reviews: document.getElementById('reviewsCard')
    };
    
    Object.keys(cards).forEach(cardType => {
        const card = cards[cardType];
        if (card) {
            // Remove existing click listener to prevent duplicates
            const newCard = card.cloneNode(true);
            card.parentNode.replaceChild(newCard, card);
            
            newCard.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Card clicked:', cardType); // Debug log
                showTable(cardType);
            });
            console.log('Event listener added to:', cardType + 'Card'); // Debug log
        } else {
            console.error('Card not found:', cardType + 'Card'); // Debug log
        }
    });
}

// Enhanced table switching function with better error handling
function showTable(tableType) {
    console.log('Switching to table:', tableType); // Debug log
    
    // Hide all tables
    const tables = ['clientsTable', 'appointmentsTable', 'reviewsTable'];
    tables.forEach(tableId => {
        const table = document.getElementById(tableId);
        if (table) {
            table.style.display = 'none';
            console.log('Hiding table:', tableId); // Debug log
        } else {
            console.error('Table not found:', tableId); // Debug log
        }
    });
    
    // Remove active indicators from all cards
    document.querySelectorAll('.enhanced-card .card-indicator').forEach(indicator => {
        indicator.classList.remove('active');
    });
    
    // Remove active state from all cards
    document.querySelectorAll('.enhanced-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Show selected table and update active state
    const targetTable = document.getElementById(tableType + 'Table');
    if (targetTable) {
        targetTable.style.display = 'block';
        console.log('Showing table:', tableType + 'Table'); // Debug log
    } else {
        console.error('Target table not found:', tableType + 'Table'); // Debug log
        return;
    }
    
    const activeCard = document.querySelector(`.${tableType}-card`);
    if (activeCard) {
        activeCard.classList.add('active');
        const indicator = activeCard.querySelector('.card-indicator');
        if (indicator) {
            indicator.classList.add('active');
        }
        console.log('Activated card:', `.${tableType}-card`); // Debug log
    } else {
        console.error('Active card not found:', `.${tableType}-card`); // Debug log
    }
    
    // Update URL to maintain tab state
    const url = new URL(window.location);
    url.searchParams.set('active_tab', tableType);
    window.history.replaceState({}, '', url);
}

// ===============================================
// PROFILE TABS MANAGEMENT
// ===============================================
function initializeProfileTabs() {
    const tabButtons = document.querySelectorAll('.p-tab-btn');
    const tabContents = document.querySelectorAll('.p-tab-content');
    
    if (tabButtons.length === 0 || tabContents.length === 0) {
        console.warn('Profile tab elements not found');
        return;
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('p-active'));
            tabContents.forEach(content => content.classList.remove('p-active'));
            
            // Add active class to current tab
            this.classList.add('p-active');
            const targetTab = document.getElementById(`${tabId}-tab`);
            if (targetTab) {
                targetTab.classList.add('p-active');
            }
        });
    });
    
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
}

function addSkillTag(skill) {
    const skillsContainer = document.getElementById('skills-container');
    if (!skillsContainer) return;
    
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

// ===============================================
// SEARCH AND FILTER FUNCTIONALITY
// ===============================================
function initializeSearchAndFilters() {
    // Search functionality
    const searchInputs = {
        clientSearch: 'clients',
        appointmentSearch: 'appointments',
        reviewSearch: 'reviews'
    };

    Object.keys(searchInputs).forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                searchTable(searchInputs[inputId], e.target.value);
            });
        }
    });

    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function(e) {
            filterAppointmentsByStatus(e.target.value);
        });
    }
    
    if (ratingFilter) {
        ratingFilter.addEventListener('change', function(e) {
            filterReviewsByRating(e.target.value);
        });
    }
}

function searchTable(tableType, searchTerm) {
    const table = document.getElementById(tableType + 'Table');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr:not(.empty-state)');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm.toLowerCase()) ? '' : 'none';
    });
}

function filterAppointmentsByStatus(status) {
    const rows = document.querySelectorAll('#appointmentsTable tbody tr:not(.empty-state)');
    
    rows.forEach(row => {
        if (!status) {
            row.style.display = '';
        } else {
            const statusBadge = row.querySelector('.status-badge');
            if (statusBadge) {
                const rowStatus = statusBadge.textContent.trim().toLowerCase();
                row.style.display = rowStatus.includes(status) ? '' : 'none';
            }
        }
    });
}

function filterReviewsByRating(rating) {
    const rows = document.querySelectorAll('#reviewsTable tbody tr:not(.empty-state)');
    
    rows.forEach(row => {
        if (!rating) {
            row.style.display = '';
        } else {
            const ratingNumber = row.querySelector('.rating-number');
            if (ratingNumber) {
                const rowRating = parseInt(ratingNumber.textContent.split('/')[0]);
                row.style.display = rowRating === parseInt(rating) ? '' : 'none';
            }
        }
    });
}

// ===============================================
// FORM VALIDATION
// ===============================================
function initializeFormValidation() {
    const editProfileForm = document.getElementById('editProfileForm');
    
    if (!editProfileForm) {
        console.warn('Edit profile form not found');
        return;
    }
    
    editProfileForm.addEventListener('submit', function(e) {
        let valid = true;
        let firstInvalid = null;

        // Required fields validation
        const requiredFields = [
            { id: 'firstname', name: 'First Name' },
            { id: 'lastname', name: 'Last Name' },
            { id: 'email', name: 'Email' },
            { id: 'experience_years', name: 'Years of Experience' },
            { id: 'contact_number', name: 'Contact Number' },
            { id: 'province', name: 'Province' },
            { id: 'city', name: 'City' },
            { id: 'zipcode', name: 'Zipcode' }
        ];

        requiredFields.forEach(field => {
            const input = document.getElementById(field.id);
            if (input && !input.value.trim()) {
                valid = false;
                input.classList.add('border-red-500');
                if (!firstInvalid) firstInvalid = input;
            } else if (input) {
                input.classList.remove('border-red-500');
            }
        });

        // Years of experience validation
        const expInput = document.getElementById('experience_years');
        if (expInput && (isNaN(expInput.value) || expInput.value < 0)) {
            valid = false;
            expInput.classList.add('border-red-500');
            if (!firstInvalid) firstInvalid = expInput;
        }

        // Bio length validation
        const bioInput = document.getElementById('bio');
        if (bioInput && bioInput.value.length > 300) {
            valid = false;
            bioInput.classList.add('border-red-500');
            if (!firstInvalid) firstInvalid = bioInput;
        }

        if (!valid) {
            e.preventDefault();
            alert('Please fill out all required fields correctly.');
            if (firstInvalid) {
                // Find which tab contains the invalid input
                const tabContent = firstInvalid.closest('.p-tab-content');
                if (tabContent) {
                    const tabId = tabContent.id.replace('-tab', '');
                    const tabButton = document.querySelector(`.p-tab-btn[data-tab="${tabId}"]`);
                    if (tabButton) {
                        tabButton.click();
                    }
                }
                setTimeout(() => firstInvalid.focus(), 100);
            }
            return false;
        }
    });
}

// ===============================================
// UTILITY FUNCTIONS
// ===============================================

// Show reviews table function
function showReviewsTable() {
    console.log('Switching to reviews table from profile section');
    
    // Hide profile section and show dashboard section
    const profileSection = document.getElementById('profileSection');
    const dashboardSection = document.getElementById('dashboardSection');
    
    if (profileSection) profileSection.style.display = 'none';
    if (dashboardSection) dashboardSection.style.display = 'block';
    
    // Show reviews table specifically
    showTable('reviews');
    
    // Update sidebar navigation - remove active from all nav items
    const navItems = document.querySelectorAll('.sidebar-links li a');
    navItems.forEach(item => item.classList.remove('active'));
    
    // Add active class to dashboard nav item
    const dashboardNav = document.querySelector('.sidebar-links li a[href="#dashboardSection"]');
    if (dashboardNav) {
        dashboardNav.classList.add('active');
        console.log('Dashboard nav activated');
    } else {
        // Fallback - look for dashboard link by text content or other attributes
        const dashboardLink = Array.from(navItems).find(link => 
            link.textContent.toLowerCase().includes('dashboard') || 
            link.getAttribute('href') === '#dashboardSection' ||
            link.getAttribute('data-section') === 'dashboard'
        );
        if (dashboardLink) {
            dashboardLink.classList.add('active');
            console.log('Dashboard nav activated (fallback method)');
        }
    }
    
    // Update localStorage to maintain state
    localStorage.setItem('activeSection', '#dashboardSection');
    
    // Update URL to show we're in dashboard with reviews active
    const url = new URL(window.location);
    url.searchParams.set('active_tab', 'reviews');
    window.history.replaceState({}, '', url);
}

// Share profile function
function shareProfile() {
    const profileUrl = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: `${document.querySelector('.profile-name')?.textContent || 'Freelancer'}'s Profile`,
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
        
        alert('Profile link copied to clipboard!');
    }
}

// Image preview function
function pPreviewImage(event) {
    const file = event.target.files[0];
    const currentImage = document.getElementById('currentProfilePicture');
    const newImage = document.getElementById('newProfilePicturePreview');
    
    if (file && currentImage && newImage) {
        const reader = new FileReader();
        reader.onload = function(e) {
            newImage.src = e.target.result;
            newImage.classList.remove('p-hidden');
            currentImage.classList.add('p-hidden');
        };
        reader.readAsDataURL(file);
    }
}

// Refresh dashboard function
function refreshDashboard() {
    const refreshBtn = document.querySelector('.refresh-btn');
    if (refreshBtn) {
        const originalContent = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Refreshing...';
        refreshBtn.disabled = true;
        
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
}

// ===============================================
// DEBUG FUNCTIONS (Remove in production)
// ===============================================
function debugDashboard() {
    console.log('=== DASHBOARD DEBUG INFO ===');
    console.log('Clients table:', document.getElementById('clientsTable'));
    console.log('Appointments table:', document.getElementById('appointmentsTable'));
    console.log('Reviews table:', document.getElementById('reviewsTable'));
    console.log('Clients card:', document.getElementById('clientsCard'));
    console.log('Appointments card:', document.getElementById('appointmentsCard'));
    console.log('Reviews card:', document.getElementById('reviewsCard'));
    console.log('=== END DEBUG INFO ===');
}

// Call debug function on load (remove in production)
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(debugDashboard, 1000);
});



</script>


