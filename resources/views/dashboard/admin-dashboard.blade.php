
@include("admin.header")
@include("admin.homeSection")
@include('admin.sidebar&Header')
@include('admin.dashboard-admin')  

@include('admin.categorySection') 

        
       

    
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
   
   
    <script>
//   document.addEventListener("DOMContentLoaded", function () {
//     const sections = {
//         dashboard: document.getElementById("dashboard"),
//         users: document.getElementById("users"),
//         category: document.getElementById("category"),
//         posts: document.getElementById("posts") 
//     };

//     const links = {
//         dashboard: document.getElementById("dashboard-link"),
//         users: document.getElementById("users-link"),
//         category: document.getElementById("categories-link"),
//         posts: document.getElementById("posts-link")
//     };

//     const logoutLink = document.getElementById("logout-link"); // Assuming there's a logout button or link

//     // Function to show a specific section
//     function showSection(sectionKey) {
//         Object.values(sections).forEach((section) => (section.style.display = "none"));
//         if (sections[sectionKey]) {
//             sections[sectionKey].style.display = "block";
//         }
//     }

//     // Initially hide all sections
//     Object.values(sections).forEach((section) => (section.style.display = "none"));

//     // Clear the last active section on logout
//     if (logoutLink) {
//         logoutLink.addEventListener("click", function () {
//             localStorage.removeItem("adminActiveSection"); // Clear saved section
//         });
//     }

//     // Determine the section to show
//     let lastActiveSection = localStorage.getItem("adminActiveSection");

//     // If no section is saved or login is detected, default to "dashboard"
//     if (!lastActiveSection || window.location.href.includes("login=true")) {
//         lastActiveSection = "dashboard";
//         localStorage.setItem("adminActiveSection", "dashboard"); // Set default section
//     }

//     // Show the determined section
//     showSection(lastActiveSection);

//     // Add click event listeners to navigation links
//     Object.keys(links).forEach((key) => {
//         links[key].addEventListener("click", function (event) {
//             event.preventDefault();
//             localStorage.setItem("adminActiveSection", key); // Save active section
//             showSection(key);
//         });
//     });

//     // Listen for form submissions to save the active section
//     const forms = document.querySelectorAll("form");
//     forms.forEach((form) => {
//         form.addEventListener("submit", function () {
//             const currentSection = Object.keys(sections).find(
//                 (key) => sections[key].style.display === "block"
//             );
//             if (currentSection) {
//                 localStorage.setItem("adminActiveSection", currentSection); // Save current section before submission
//             }
//         });
//     });
// });

//         // success message time duration
//         document.addEventListener('DOMContentLoaded', function () {
//     const alert = document.querySelector('.alert-success');
//     if (alert) {
//         setTimeout(() => {
//             alert.remove();
//         }, 3000); // 3 seconds
//     }

    
// });

//         //*************************** */ modal scirpt******************
// document.addEventListener("DOMContentLoaded", function () {
//     const createCategoryBtn = document.getElementById("createCategoryBtn");
//     const categoryModal = document.getElementById("categoryModal");
//     const closeModal = document.querySelector(".close-modal");
  


//     // Show the modal
//     createCategoryBtn.addEventListener("click", function () {
//         categoryModal.style.display = "flex";
       
//     });

//     // Hide the modal
//     closeModal.addEventListener("click", function () {
//         categoryModal.style.display = "none";
//     });

//     // Hide modal on clicking outside the modal content
//     window.addEventListener("click", function (event) {
//         if (event.target === categoryModal) {
//             categoryModal.style.display = "none";
//         }
//     });
//     const hasErrors = document.querySelector(".error-message"); // Checks if there are error messages
//     if (hasErrors) {
//         categoryModal.style.display = "flex"; // Keep modal open
//     }
// });

// document.querySelectorAll('.edit-category-btn').forEach(button => {
//     button.addEventListener('click', function () {
//         const categoryId = this.getAttribute('data-id');
//         fetch(`/categories/${categoryId}/edit`)
//             .then(response => response.json())
//             .then(data => {
//                 document.getElementById('editCategoryId').value = data.id;
//                 document.getElementById('editCategoryName').value = data.name;
//                 document.getElementById('editCategoryForm').action = `/categories/${data.id}/update`;
//                 document.getElementById('editCategoryModal').style.display = 'flex';
//             });
//     });

// });
//         document.querySelector('.close-btn').addEventListener('click', function () {
//             document.getElementById('editCategoryModal').style.display = 'none';
//         });

//         // Cancel Button
//         document.querySelector('.cancel-btn').addEventListener('click', function () {
//             document.getElementById('editCategoryModal').style.display = 'none';
//         });

// Sidebar links and sections toggle
const links = document.querySelectorAll('.sidebar-links li a');
const sections = document.querySelectorAll('main > div');

