

         <!-- Dashboard Section -->
    
  
<div class="dashboard-section" id="dashboardSection" style="display: none;">
    
    <div class="content-header">
      <h2 class ="section-title">Dashboard</h2>
    </div>

    
    <section class="cards">
    <div class="card1" id="totalFreelancersCard">
          <span class="material-symbols-outlined">people</span>
          <div class="stats-details">
            <h3>Total Freelancers</h3>
            <p>{{ $totalFreelancers }}</p>
          </div>
        </div>
          <div class="card2" id="totalClientsCard">
              <span class="material-symbols-outlined">groups</span>
              <div class="stats-details">
              <h3>Total Clients</h3>
              <p>{{ $totalCustomers }}</p>
              </div>
          </div>
          <div class="card3" id="pendingAccountsCard">
              <span class="material-symbols-outlined">pending</span>
              <div class="stats-details">
              <h3>Pending Accounts</h3>
              <p>{{ $totalPendingAccounts }}</p>
              </div>
          </div>
          <div class="card4" id="pendingPostsCard">
              <span class="material-symbols-outlined">post_add</span>
              <div class="stats-details">
              <h3>Pending Posts</h3>
              <p>{{ $totalPendingPosts }}</p>
              </div>     
      </div>     
   
    </section>

    <!-- Total Freelancers Section -->
    <div class="details-section" id="totalFreelancersSection" style="display: none;">
      <h2>Total Freelancers</h2>
      <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Service Category</th>
            <th>Status</th>
           
          </tr>
        </thead>
        <tbody>
        @forelse($freelancers as $freelancer)
          <tr>
            <td>
                 @if($freelancer->profile_picture)
                    <img src="{{ asset('storage/' . $freelancer->profile_picture) }}" alt="Profile Picture" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
                    @else
                    <img src="{{ asset('images/defaultprofile.jpg') }}" alt="Default Profile" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
                    @endif
            </td>
            <td>{{ $freelancer->lastname }} ,{{ $freelancer->firstname }}</td>
            <td>{{ $freelancer->email }}</td>
            <td> 
            @forelse($freelancer->categories as $category)
        <span>{{ $category->name }}</span>@if (!$loop->last), @endif
            @empty
                N/A
            @endforelse
           </td>
            <td> @if($freelancer->is_verified)
                    <span style="color:green;">Verified</span>
                @else
                    <span style="color:red;">Not Verified</span>
                @endif</td>

                 <!-- Ban/Unban Button Column -->
             
          </tr>
          @empty
                <tr>
                    <td colspan="5">No freelancers found.</td>
                </tr>
            @endforelse
          
        </tbody>
      </table>
      </div>
       <div class="category-pagination-container">
        {{ $freelancers->appends(request()->except('freelancersPage'))->links() }}
    </div>
    </div>

    <!-- Total Clients Section -->
    <div class="details-section" id="totalClientsSection" style="display: none;">
      <h2>Total Clients</h2>
      <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
           
          </tr>
        </thead>
        <tbody>
        @foreach($customer as $client)
          <tr>
            <td>  @if($client->profile_picture)
                <img src="{{ asset('storage/' . $client->profile_picture) }}" alt="Profile Picture" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
                @else
                <img src="{{ asset('images/defaultprofile.jpg') }}" alt="Default Profile" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
                @endif
            </td>
            <td>{{ $client->firstname }} {{ $client->lastname }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ $client->role }} </td>
           
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="category-pagination-container">
        {{ $customer->appends(request()->except('customersPage'))->links() }}
    </div>
    </div>

    <!-- Pending Accounts Section -->
    <div class="details-section" id="pendingAccountsSection" style="display: none;">
  <h2>Pending Accounts</h2>
  <div class="admin-table-container">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Profile</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @forelse($freelancers->where('is_verified', 0) as $freelancer)
        <tr>
            <td>
                @if($freelancer->profile_picture)
                <img src="{{ asset('storage/' . $freelancer->profile_picture) }}" alt="Profile Picture" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
                @else
                <img src="{{ asset('images/defaultprofile.jpg') }}" alt="Default Profile" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
                @endif
            </td>
            <td>{{ $freelancer->firstname }} {{ $freelancer->lastname }}</td>
            <td>{{ $freelancer->email }}</td>
            <td>{{ $freelancer->role ?? 'N/A' }}</td> 
            <td>
              @if($freelancer->is_verified)
                ✅ Verified
              @else
                🕒 Pending
              @endif
            </td>
            <td>
                @if(!$freelancer->is_verified)
                    <!-- Review Button -->
                    <button class="review-btn" onclick="openReviewModal({{ $freelancer->id }})">
                        <i class="fas fa-eye"></i> Review
                    </button>

                  
                @else
                    <button class="verified-btn">Verified</button>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No pending accounts found.</td>
        </tr>      
        @endforelse
    </tbody>
  </table>
 </div>
  <!-- Add Pagination -->
