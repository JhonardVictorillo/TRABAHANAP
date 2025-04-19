<!-- <main class="main-content">
    <div id="dashboard" class="content-section"> 
        <div class="content-header">
           <h2>Dashboard</h2>
        </div>
        <div class="cards">
            <div class="card">
                <span class="material-symbols-outlined">people</span> 
                <h3>Total Freelancers</h3>
                <p>{{ $totalFreelancers }}</p>
            </div>
            <div class="card">
                <span class="material-symbols-outlined">groups</span> 
                <h3>Total Clients</h3>
                <p>{{ $totalCustomers }}</p>
            </div>
            <div class="card">
                <span class="material-symbols-outlined">pending</span> 
                <h3>Pending Accounts</h3>
                <p>{{ $pendingAccounts ?? 0 }}</p>
            </div>
        </div>
        </div>
        </div>
         -->

         <!-- Dashboard Section -->
    
  
<div class="dashboard-section" id="dashboardSection" style="display: none;">
    
    <div class="content-header">
      <h2>Dashboard</h2>
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
              <p>30</p>
              </div>
          </div>
          <div class="card4" id="pendingPostsCard">
              <span class="material-symbols-outlined">post_add</span>
              <div class="stats-details">
              <h3>Pending Posts</h3>
              <p>5</p>
              </div>     
      </div>     
   
    </section>

    <!-- Total Freelancers Section -->
    <div class="details-section" id="totalFreelancersSection" style="display: none;">
      <h2>Total Freelancers</h2>
      <div class="table-container">
      <table>
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
                 <img src="{{ asset('storage/' . $freelancer->profile_picture) ?? asset('defaultprofile.jpg') }}" 
                             alt="Profile Picture" 
                             class="profile-pic"  >
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
            <td> {{ ucfirst($freelancer->status) ?? 'Inactive' }}</td>
          </tr>
          @empty
                <tr>
                    <td colspan="5">No freelancers found.</td>
                </tr>
            @endforelse
          
        </tbody>
      </table>
      </div>
    </div>

    <!-- Total Clients Section -->
    <div class="details-section" id="totalClientsSection" style="display: none;">
      <h2>Total Clients</h2>
      <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        @foreach($customer as $client)
          <tr>
            <td>  @if($client->profile_picture)
          <img src="{{ asset('storage/' . $client->profile_picture) }}" alt="Profile Picture" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
        @else
          <img src="{{ asset('images/defaultprofile.png') }}" alt="Default Profile" class="profile-pic" style="width:50px; height:50px; object-fit:cover;">
        @endif</td>
            <td>{{ $client->firstname }} {{ $client->lastname }}</td>
            <td>{{ $client->email }}</td>
            <td> 
               @if($client->status === 'active')
              <span style="color:green;">Active</span>
            @else
              <span style="color:red;">Inactive</span>
            @endif
         </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </div>

    <!-- Pending Accounts Section -->
    <div class="details-section" id="pendingAccountsSection" style="display: none;">
      <h2>Pending Accounts</h2>
      <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @foreach($freelancers as $freelancer)
                 
            <tr>
                <td><img src="{{ asset('storage/' . $freelancer->profile_picture) }}" alt="Profile Picture" class="profile-pic"></td>
                <td>{{ $freelancer->firstname }} {{ $freelancer->lastname }}</td>
                <td>{{ $freelancer->email }}</td>
                <td>
                  @if($freelancer->is_verified)
                ✅ Verified
                @else
                🕒 Pending
              @endif
              </td>
                <td>
                @if(!$freelancer->is_verified)
                    <!-- Accept Button -->
                    <form action="{{ route('admin.verifyFreelancer', $freelancer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="accept-btn">Accept</button>
                    </form>

                    <!-- Decline Button -->
                    <form action="{{ route('admin.rejectFreelancer', $freelancer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="decline-btn">Decline</button>
                    </form>
                    @else
                <!-- Optional: Display additional action for verified users if needed -->
                <button class="verified-btn">Verified</button>
              @endif
                </td>
            </tr>
                  
                @endforeach
        </tbody>
      </table>
     </div>
    </div>

    <!-- Pending Posts Section -->
    <div class="details-section" id="pendingPostsSection" style="display: none;">
    <h2>Pending Posts</h2>
    <div class="table-container">
        <table>
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
                  <button class="approve-btn" data-id="{{ $post->id }}">Approve</button>
                  <button class="reject-btn" data-id="{{ $post->id }}">Reject</button>
              @else
                  <span>Approved</span>
              @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
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

        fetch(`/admin/posts/${postId}/approve`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
        })
        .then(response => response.json())
        .then(data => {
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

        fetch(`/admin/posts/${postId}/reject`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
        })
        .then(response => response.json())
        .then(data => {
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

  </script>