links.forEach(link => {
  link.addEventListener('click', function (event) {
    event.preventDefault();

    links.forEach(link => link.classList.remove('active'));
    this.classList.add('active');

    sections.forEach(section => section.style.display = 'none');

    const targetSelector = this.getAttribute('href');
    if (targetSelector && targetSelector !== "#") {
      const targetSection = document.querySelector(targetSelector);
      if (targetSection) {
        targetSection.style.display = 'block';
        // Save active section to localStorage
        localStorage.setItem('activeSection', targetSelector);
      }
    }
  });
});

// Home Link
document.getElementById('homeLink').addEventListener('click', function () {
  setActiveSection('#homeSection');
});

// Dashboard Link
document.getElementById('dashboardLink').addEventListener('click', function () {
  setActiveSection('#dashboardSection');
  toggleDashboardCards('totalFreelancers');
});

// Card Click Events
document.getElementById('totalFreelancersCard').addEventListener('click', () => toggleDashboardCards('totalFreelancers'));
document.getElementById('totalClientsCard').addEventListener('click', () => toggleDashboardCards('totalClients'));
document.getElementById('pendingAccountsCard').addEventListener('click', () => toggleDashboardCards('pendingAccounts'));
document.getElementById('pendingPostsCard').addEventListener('click', () => toggleDashboardCards('pendingPosts'));


// Categories Link
document.getElementById('categoriesLink').addEventListener('click', function () {
  setActiveSection('#categoriesSection');
});

// Reusable function to activate a section
function setActiveSection(selector) {
  sections.forEach(section => section.style.display = 'none');
  links.forEach(link => link.classList.remove('active'));

  const targetSection = document.querySelector(selector);
  if (targetSection) {
    targetSection.style.display = 'block';
    const correspondingLink = document.querySelector(`a[href="${selector}"]`);
    if (correspondingLink) correspondingLink.classList.add('active');
    localStorage.setItem('activeSection', selector);
  }
}

// Reusable function to toggle dashboard cards
function toggleDashboardCards(activeCard) {
  const cards = ['totalFreelancers', 'totalClients', 'pendingAccounts', 'pendingPosts'];

  cards.forEach(card => {
    const cardElement = document.getElementById(`${card}Card`);
    const sectionElement = document.getElementById(`${card}Section`);
    if (card === activeCard) {
      cardElement.classList.add('active');
      sectionElement.style.display = 'block';
    } else {
      cardElement.classList.remove('active');
      sectionElement.style.display = 'none';
    }
  });

  localStorage.setItem('activeSection', '#dashboardSection');
}

// On Page Load: Restore active section
window.addEventListener('DOMContentLoaded', () => {
  const savedSection = localStorage.getItem('activeSection') || '#homeSection';
  setActiveSection(savedSection);
});

// Success message hide after 3 seconds
document.addEventListener('DOMContentLoaded', function () {
  const alert = document.querySelector('.alert-success');
  if (alert) {
    setTimeout(() => {
      alert.remove();
    }, 3000);
  }
});

// Add Category Modal Handling
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("categoryModal");
  const addCategoryBtn = document.getElementById("addCategoryBtn");
  const closeModal = document.querySelector(".close");

  addCategoryBtn.addEventListener("click", function () {
    modal.style.display = "flex";
  });

  closeModal.addEventListener("click", function () {
    modal.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
});

// Edit Category Modal Handling
const editCategoryBtns = document.querySelectorAll('.edit-category-btn');
const editCategoryModal = document.getElementById('editCategoryModal');
const editCategoryIdField = document.getElementById('editCategoryId');
const editCategoryNameField = document.getElementById('editCategoryName');
const updateCategoryBtn = document.getElementById('updateCategory');
const cancelEditCategoryBtn = document.getElementById('cancelEditCategory');

// Open edit modal
document.querySelector("#categoriesSection table tbody").addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("edit-category-btn")) {
        const categoryId = e.target.getAttribute("data-id");
        const categoryRow = e.target.closest('tr');
        const categoryName = categoryRow.querySelector('td:nth-child(2)').textContent.trim();

        // Set values in your modal form fields
        editCategoryIdField.value = categoryId;
        editCategoryNameField.value = categoryName;

        // Show the modal
        editCategoryModal.style.display = 'flex';
    }
});
// Close edit modal
cancelEditCategoryBtn.addEventListener('click', (e) => {
  e.preventDefault();
  editCategoryModal.style.display = 'none';
});

// Close edit modal when clicking outside
window.addEventListener('click', (e) => {
  if (e.target === editCategoryModal) {
    editCategoryModal.style.display = 'none';
  }
});



   
       
    </script>
    </body>
</html>





