<div class="category-pagination-container">
    {{ $freelancers->appends(request()->except('freelancersPage'))->links() }}
</div>
</div>

    <!-- Pending Posts Section -->
    <div class="details-section" id="pendingPostsSection" style="display: none;">
    <h2>Pending Posts</h2>
    <div class="admin-table-container">
        <table class="admin-table">
          <thead>
            <tr>
           
              <th>Freelancer</th>
              <th>Category</th>
              <th>Sub-services</th>
              <th>Post-image</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($posts as $post)
            <tr>
            <td>{{ $post->freelancer->firstname }} {{ $post->freelancer->lastname }}</td>
            @foreach($post->freelancer->categories as $category)  
              <td>{{ $category->name }}</td>
              @endforeach
              <td> 
                @if($post->subServices && count($post->subServices))
                    <ul>
                        @foreach ($post->subServices as $subService)
                            <li>{{ $subService->sub_service }}</li> 
                        @endforeach
                    </ul>
                @else
                    <p>No sub-services available</p>
                @endif
              </td>
              
              <td>  
              @if($post->pictures && count($post->pictures))
                    @foreach ($post->pictures as $picture)
                        <img src="{{ asset('storage/' . $picture->image_path) }}" alt="Post Image" style="width: 50px; height: 50px;">
                    @endforeach
                @else
                    <p>No images available</p>
                @endif
              <td>  
                @if($post->approved)
                        <span style="color: green;">Approved</span>
                    @else
                        <span style="color: red;">Pending</span>
                    @endif
                </td>
              <td>
               @if(!$post->approved)
                  <button class="approve-btn" data-id="{{ $post->id }}">
                    <span class="btn-text">Approve</span>
                    <span class="btn-spinner" style="display:none;">
                      <i class="fas fa-spinner fa-spin"></i>
                    </span>
                  </button>
                  <button class="reject-btn" data-id="{{ $post->id }}">
                    <span class="btn-text">Reject</span>
                    <span class="btn-spinner" style="display:none;">
                      <i class="fas fa-spinner fa-spin"></i>
                    </span>
                  </button>
              @else
                  <span>Approved</span>
              @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- Add Pagination -->
    <div class="category-pagination-container">
        {{ $pendingPosts->appends(request()->except('pendingPostsPage'))->links() }}
    </div>
      </div>
  </div>
  
  

  <!-- Freelancer Review Modal -->
