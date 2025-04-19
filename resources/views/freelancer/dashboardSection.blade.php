
      <main class="main-content">
      <div class="profile-section" id="profileSection">
      <div class="profile-container">
        <!-- Sidebar -->
        <div class="profile-card">
          <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-image" />
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
            <h2>Profile  <button class="edit-profile-icon-btn" onclick="openUpdateProfileModal()" title="Edit Profile">
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
            @foreach ($post->pictures as $picture)<!-- Assuming the relation name is 'postPictures' -->
              <img src="{{ asset('storage/' . $picture->image_path) }}" alt="Work Image" /> <!-- Adjust field name if different -->
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
<div id="editProfileModal" class="modal" >
  <div class="modal-content">
  <span class="close" >&times;</span>
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
    const openModalBtn = document.querySelector(".edit-profile-icon-btn");
    const closeModalBtn = modal.querySelector(".close");

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


    </script>

  