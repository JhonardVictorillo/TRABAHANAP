@php
function getStatusColor($status) {
    return match (strtolower($status)) {
        'pending'            => '#fbbf24', // Yellow
        'accepted'           => '#2563eb', // Blue
        'completed'          => '#10b981', // Green
        'canceled'           => '#ef4444', // Red
        'declined'           => '#6b7280', // Gray
        'no_show_freelancer' => '#eab308', // Amber/Orange
        'no_show_customer'   => '#a21caf', // Purple
        default              => '#6b7280', // Default Gray
    };
}
@endphp

@include("admin.header")
@include("admin.homeSection")
@include('admin.sidebar&Header')
@include('admin.dashboard-admin')
@include('admin.booking')  
@include('admin.violations')
@include('admin.userstats')
@include('admin.categorySection') 

        
       

    
  <!-- Success message -->
  @if(session('success'))
        <div class="alert alert-success">
        <i class='bx bx-check-circle'></i> <!-- Success icon -->
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

//search functionality
document.querySelectorAll('input[name="search"]').forEach(function(input) {
    input.addEventListener('input', function() {
        if (this.value === '') {
            this.form.submit();
        }
    });
});
   
       
    </script>
    </body>
</html>





















