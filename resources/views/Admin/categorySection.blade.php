<div class="details-section" id="categoriesSection" style="display: none;">
<div class="category-management-card">
    <div class="category-card-header">
        <h3 class="category-card-title">Category Management</h3>
        <button class="category-add-btn" id="addCategoryBtn">
            <i class="fas fa-plus-circle"></i> Add New Category
        </button>
    </div>
    
    <div class="category-card-body">
        <!-- Tab Navigation -->
        <div class="category-tab-navigation" id="categoryTabs">
            <button class="category-tab-btn active" onclick="showCategoryTab('categories')">Categories</button>
            <button class="category-tab-btn" onclick="showCategoryTab('requests')">
                Category Requests
                @php 
                    $pendingCount = \App\Models\CategoryRequest::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="category-badge category-badge-warning">{{ $pendingCount }}</span>
                @endif
            </button>
        </div>
        
        <!-- Tab Content -->
        <div class="category-tab-content">
            <!-- Categories Tab -->
            <div id="categoriesTab" class="category-tab-pane active">
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Freelancers</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->users_count ?? 0 }}</td>
                                    <td>{{ $category->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="category-action-buttons">
                                           <button class="edit-category" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="delete-btn" data-id="{{ $category->id }}">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="category-empty-state">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Categories Pagination -->
                <div class="category-pagination-container">
                    @if($categories->previousPageUrl())
                        <a href="{{ $categories->previousPageUrl() }}" class="category-pagination-btn">
                            <i class="fas fa-arrow-left"></i> Previous
                        </a>
                    @else
                        <button class="category-pagination-btn category-btn-disabled" disabled>
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                    @endif
                    
                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}" class="category-pagination-btn">
                            Next <i class="fas fa-arrow-right"></i>
                        </a>
                    @else
                        <button class="category-pagination-btn category-btn-disabled" disabled>
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Category Requests Tab -->
            <div id="requestsTab" class="category-tab-pane">
                <!-- Filter Buttons -->
                <div class="category-filter-container">
                    <div class="category-filter-group">
                        <a href="?tab=requests" class="category-filter-btn {{ !request('status') ? 'active' : '' }}">
                            All <span class="category-count">{{ $categoryRequestsCount ?? 0 }}</span>
                        </a>
                        <a href="?tab=requests&status=pending" class="category-filter-btn {{ request('status') == 'pending' ? 'active warning' : '' }}">
                            Pending <span class="category-count">{{ $pendingCount ?? 0 }}</span>
                        </a>
                        <a href="?tab=requests&status=approved" class="category-filter-btn {{ request('status') == 'approved' ? 'active success' : '' }}">
                            Approved <span class="category-count">{{ $approvedCount ?? 0 }}</span>
                        </a>
                        <a href="?tab=requests&status=declined" class="category-filter-btn {{ request('status') == 'declined' ? 'active danger' : '' }}">
                            Declined <span class="category-count">{{ $declinedCount ?? 0 }}</span>
                        </a>
                    </div>
                </div>
                
                <!-- Category Requests Table -->
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Freelancer</th>
                                <th>Requested Category</th>
                                <th>Status</th>
                                <th>Date Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryRequests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>{{ $request->user->firstname }} {{ $request->user->lastname }}</td>
                                    <td>{{ $request->category_name }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="category-status-badge category-status-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="category-status-badge category-status-success">Approved</span>
                                        @else
                                            <span class="category-status-badge category-status-danger">Declined</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="category-action-buttons">
                                             <button class="category-action-btn category-info-btn view-request" 
                                                    data-id="{{ $request->id }}" 
                                                    data-user="{{ $request->user->name }}"
                                                    data-name="{{ $request->category_name }}"
                                                    data-date="{{ $request->created_at->format('M d, Y') }}"
                                                    title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if($request->status == 'pending')
                                                <button class="category-action-btn category-success-btn approve-request" 
                                                        data-id="{{ $request->id }}" 
                                                        data-name="{{ $request->category_name }}"
                                                        title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="category-action-btn category-danger-btn decline-request" 
                                                        data-id="{{ $request->id }}"
                                                        title="Decline">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="category-empty-state">No category requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Requests Pagination -->
                <div class="category-pagination-container">
                    @if(method_exists($categoryRequests, 'previousPageUrl') && $categoryRequests->previousPageUrl())
                        <a href="{{ $categoryRequests->previousPageUrl() }}&tab=requests{{ request('status') ? '&status='.request('status') : '' }}" class="category-pagination-btn">
                            <i class="fas fa-arrow-left"></i> Previous
                        </a>
                    @else
                        <button class="category-pagination-btn category-btn-disabled" disabled>
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                    @endif
                    
                    @if(method_exists($categoryRequests, 'hasMorePages') && $categoryRequests->hasMorePages())
                        <a href="{{ $categoryRequests->nextPageUrl() }}&tab=requests{{ request('status') ? '&status='.request('status') : '' }}" class="category-pagination-btn">
                            Next <i class="fas fa-arrow-right"></i>
                        </a>
                    @else
                        <button class="category-pagination-btn category-btn-disabled" disabled>
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Categories Section -->
<!-- <div class="details-section" id="categoriesSection" style="display: none;">
<h2>
    <span class="material-symbols-outlined align-middle">category</span>
    Categories
  </h2>
      <button id="addCategoryBtn">Add Category</button>
      <table>

        <thead>
          <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        @if($categories->count() > 0)
        @foreach($categories as $category)
        <tr data-id="{{ $category->id }}">
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
            <td>
            <button class="verify-btn edit-category-btn" data-id="{{ $category->id }}">Edit</button>
            <button type="button" class="delete-btn" data-id="{{ $category->id }}">Delete</button>
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
    -->
    
  </main>

  
<!-- Modal Structure -->
<div id="categoryModal" class="modal">
  <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Add Category</h2>
      <form action="{{ route('categories.store') }}" method="POST">
      @csrf
      <label for="categoryName">Category Name:</label>
      <input type="text" id="categoryName"  name="name" value="{{ old('name') }}"  class="{{ $errors->has('name') ? 'input-error' : '' }}" required>
        @error('name')
        <div class="error-message" style="color: red; margin-top: 5px;">{{ $message }}</div>
    @enderror
     
      <button id="saveCategory">Add</button>
  </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal">
  <div class="modal-content">
      <h2>Edit Category</h2>
      <form id="editCategoryForm" method="POST">
            @csrf
      <input type="hidden" id="editCategoryId">
      <label for="name">Category Name:</label>
    <input type="text" id="editCategoryName" placeholder="Category Name">
      <div class="modal-buttons">
          <button id="updateCategory">Update</button>
          <button id="cancelEditCategory" class="cancel-btn">Cancel</button>
      </div>
      </form>
  </div>
</div>

<!-- View Request Modal -->
<div id="viewRequestModal" class="category-modal">
    <div class="category-modal-content">
        <div class="category-modal-header">
            <h4 class="category-modal-title">Category Request Details</h4>
            <span class="category-modal-close" id="viewRequestModalClose">&times;</span>
        </div>
        <div class="category-modal-body">
            <div class="category-request-details">
                <p><strong>Freelancer:</strong> <span id="request_freelancer"></span></p>
                <p><strong>Requested Category:</strong> <span id="request_category"></span></p>
                <p><strong>Date Requested:</strong> <span id="request_date"></span></p>
            </div>
            <div class="category-modal-footer">
                <button type="button" class="category-btn category-btn-secondary" id="viewRequestModalClose2">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve Request Modal -->
<div id="approveRequestModal" class="category-modal">
    <div class="category-modal-content">
        <div class="category-modal-header">
            <h4 class="category-modal-title">Approve Category Request</h4>
            <span class="category-modal-close" id="approveModalClose">&times;</span>
        </div>
        <div class="category-modal-body">
            <form id="approveRequestForm">
                <input type="hidden" id="approve_request_id" name="id">
                
                <div class="category-form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" id="category_name" name="category_name" class="category-form-control" required>
                    <div class="category-form-help">You can modify the category name if needed.</div>
                </div>
                
                <div class="category-form-group">
                    <label for="approve_admin_notes">Admin Notes (optional)</label>
                    <textarea id="approve_admin_notes" name="admin_notes" class="category-form-control" rows="3" placeholder="Add any notes about this approval"></textarea>
                </div>
                
                <div class="category-modal-footer">
                    <button type="button" class="category-btn category-btn-secondary" id="approveModalCancel">Cancel</button>
                    <button type="button" class="category-btn category-btn-success" id="approveModalSubmit">Approve Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Decline Request Modal -->
<div id="declineRequestModal" class="category-modal">
    <div class="category-modal-content">
        <div class="category-modal-header">
            <h4 class="category-modal-title">Decline Category Request</h4>
            <span class="category-modal-close" id="declineModalClose">&times;</span>
        </div>
        <div class="category-modal-body">
            <form id="declineRequestForm">
                <input type="hidden" id="decline_request_id" name="id">
                
                <div class="category-form-group">
                    <label for="decline_admin_notes">Reason for Declining</label>
                    <textarea id="decline_admin_notes" name="admin_notes" class="category-form-control" rows="3" required placeholder="Please provide a reason for declining this request"></textarea>
                    <div class="category-form-help">This will be shared with the freelancer who requested the category.</div>
                </div>
                
                <div class="category-modal-footer">
                    <button type="button" class="category-btn category-btn-secondary" id="declineModalCancel">Cancel</button>
                    <button type="button" class="category-btn category-btn-danger" id="declineModalSubmit">Decline Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // EDIT CATEGORY FUNCTIONALITY
    // 1. Make sure we find the edit buttons
    const editButtons = document.querySelectorAll(".edit-category");
    console.log("Found edit buttons:", editButtons.length);
    
    // 2. Add click handler to all edit buttons
    editButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            // Get the category data from data attributes
            const categoryId = this.getAttribute("data-id");
            const categoryName = this.getAttribute("data-name");
            
            console.log("Edit clicked for:", categoryId, categoryName);
            
            // Set the form values
            document.getElementById("editCategoryId").value = categoryId;
            document.getElementById("editCategoryName").value = categoryName;
            
            // Show the modal
            document.getElementById("editCategoryModal").style.display = "flex";
        });
    });
    
    // DELETE CATEGORY FUNCTIONALITY - Direct approach
    const deleteButtons = document.querySelectorAll(".delete-btn");
    console.log("Found delete buttons:", deleteButtons.length);
    
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            const categoryId = this.getAttribute("data-id");
            
            if (!confirm("Are you sure you want to delete this category?")) {
                return;
            }
            
            console.log("Deleting category:", categoryId);
            
            const csrfToken = document.querySelector('input[name="_token"]').value;
            
            fetch(`/categories/${categoryId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    
                    // Remove the row from the table
                    const row = this.closest("tr");
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert("Failed to delete category: " + (data.message || "Unknown error"));
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while deleting the category");
            });
        });
    });

    // Keep your existing code below this line
    // ADD CATEGORY AJAX
    const saveCategoryBtn = document.getElementById("saveCategory");
    saveCategoryBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const categoryName = document.getElementById("categoryName").value;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch("{{ route('categories.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ name: categoryName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                addCategoryRow(data.category); // update table
                document.getElementById("categoryModal").style.display = "none";
                document.getElementById("categoryName").value = "";
            } else {
                alert("Failed to add category.");
            }
        })
        .catch(error => console.error(error));
    });

    // UPDATE CATEGORY AJAX
    const updateCategoryBtn = document.getElementById("updateCategory");
    updateCategoryBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const categoryId = document.getElementById("editCategoryId").value;
        const categoryName = document.getElementById("editCategoryName").value;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(`/categories/${categoryId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ name: categoryName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                updateCategoryRow(data.category); // update row
                document.getElementById("editCategoryModal").style.display = "none";
            } else {
                alert("Failed to update category.");
            }
        })
        .catch(error => console.error(error));
    });

    // Helper function to update row after edit
    function updateCategoryRow(category) {
        // Find the row in the table
        const rows = document.querySelectorAll("#categoriesSection table tbody tr");
        for (let row of rows) {
            const rowId = row.querySelector("td:first-child").textContent;
            if (rowId == category.id) {
                // Update the name cell (second column)
                row.querySelector("td:nth-child(2)").textContent = category.name;
                break;
            }
        }
    }

    // Helper function to add new row
    function addCategoryRow(category) {
        const tbody = document.querySelector("#categoriesSection table tbody");
        const newRow = document.createElement("tr");
        
        newRow.innerHTML = `
            <td>${category.id}</td>
            <td>${category.name}</td>
            <td>0</td>
            <td>${new Date().toLocaleDateString()}</td>
            <td>
                <button class="edit-category" data-id="${category.id}" data-name="${category.name}">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="delete-btn" data-id="${category.id}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        
        // Add event listeners to the new buttons
        const newEditBtn = newRow.querySelector(".edit-category");
        newEditBtn.addEventListener("click", function() {
            document.getElementById("editCategoryId").value = this.getAttribute("data-id");
            document.getElementById("editCategoryName").value = this.getAttribute("data-name");
            document.getElementById("editCategoryModal").style.display = "block";
        });
        
        const newDeleteBtn = newRow.querySelector(".delete-btn");
        newDeleteBtn.addEventListener("click", function() {
            const categoryId = this.getAttribute("data-id");
            if (confirm("Are you sure you want to delete this category?")) {
                const csrfToken = document.querySelector('input[name="_token"]').value;
                
                fetch(`/categories/${categoryId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        this.closest("tr").remove();
                    } else {
                        alert("Failed to delete category.");
                    }
                })
                .catch(error => console.error(error));
            }
        });
    }

    // Close button for edit modal
    const closeEditModal = document.querySelector("#editCategoryModal .close");
    if (closeEditModal) {
        closeEditModal.addEventListener("click", function() {
            document.getElementById("editCategoryModal").style.display = "none";
        });
    }
    
    // Cancel button for edit modal
    const cancelEditBtn = document.getElementById("cancelEditCategory");
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("editCategoryModal").style.display = "none";
        });
    }
    
    // Close button for add modal
    const closeAddModal = document.querySelector("#categoryModal .close");
    if (closeAddModal) {
        closeAddModal.addEventListener("click", function() {
            document.getElementById("categoryModal").style.display = "none";
        });
    }
    
    // Close modals when clicking outside
    window.addEventListener("click", function(event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
        }
    });
});



