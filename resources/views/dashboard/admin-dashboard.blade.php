

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head> 
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>trabahanap.</h2>
        </div>
        <ul class="sidebar-links">
            <h4><span>Main Menu</span></h4>
            <li>
                <a href=""  id="dashboard-link"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
            </li>
            <li>
                <a href=""  id="users-link"><span class="material-symbols-outlined">group</span>Users</a>
            </li>
            <li>
                <a href="" id="categories-link"><span class="material-symbols-outlined">category</span>Categories</a>
            </li>
           
            <h4><span>Account</span></h4>
            <li>
                <a href="#" ><span class="material-symbols-outlined">account_circle</span>Profile</a>
            </li>
            <li>
                <a href="#" ><span class="material-symbols-outlined">settings</span>Settings</a>
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
        <div class="user-account">
            <div class="user-profile">
                <div class="user-detail">
                    <h3> Hi,  {{ Auth::user()->firstname }}</h3>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>
    </aside>

    <main class="main-content">
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
        
        
        <div id="users" class="section" style="display: none;"> <!-- Initially hidden -->
        <h2>All Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($users) && $users->count() > 0)
                @foreach($users as $user) <!-- Loop through users -->
                    <tr>
                        <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <button class="verify-btn">Verify</button>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div id="category" class="section" style="display: none;"> <!-- Initially hidden -->
        <h2>All Category</h2>
        <button id="createCategoryBtn" class="create-btn">+ Create Category</button>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CATEGORY NAME</th>
                    <th>CREATED</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
           
            @if($categories->count() > 0)
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->created_at->format('Y-m-d H:i') }}</td> <!-- Format created date -->
                <td>
                    <button class="verify-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4">No categories found.</td>
        </tr>
    @endif
            
            </tbody>
        </table>
    </div>


    @if(session('success'))
     <div class="alert alert-success">
       {{ session('success') }}
     </div>
        @endif


        <!-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif -->
   
    </main>

        <!-- Modal -->
    <div id="categoryModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Create New Category</h3>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" id="categoryName"  name="name" value="{{ old('name') }}"  class="{{ $errors->has('name') ? 'input-error' : '' }}" required>
                    @error('name')
                     <div class="error-message" style="color: red; margin-top: 5px;">{{ $message }}</div>
                  @enderror
                </div>
                <button type="submit" class="verify-btn">Create</button>
            </form>
        </div>
    </div>
</div>





    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dashboardLink = document.getElementById('dashboard-link');
            const usersLink = document.getElementById('users-link');
            const categoryLink = document.getElementById('categories-link')
            const dashboardSection = document.getElementById('dashboard');
            const usersSection = document.getElementById('users');
            const categorySection = document.getElementById('category');



             // Set the default view to the dashboard
          dashboardSection.style.display = 'block';
         usersSection.style.display = 'none';
        categorySection.style.display = 'none';


            // Show the category section if it's in the URL (or after category creation)
    if (window.location.href.includes('category=true') || {{ session('success') ? 'true' : 'false' }}) {
        dashboardSection.style.display = 'none';
        usersSection.style.display = 'none';
        categorySection.style.display = 'block';
    } else if ({!! $errors->any() ? 'true' : 'false' !!}) {
        // Show category section if there are validation errors
        dashboardSection.style.display = 'none';
        usersSection.style.display = 'none';
        categorySection.style.display = 'block';
    }
            // Toggle to Users section when "Users" is clicked
            usersLink.addEventListener('click', function(event) {
                event.preventDefault();
                dashboardSection.style.display = 'none';
                usersSection.style.display = 'block';
                categorySection.style.display = 'none';

            });

            // Toggle back to Dashboard section when "Dashboard" is clicked
            dashboardLink.addEventListener('click', function(event) {
                event.preventDefault();
                usersSection.style.display = 'none';
                categorySection.style.display = 'none';
                dashboardSection.style.display = 'block';
            });
            categoryLink.addEventListener('click', function(event) {
                event.preventDefault();
                usersSection.style.display = 'none';
                dashboardSection.style.display = 'none';
                categorySection.style.display = 'block';

            });
        });
       
        // success message time duration
        document.addEventListener('DOMContentLoaded', function () {
    const alert = document.querySelector('.alert-success');
    if (alert) {
        setTimeout(() => {
            alert.remove();
        }, 3000); // 3 seconds
    }
});

        //*************************** */ modal scirpt******************
document.addEventListener("DOMContentLoaded", function () {
    const createCategoryBtn = document.getElementById("createCategoryBtn");
    const categoryModal = document.getElementById("categoryModal");
    const closeModal = document.querySelector(".close-modal");
  


    // Show the modal
    createCategoryBtn.addEventListener("click", function () {
        categoryModal.style.display = "flex";
       
    });

    // Hide the modal
    closeModal.addEventListener("click", function () {
        categoryModal.style.display = "none";
    });

    // Hide modal on clicking outside the modal content
    window.addEventListener("click", function (event) {
        if (event.target === categoryModal) {
            categoryModal.style.display = "none";
        }
    });
    const hasErrors = document.querySelector(".error-message"); // Checks if there are error messages
    if (hasErrors) {
        categoryModal.style.display = "flex"; // Keep modal open
    }
});


    </script>
    </body>
</html>





















