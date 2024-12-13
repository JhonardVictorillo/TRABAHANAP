

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
                <a href="#"   id="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                <button class="verify-btn edit-category-btn" data-id="{{ $category->id }}">Edit</button>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
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

    <div id="editCategoryModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Edit Category</h3>
        <form id="editCategoryForm" method="POST">
            @csrf
            <input type="hidden" name="id" id="editCategoryId">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" name="name" id="editCategoryName" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="save-btn">Update</button>
            </div>
        </form>
    </div>
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
  document.addEventListener("DOMContentLoaded", function () {
    const sections = {
        dashboard: document.getElementById("dashboard"),
        users: document.getElementById("users"),
        category: document.getElementById("category")
    };

    const links = {
        dashboard: document.getElementById("dashboard-link"),
        users: document.getElementById("users-link"),
        category: document.getElementById("categories-link")
    };

    const logoutLink = document.getElementById("logout-link"); // Assuming there's a logout button or link

    // Function to show a specific section
    function showSection(sectionKey) {
        Object.values(sections).forEach((section) => (section.style.display = "none"));
        if (sections[sectionKey]) {
            sections[sectionKey].style.display = "block";
        }
    }

    // Initially hide all sections
    Object.values(sections).forEach((section) => (section.style.display = "none"));

    // Clear the last active section on logout
    if (logoutLink) {
        logoutLink.addEventListener("click", function () {
            localStorage.removeItem("adminActiveSection"); // Clear saved section
        });
    }

    // Determine the section to show
    let lastActiveSection = localStorage.getItem("adminActiveSection");

    // If no section is saved or login is detected, default to "dashboard"
    if (!lastActiveSection || window.location.href.includes("login=true")) {
        lastActiveSection = "dashboard";
        localStorage.setItem("adminActiveSection", "dashboard"); // Set default section
    }

    // Show the determined section
    showSection(lastActiveSection);

    // Add click event listeners to navigation links
    Object.keys(links).forEach((key) => {
        links[key].addEventListener("click", function (event) {
            event.preventDefault();
            localStorage.setItem("adminActiveSection", key); // Save active section
            showSection(key);
        });
    });

    // Listen for form submissions to save the active section
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener("submit", function () {
            const currentSection = Object.keys(sections).find(
                (key) => sections[key].style.display === "block"
            );
            if (currentSection) {
                localStorage.setItem("adminActiveSection", currentSection); // Save current section before submission
            }
        });
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

document.querySelectorAll('.edit-category-btn').forEach(button => {
    button.addEventListener('click', function () {
        const categoryId = this.getAttribute('data-id');
        fetch(`/categories/${categoryId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editCategoryId').value = data.id;
                document.getElementById('editCategoryName').value = data.name;
                document.getElementById('editCategoryForm').action = `/categories/${data.id}/update`;
                document.getElementById('editCategoryModal').style.display = 'flex';
            });
    });

});
        document.querySelector('.close-btn').addEventListener('click', function () {
            document.getElementById('editCategoryModal').style.display = 'none';
        });

        // Cancel Button
        document.querySelector('.cancel-btn').addEventListener('click', function () {
            document.getElementById('editCategoryModal').style.display = 'none';
        });


    </script>
    </body>
</html>





