// Check URL for tab state on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'requests') {
        showCategoryTab('requests');
    }
});

document.addEventListener("DOMContentLoaded", function() {
    console.log("Category request management script initialized");
    // ===== CATEGORY REQUESTS TAB FUNCTIONALITY =====

    // View Request Modal
    const viewRequestModal = document.getElementById('viewRequestModal');
    
    // Debug data attributes in buttons
    const viewButtons = document.querySelectorAll('.view-request');
    console.log(`Found ${viewButtons.length} view buttons`);
    viewButtons.forEach((btn, i) => {
        console.log(`Button ${i+1} data:`, {
            id: btn.getAttribute('data-id'),
            user: btn.getAttribute('data-user'),
            name: btn.getAttribute('data-name'),
            date: btn.getAttribute('data-date')
        })
    });
    // Add click event listener for view request buttons
     document.addEventListener('click', function(e) {
        if (e.target.closest('.view-request')) {
            const button = e.target.closest('.view-request');
            const requestId = button.getAttribute('data-id');
            
            console.log("View button clicked, id:", requestId);
            
            // Check if data attributes exist on the button
            let freelancerName = button.getAttribute('data-user');
            let categoryName = button.getAttribute('data-name');
            let dateRequested = button.getAttribute('data-date');
            
            console.log("Button data attributes:", { 
                user: freelancerName, 
                name: categoryName, 
                date: dateRequested 
            });
            
            // If data attributes are missing, get from table row
            if (!freelancerName || !categoryName || !dateRequested) {
                console.log("Some data attributes missing, trying to read from table row");
                
                const row = button.closest('tr');
                if (row) {
                    // Get data from table cells (adjust cell indexes as needed for your table)
                    if (!freelancerName) {
                        const userCell = row.querySelector('td:nth-child(2)');
                        if (userCell) freelancerName = userCell.textContent.trim();
                    }
                    
                    if (!categoryName) {
                        const categoryCell = row.querySelector('td:nth-child(3)');
                        if (categoryCell) categoryName = categoryCell.textContent.trim();
                    }
                    
                    if (!dateRequested) {
                        const dateCell = row.querySelector('td:nth-child(5)');
                        if (dateCell) dateRequested = dateCell.textContent.trim();
                    }
                    
                    console.log("Data from table row:", { 
                        user: freelancerName, 
                        name: categoryName, 
                        date: dateRequested 
                    });
                }
            }
            
            // Populate the modal with request details (with fallbacks)
            const freelancerElem = document.getElementById('request_freelancer');
            const categoryElem = document.getElementById('request_category');
            const dateElem = document.getElementById('request_date');
            
            if (freelancerElem) freelancerElem.textContent = freelancerName || 'Not available';
            if (categoryElem) categoryElem.textContent = categoryName || 'Not available';
            if (dateElem) dateElem.textContent = dateRequested || 'Not available';
            
            // Show the modal
            viewRequestModal.style.display = 'block';
        }
    });
    
    
    // Close view request modal
    const closeViewRequestModal = document.getElementById('viewRequestModalClose');
    if (closeViewRequestModal) {
        closeViewRequestModal.addEventListener('click', function() {
            viewRequestModal.style.display = 'none';
        });
    }
    
    // Close button in view modal footer
    const closeViewRequestButton = document.getElementById('viewRequestModalClose2');
    if (closeViewRequestButton) {
        closeViewRequestButton.addEventListener('click', function() {
            viewRequestModal.style.display = 'none';
        });
    }

    // Debug approve buttons
    const approveButtons = document.querySelectorAll('.approve-request');
    console.log(`Found ${approveButtons.length} approve buttons`);
    approveButtons.forEach((btn, i) => {
        console.log(`Approve button ${i+1} data:`, {
            id: btn.getAttribute('data-id'),
            name: btn.getAttribute('data-name')
        });
    });
    
    // Approve Request Modal
     document.addEventListener('click', function(e) {
        if (e.target.closest('.approve-request')) {
            const button = e.target.closest('.approve-request');
            const requestId = button.getAttribute('data-id');
            
            console.log("Approve button clicked, id:", requestId);
            
            // Get category name with fallback
            let categoryName = button.getAttribute('data-name');
            
            // If data attribute is missing, get from table row
            if (!categoryName) {
                console.log("Category name missing, trying to read from table row");
                
                const row = button.closest('tr');
                if (row) {
                    const categoryCell = row.querySelector('td:nth-child(3)'); // Adjust index as needed
                    if (categoryCell) categoryName = categoryCell.textContent.trim();
                    console.log("Category name from table:", categoryName);
                }
            }
            
            // Show approve modal
            const approveModal = document.getElementById('approveRequestModal');
            if (approveModal) {
                // Set the request ID in the form
                const requestIdField = document.getElementById('approve_request_id');
                if (requestIdField) {
                    requestIdField.value = requestId;
                }
                
                // Set the category name in the form
                const categoryNameField = document.getElementById('category_name');
                if (categoryNameField) {
                    categoryNameField.value = categoryName || '';
                    console.log("Set category name in form to:", categoryName);
                }
                
                // Show the modal
                approveModal.style.display = 'block';
            } else {
                console.error("Approve modal not found in the DOM");
                // Fall back to simple confirmation if modal doesn't exist
                if (confirm('Are you sure you want to approve this category request?')) {
                    approveRequest(requestId);
                }
            }
        }
    });
    
    // Decline Request Modal
    document.addEventListener('click', function(e) {
        if (e.target.closest('.decline-request')) {
            const button = e.target.closest('.decline-request');
            const requestId = button.getAttribute('data-id');
            
            // Show decline modal
            const declineModal = document.getElementById('declineRequestModal');
            if (declineModal) {
                // Set the request ID in the form
                document.getElementById('decline_request_id').value = requestId;
                
                // Show the modal
                declineModal.style.display = 'block';
            } else {
                // Fall back to simple confirmation if modal doesn't exist
                if (confirm('Are you sure you want to decline this category request?')) {
                    declineRequest(requestId);
                }
            }
        }
    });
    
    // Approve Modal Close Button
    const approveModalClose = document.getElementById('approveModalClose');
    if (approveModalClose) {
        approveModalClose.addEventListener('click', function() {
            document.getElementById('approveRequestModal').style.display = 'none';
        });
    }
    
    // Approve Modal Cancel Button
    const approveModalCancel = document.getElementById('approveModalCancel');
    if (approveModalCancel) {
        approveModalCancel.addEventListener('click', function() {
            document.getElementById('approveRequestModal').style.display = 'none';
        });
    }
    
 const approveModalSubmit = document.getElementById('approveModalSubmit');
if (approveModalSubmit) {
    approveModalSubmit.addEventListener('click', function() {
        const requestId = document.getElementById('approve_request_id').value;
        const notes = document.getElementById('approve_admin_notes').value || '';
        const categoryName = document.getElementById('category_name').value;
        
        if (!categoryName) {
            alert('Please provide a category name');
            return;
        }
        
        // Get CSRF token
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                        document.querySelector('input[name="_token"]')?.value;
                        
        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Security token missing. Please refresh the page and try again.');
            return;
        }
        
        console.log("Approving request:", requestId);
        console.log("Category name:", categoryName);
        console.log("Notes:", notes);
        
        // Show loading state
        approveModalSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        approveModalSubmit.disabled = true;
        
        // Create FormData for multipart form submission
        const formData = new FormData();
        formData.append('category_name', categoryName);
        formData.append('admin_notes', notes);
        formData.append('_token', csrfToken);
        
        // Send with proper error handling
        fetch(`/admin/category-requests/${requestId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'  // This marks it as an AJAX request
            },
            body: formData
        })
        .then(response => {
            console.log("Response status:", response.status);
            
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || `Error ${response.status}`);
                }).catch(e => {
                    // If parsing JSON fails, throw with status code
                    if (e instanceof SyntaxError) {
                        throw new Error(`Server returned ${response.status}`);
                    }
                    throw e;
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log("Response data:", data);
            
            if (data.success) {
                // Update the table
                updateRequestRow(requestId, 'approved');
                
                // Close the modal
                document.getElementById('approveRequestModal').style.display = 'none';
                
                // Show success message
                alert(data.message || 'Category request approved successfully');
                
                // Reset button
                approveModalSubmit.innerHTML = 'Approve Request';
                approveModalSubmit.disabled = false;
                
                // Reload page to ensure everything is updated
                window.location.reload();
            } else {
                // Reset button
                approveModalSubmit.innerHTML = 'Approve Request';
                approveModalSubmit.disabled = false;
                
                // Show error message
                alert(data.message || 'Failed to approve category request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset button
            approveModalSubmit.innerHTML = 'Approve Request';
            approveModalSubmit.disabled = false;
            
            alert('An error occurred while processing the request: ' + error.message);
        });
    });
}
    
    // Decline Modal Close Button
    const declineModalClose = document.getElementById('declineModalClose');
    if (declineModalClose) {
        declineModalClose.addEventListener('click', function() {
            document.getElementById('declineRequestModal').style.display = 'none';
        });
    }
    
    // Decline Modal Cancel Button
    const declineModalCancel = document.getElementById('declineModalCancel');
    if (declineModalCancel) {
        declineModalCancel.addEventListener('click', function() {
            document.getElementById('declineRequestModal').style.display = 'none';
        });
    }
    
    // Decline Modal Submit Button
    const declineModalSubmit = document.getElementById('declineModalSubmit');
if (declineModalSubmit) {
    declineModalSubmit.addEventListener('click', function() {
        const requestId = document.getElementById('decline_request_id').value;
        const notes = document.getElementById('decline_admin_notes').value;
        
        if (!notes) {
            alert('Please provide a reason for declining this request');
            return;
        }
        
        // Get CSRF token
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                        document.querySelector('input[name="_token"]')?.value;
                        
        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Security token missing. Please refresh the page and try again.');
            return;
        }
        
        // Show loading state
        declineModalSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        declineModalSubmit.disabled = true;
        
        // Create FormData for multipart form submission
        const formData = new FormData();
        formData.append('admin_notes', notes);
        formData.append('_token', csrfToken);
        
        // CORRECTED URL: Removed /admin prefix to match your routes
        fetch(`/admin/category-requests/${requestId}/decline`, {
                method: 'POST',
                body: formData
            })
        .then(response => {
            console.log("Response status:", response.status);
            
            // Check if the response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // For non-JSON responses (like HTML error pages)
                throw new Error(`Server returned ${response.status} with non-JSON response`);
            }
        })
        .then(data => {
            console.log("Response data:", data);
            
            if (data.success) {
                // Update the table
                updateRequestRow(requestId, 'declined');
                
                // Close the modal
                document.getElementById('declineRequestModal').style.display = 'none';
                
                // Show success message
                alert(data.message || 'Category request declined successfully');
                
                // Reset button
                declineModalSubmit.innerHTML = 'Decline Request';
                declineModalSubmit.disabled = false;
                
                // Reload page to ensure everything is updated
                window.location.reload();
            } else {
                // Reset button
                declineModalSubmit.innerHTML = 'Decline Request';
                declineModalSubmit.disabled = false;
                
                // Show error message
                alert(data.message || 'Failed to decline category request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset button
            declineModalSubmit.innerHTML = 'Decline Request';
            declineModalSubmit.disabled = false;
            
            alert('An error occurred while processing the request');
        });
    });
}

    
    // Function to update a request row in the table
    function updateRequestRow(requestId, newStatus) {
        const row = document.querySelector(`tr[data-id="${requestId}"]`) || 
                   document.querySelector(`.approve-request[data-id="${requestId}"]`).closest('tr');
        
        if (row) {
            // Update status cell (4th column)
            const statusCell = row.querySelector('td:nth-child(4)');
            if (statusCell) {
                if (newStatus === 'approved') {
                    statusCell.innerHTML = '<span class="category-status-badge category-status-success">Approved</span>';
                } else if (newStatus === 'declined') {
                    statusCell.innerHTML = '<span class="category-status-badge category-status-danger">Declined</span>';
                }
            }
            
            // Update action buttons - replace with just the view button
            const actionCell = row.querySelector('td:last-child');
            if (actionCell) {
                const viewButton = row.querySelector('.view-request');
                if (viewButton) {
                    // Clone the view button to keep its data attributes
                    const viewButtonClone = viewButton.cloneNode(true);
                    
                    // Replace the action cell content with just the view button
                    actionCell.innerHTML = '<div class="category-action-buttons"></div>';
                    actionCell.querySelector('.category-action-buttons').appendChild(viewButtonClone);
                } else {
                    // If no view button found, create a basic one
                    actionCell.innerHTML = `
                        <div class="category-action-buttons">
                            <button class="category-action-btn category-info-btn view-request" 
                                    data-id="${requestId}" 
                                    title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    `;
                }
            }
        }
    }
    
    // Function to update pending counts via AJAX
    function updatePendingCount() {
       fetch('/admin/category-requests/pending-count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update badge in tab
            const tabBadge = document.querySelector('#categoryTabs .category-tab-btn:nth-child(2) .category-badge');
            
            if (data.count > 0) {
                if (tabBadge) {
                    tabBadge.textContent = data.count;
                } else {
                    const requestsTab = document.querySelector('#categoryTabs .category-tab-btn:nth-child(2)');
                    if (requestsTab) {
                        const badge = document.createElement('span');
                        badge.className = 'category-badge category-badge-warning';
                        badge.textContent = data.count;
                        requestsTab.appendChild(badge);
                    }
                }
            } else {
                if (tabBadge) {
                    tabBadge.remove();
                }
            }
            
            // Update filter button counts
            const pendingFilterCount = document.querySelector('a[href="?tab=requests&status=pending"] .category-count');
            if (pendingFilterCount) {
                pendingFilterCount.textContent = data.count;
            }
            
            if (data.approvedCount !== undefined) {
                const approvedFilterCount = document.querySelector('a[href="?tab=requests&status=approved"] .category-count');
                if (approvedFilterCount) {
                    approvedFilterCount.textContent = data.approvedCount;
                }
            }
            
            if (data.declinedCount !== undefined) {
                const declinedFilterCount = document.querySelector('a[href="?tab=requests&status=declined"] .category-count');
                if (declinedFilterCount) {
                    declinedFilterCount.textContent = data.declinedCount;
                }
            }
        })
        .catch(error => {
            console.error('Error updating pending count:', error);
        });
    }
    
    // Tab switching function
    window.showCategoryTab = function(tabName) {
        // Update active tab
        const tabs = document.querySelectorAll('#categoryTabs .category-tab-btn');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        const selectedTab = document.querySelector(`#categoryTabs .category-tab-btn[onclick*="${tabName}"]`);
        if (selectedTab) {
            selectedTab.classList.add('active');
        }

        // Hide all tab panes and show the selected one
        const tabPanes = document.querySelectorAll('.category-tab-pane');
        tabPanes.forEach(pane => pane.classList.remove('active'));
        
        const activePane = document.getElementById(tabName + 'Tab');
        if (activePane) {
            activePane.classList.add('active');
        }

        // Update URL parameter for tab state
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    };
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('category-modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // Check URL for tab state on page load
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'requests') {
        showCategoryTab('requests');
    }
    
    console.log('Category request management script initialized');
});
</script>