<div id="freelancerReviewModal" class="modal" style="display: none;">
    <div class="modal-content simple-review-modal">
        <!-- Modal Header -->
        <div class="simple-review-header">
            <div class="simple-header-content">
                <span class="material-symbols-outlined">person_check</span>
                <div>
                    <h3>Freelancer Application Review</h3>
                    <p>Review freelancer details before approval</p>
                </div>
            </div>
            <button class="simple-modal-close" onclick="closeReviewModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="simple-review-body">
            <!-- Freelancer Overview -->
            <div class="simple-freelancer-overview">
                <div class="simple-profile-section">
                    <img id="modalProfilePicture" src="" alt="Profile" class="simple-profile-pic">
                    <div class="simple-profile-details">
                        <h4 id="modalFullName"></h4>
                        <p id="modalEmail"></p>
                        <div class="simple-badges">
                            <span class="simple-badge" id="verificationBadge">
                                <span class="material-symbols-outlined" id="verificationIcon">schedule</span>
                                <span id="verificationText">Pending</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="simple-info-grid">
    <!-- Personal & Location Info Combined -->
    <div class="simple-info-card">
        <h5><span class="material-symbols-outlined">person</span> Personal & Location Information</h5>
        <div class="simple-info-item">
            <span class="simple-label">Contact:</span>
            <span id="modalContactNumber">N/A</span>
        </div>
        <div class="simple-info-item">
            <span class="simple-label">Province:</span>
            <span id="modalProvince">N/A</span>
        </div>
        <div class="simple-info-item">
            <span class="simple-label">City:</span>
            <span id="modalCity">N/A</span>
        </div>
        <div class="simple-info-item">
            <span class="simple-label">Barangay:</span>
            <span id="modalBarangay">N/A</span>
        </div>
    </div>

    <!-- Professional Info -->
    <div class="simple-info-card">
        <h5><span class="material-symbols-outlined">work</span> Professional Information</h5>
        <div class="simple-info-item">
            <span class="simple-label">Experience:</span>
            <span id="modalExperienceYears">N/A</span>
        </div>
        <div class="simple-info-item">
            <span class="simple-label">Specialization:</span>
            <span id="modalSpecialization">N/A</span>
        </div>
        <div class="simple-info-item">
            <span class="simple-label">Categories:</span>
            <span id="modalCategories">N/A</span>
        </div>
    </div>
</div>

            <!-- ID Verification Section -->
            <div class="simple-id-section">
                <h5><span class="material-symbols-outlined">badge</span> ID Verification</h5>
                <div class="simple-id-container">
                    <div class="simple-id-item">
                        <span class="simple-id-label">ID Front</span>
                        <img id="modalIdFront" src="" alt="ID Front" class="simple-id-image" onclick="enlargeImage(this)">
                    </div>
                    <div class="simple-id-item">
                        <span class="simple-id-label">ID Back</span>
                        <img id="modalIdBack" src="" alt="ID Back" class="simple-id-image" onclick="enlargeImage(this)">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="simple-review-footer">
            <div class="simple-footer-note">
                <span class="material-symbols-outlined">info</span>
                <span>Review all information before making a decision</span>
            </div>
            <div class="simple-footer-actions">
                <button type="button" class="simple-btn simple-btn-cancel" onclick="closeReviewModal()">
                    <span class="material-symbols-outlined">close</span>
                    Cancel
                </button>
                <button type="button" class="simple-btn simple-btn-decline" onclick="declineFromModal()">
                    <span class="material-symbols-outlined">cancel</span>
                    Decline
                </button>
                <button type="button" class="simple-btn simple-btn-approve" onclick="acceptFromModal()">
                    <span class="material-symbols-outlined">check_circle</span>
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Simplified Image Enlargement -->
<div id="imageEnlargeOverlay" class="simple-image-overlay" onclick="closeEnlargedImage()">
    <div class="simple-enlarged-container">
        <img id="enlargedImage" src="" alt="Enlarged ID">
        <button class="simple-enlarge-close" onclick="closeEnlargedImage()">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
</div>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const pendingPostsSection = document.querySelector('#pendingPostsSection');

    // Approve button click
    pendingPostsSection.addEventListener('click', function(e) {
        if (e.target.classList.contains('approve-btn')) {
            const postId = e.target.getAttribute('data-id');
            approvePost(postId, e.target);
        }
    });

    // Reject button click
    pendingPostsSection.addEventListener('click', function(e) {
        if (e.target.classList.contains('reject-btn')) {
            const postId = e.target.getAttribute('data-id');
            rejectPost(postId, e.target);
        }
    });

    function approvePost(postId, button) {
        if (!confirm("Are you sure you want to approve this post?")) return;
         showSpinnerOnButton(button);
        fetch(`/admin/posts/${postId}/approve`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
        })
        .then(response => response.json())
        .then(data => {
           restoreButton(button, 'Approve');
            if (data.success) {
                alert(data.message);
                updatePostRow(button.closest('tr'), 'approved');
            } else {
                alert('Approval failed.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function rejectPost(postId, button) {
        if (!confirm("Are you sure you want to reject this post?")) return;
        showSpinnerOnButton(button);
        fetch(`/admin/posts/${postId}/reject`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
        })
        .then(response => response.json())
        .then(data => {
            restoreButton(button, 'Reject');
            if (data.success) {
                alert(data.message);
                updatePostRow(button.closest('tr'), 'rejected');
            } else {
                alert('Rejection failed.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updatePostRow(row, status) {
        const statusCell = row.querySelector('td:nth-child(5)');
        const actionsCell = row.querySelector('td:nth-child(6)');

        if (status === 'approved') {
            statusCell.innerHTML = `<span style="color: green;">Approved</span>`;
            actionsCell.innerHTML = `<span>Approved</span>`;
        } else if (status === 'rejected') {
            statusCell.innerHTML = `<span style="color: red;">Rejected</span>`;
            actionsCell.innerHTML = `<span>Rejected</span>`;
        }
    }
});

// =======================
// FREELANCER REVIEW MODAL FUNCTIONS
// =======================

let currentFreelancerId = null;

// Open review modal
function openReviewModal(freelancerId) {
    currentFreelancerId = freelancerId;
    
    // Show modal
    document.getElementById('freelancerReviewModal').style.display = 'block';
    
    // Add loading state to modal
    const modalBody = document.querySelector('.simple-review-body');
    const originalContent = modalBody.innerHTML;
    modalBody.innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: #2563eb; animation: spin 1s linear infinite;">refresh</span>
            <p style="margin-top: 16px; color: #6b7280;">Loading freelancer details...</p>
        </div>
    `;
    
    // Fetch freelancer details
    fetch(`/admin/freelancer/${freelancerId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Restore original content first
                modalBody.innerHTML = originalContent;
                // Then populate with data
                populateModal(data.freelancer);
            } else {
                alert('Failed to load freelancer details');
                closeReviewModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading freelancer details');
            closeReviewModal();
        });
}

// Populate modal with freelancer data
function populateModal(freelancer) {
    // Profile picture
    const profilePic = document.getElementById('modalProfilePicture');
    if (profilePic) {
        profilePic.src = freelancer.profile_picture ? 
            `/storage/${freelancer.profile_picture}` : '/defaultprofile.jpg';
    }
    
    // Basic information
    const fullNameEl = document.getElementById('modalFullName');
    if (fullNameEl) {
        fullNameEl.textContent = `${freelancer.firstname} ${freelancer.lastname}`;
    }
    
    const emailEl = document.getElementById('modalEmail');
    if (emailEl) {
        emailEl.textContent = freelancer.email || 'N/A';
    }
    
    // Contact information
    const contactEl = document.getElementById('modalContactNumber');
    if (contactEl) {
        contactEl.textContent = freelancer.contact_number || 'N/A';
    }
    
    // Location information
    const provinceEl = document.getElementById('modalProvince');
    if (provinceEl) {
        provinceEl.textContent = freelancer.province || 'N/A';
    }
    
    const cityEl = document.getElementById('modalCity');
    if (cityEl) {
        cityEl.textContent = freelancer.city || 'N/A';
    }
    
    const barangayEl = document.getElementById('modalBarangay');
    if (barangayEl) {
        barangayEl.textContent = freelancer.barangay || 'N/A';
    }
    
    // Professional information
    const experienceEl = document.getElementById('modalExperienceYears');
    if (experienceEl) {
        experienceEl.textContent = freelancer.experience_years ? 
            `${freelancer.experience_years} years` : 'N/A';
    }
    
    const specializationEl = document.getElementById('modalSpecialization');
    if (specializationEl) {
        specializationEl.textContent = freelancer.specialization || 'N/A';
    }
    
    // Categories
    const categoriesEl = document.getElementById('modalCategories');
    if (categoriesEl) {
        if (freelancer.categories && freelancer.categories.length > 0) {
            categoriesEl.textContent = freelancer.categories.map(cat => cat.name).join(', ');
        } else {
            categoriesEl.textContent = 'No categories assigned';
        }
    }
    
    // ID Images
    const idFrontEl = document.getElementById('modalIdFront');
    if (idFrontEl) {
        idFrontEl.src = freelancer.id_front ? 
            `/storage/${freelancer.id_front}` : '/placeholder-id.png';
    }
    
    const idBackEl = document.getElementById('modalIdBack');
    if (idBackEl) {
        idBackEl.src = freelancer.id_back ? 
            `/storage/${freelancer.id_back}` : '/placeholder-id.png';
    }
    
    // Update verification badge
    const verificationIcon = document.getElementById('verificationIcon');
    const verificationText = document.getElementById('verificationText');
    const verificationBadge = document.getElementById('verificationBadge');
    
    if (verificationIcon && verificationText && verificationBadge) {
        if (freelancer.is_verified) {
            verificationIcon.textContent = 'check_circle';
            verificationText.textContent = 'Verified';
            verificationBadge.classList.add('verified');
            verificationBadge.style.background = '#10b981';
        } else {
            verificationIcon.textContent = 'schedule';
            verificationText.textContent = 'Pending';
            verificationBadge.classList.remove('verified');
            verificationBadge.style.background = '#f59e0b';
        }
    }
}

// Close review modal
function closeReviewModal() {
    document.getElementById('freelancerReviewModal').style.display = 'none';
    currentFreelancerId = null;
}

// Enlarge image function
function enlargeImage(imgElement) {
    const overlay = document.getElementById('imageEnlargeOverlay');
    const enlargedImg = document.getElementById('enlargedImage');
    if (overlay && enlargedImg) {
        enlargedImg.src = imgElement.src;
        overlay.style.display = 'block';
    }
}

// Close enlarged image
function closeEnlargedImage() {
    const overlay = document.getElementById('imageEnlargeOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Accept from modal
function acceptFromModal() {
    if (!currentFreelancerId) return;
    
    if (confirm('Are you sure you want to approve this freelancer? This action will grant them access to the platform.')) {
        // Show loading state on button
        const approveBtn = document.querySelector('.simple-btn-approve');
        if (approveBtn) {
            const originalHTML = approveBtn.innerHTML;
            approveBtn.innerHTML = '<span class="material-symbols-outlined">refresh</span> Processing...';
            approveBtn.disabled = true;
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/freelancer/${currentFreelancerId}/verify`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('input[name="_token"]').value;
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Decline from modal
function declineFromModal() {
    if (!currentFreelancerId) return;
    
    if (confirm('Are you sure you want to decline this freelancer application? This action cannot be undone.')) {
        // Show loading state on button
        const declineBtn = document.querySelector('.simple-btn-decline');
        if (declineBtn) {
            const originalHTML = declineBtn.innerHTML;
            declineBtn.innerHTML = '<span class="material-symbols-outlined">refresh</span> Processing...';
            declineBtn.disabled = true;
            
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/freelancer/${currentFreelancerId}/reject`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('input[name="_token"]').value;
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Event listeners for modal interactions
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const reviewModal = document.getElementById('freelancerReviewModal');
        const imageOverlay = document.getElementById('imageEnlargeOverlay');
        
        if (event.target === reviewModal) {
            closeReviewModal();
        }
        if (event.target === imageOverlay) {
            closeEnlargedImage();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const imageOverlay = document.getElementById('imageEnlargeOverlay');
            const reviewModal = document.getElementById('freelancerReviewModal');
            
            if (imageOverlay && imageOverlay.style.display === 'block') {
                closeEnlargedImage();
            } else if (reviewModal && reviewModal.style.display === 'block') {
                closeReviewModal();
            }
        }
    });
});

// Add CSS for loading animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

  </script>
