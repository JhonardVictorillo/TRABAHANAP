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
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Freelancers</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            @if($category->image_path)
                                <img src="{{ asset('storage/'.$category->image_path) }}" alt="{{ $category->name }}" 
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 50px; height: 50px; background-color: #f3f4f6; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="color: #9ca3af;"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if($category->description)
                                <span class="description-preview" style="display: block; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $category->description }}
                                </span>
                                @if(strlen($category->description) > 50)
                                    <button class="view-description" data-description="{{ $category->description }}" 
                                            style="background: none; border: none; color: #2563eb; font-size: 0.75rem; padding: 0; cursor: pointer; text-decoration: underline;">
                                        View more
                                    </button>
                                @endif
                            @else
                                <span style="color: #9ca3af; font-style: italic;">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="user-count" style="background-color: #e5e7eb; color: #4b5563; padding: 2px 6px; border-radius: 9999px; font-size: 0.75rem;">
                                {{ $category->users_count ?? 0 }}
                            </span>
                            <button class="view-users" data-id="{{ $category->id }}" data-name="{{ $category->name }}" 
                                    style="background: #2563eb; color: white; border: none; width: 22px; height: 22px; border-radius: 50%; font-size: 0.7rem; cursor: pointer; margin-left: 5px;">
                                <i class="fas fa-users"></i>
                            </button>
                        </td>
                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="category-action-buttons">
                               <button class="edit-category" 
                                      data-id="{{ $category->id }}" 
                                      data-name="{{ $category->name }}"
                                      data-description="{{ $category->description ?? '' }}"
                                      data-image="{{ $category->image_path ? asset('storage/'.$category->image_path) : '' }}">
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
                        <td colspan="7" class="category-empty-state">No categories found.</td>
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


    
  </main>

  
<!-- Modal Structure -->
<div id="categoryModal" class="modal">
  <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Add Category</h2>
      <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="category-form-group">
              <label for="categoryName">Category Name:</label>
              <input type="text" id="categoryName" name="name" value="{{ old('name') }}" 
                    class="category-form-control {{ $errors->has('name') ? 'input-error' : '' }}" required>
              @error('name')
                  <div class="error-message" style="color: red; margin-top: 5px;">{{ $message }}</div>
              @enderror
          </div>
          
          <div class="category-form-group">
              <label for="categoryDescription">Description (Optional):</label>
              <textarea id="categoryDescription" name="description" 
                      class="category-form-control" rows="3">{{ old('description') }}</textarea>
          </div>
          
          <div class="category-form-group">
              <label for="categoryImage">Category Image (Optional):</label>
              <input type="file" id="categoryImage" name="image" 
                    class="category-form-control" accept="image/*">
              <div class="category-form-help" style="font-size: 0.8rem; color: #666; margin-top: 5px;">
                  Recommended size: 200x200px. Max size: 5MB.
              </div>
              <div id="imagePreviewContainer" style="display: none; margin-top: 10px;">
                  <img id="imagePreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ddd; padding: 3px;">
                  <button type="button" id="removeImage" style="background: #f44336; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; margin-left: 5px;">×</button>
              </div>
          </div>
          
          <button id="saveCategory" style="background-color: #2563eb; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-top: 10px;">Add Category</button>
      </form>
  </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal">
  <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Edit Category</h2>
      <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="editCategoryId">
          
          <div class="category-form-group">
              <label for="editCategoryName">Category Name:</label>
              <input type="text" id="editCategoryName" name="name" class="category-form-control" required>
          </div>
          
          <div class="category-form-group">
              <label for="editCategoryDescription">Description (Optional):</label>
              <textarea id="editCategoryDescription" name="description" 
                      class="category-form-control" rows="3"></textarea>
          </div>
          
          <div class="category-form-group">
              <label for="editCategoryImage">Category Image:</label>
              <input type="file" id="editCategoryImage" name="image" 
                    class="category-form-control" accept="image/*">
              <div class="category-form-help" style="font-size: 0.8rem; color: #666; margin-top: 5px;">
                  Leave empty to keep the current image. Recommended size: 200x200px. Max size: 5MB.
              </div>
              <div id="editImagePreviewContainer" style="margin-top: 10px;">
                  <img id="editImagePreview" src="#" alt="Preview" style="max-width: 100px; max-height: 100px; border: 1px solid #ddd; padding: 3px;">
                  <button type="button" id="removeEditImage" style="background: #f44336; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; margin-left: 5px;">×</button>
              </div>
          </div>
          
          <div class="modal-buttons">
              <button type="button" id="updateCategory" style="background-color: #2563eb; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-top: 10px; margin-right: 10px;">Update</button>
              <button type="button" id="cancelEditCategory" class="cancel-btn" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-top: 10px;">Cancel</button>
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
   // EDIT CATEGORY - UPDATE TO HANDLE IMAGE AND DESCRIPTION
const editButtons = document.querySelectorAll(".edit-category");
editButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        // Get the category data from data attributes
        const categoryId = this.getAttribute("data-id");
        const categoryName = this.getAttribute("data-name");
        const categoryDescription = this.getAttribute("data-description") || '';
        const categoryImage = this.getAttribute("data-image");
        
        console.log("Edit clicked for:", categoryId, categoryName);
        
        // Set the form values
        document.getElementById("editCategoryId").value = categoryId;
        document.getElementById("editCategoryName").value = categoryName;
        document.getElementById("editCategoryDescription").value = categoryDescription;
        
        // Set the image preview if available
        const imagePreview = document.getElementById("editImagePreview");
        const previewContainer = document.getElementById("editImagePreviewContainer");
        
        if (categoryImage && categoryImage !== '') {
            imagePreview.src = categoryImage;
            previewContainer.style.display = "block";
        } else {
            imagePreview.src = "";
            previewContainer.style.display = "none";
        }
        
        // Show the modal
        document.getElementById("editCategoryModal").style.display = "flex";
    });
});

// IMAGE PREVIEW HANDLERS
document.addEventListener('DOMContentLoaded', function() {
    // Image preview for Add Category
    const categoryImage = document.getElementById('categoryImage');
    if (categoryImage) {
        categoryImage.addEventListener('change', function(event) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

    // Remove image button for Add Category
    const removeImage = document.getElementById('removeImage');
    if (removeImage) {
        removeImage.addEventListener('click', function() {
            const input = document.getElementById('categoryImage');
            if (input) input.value = '';
            
            const container = document.getElementById('imagePreviewContainer');
            if (container) container.style.display = 'none';
        });
    }
    
    // Image preview for Edit Category
    const editCategoryImage = document.getElementById('editCategoryImage');
    if (editCategoryImage) {
        editCategoryImage.addEventListener('change', function(event) {
            const preview = document.getElementById('editImagePreview');
            const container = document.getElementById('editImagePreviewContainer');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Remove image button for Edit Category
    const removeEditImage = document.getElementById('removeEditImage');
    if (removeEditImage) {
        removeEditImage.addEventListener('click', function() {
            const input = document.getElementById('editCategoryImage');
            if (input) input.value = '';
            
            const preview = document.getElementById('editImagePreview');
            if (preview) preview.src = '';
            
            // Don't hide the container if there's an existing image
            // Just clear the file input
        });
    }
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

    const form = this.closest("form");
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Show loading state
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    this.disabled = true;

    fetch(form.action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData // Use FormData to include files
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        this.innerHTML = 'Add Category';
        this.disabled = false;
        
        if (data.success) {
            alert(data.message);
            // Reload the page to show updated list with proper images
            window.location.reload();
        } else {
            alert("Failed to add category: " + (data.message || "Unknown error"));
        }
    })
    .catch(error => {
        console.error("Error:", error);
        this.innerHTML = 'Add Category';
        this.disabled = false;
        alert("An error occurred while adding the category");
    });
});

    // UPDATE CATEGORY AJAX
  const updateCategoryBtn = document.getElementById("updateCategory");
updateCategoryBtn.addEventListener("click", function (e) {
    e.preventDefault();

    const categoryId = document.getElementById("editCategoryId").value;
    const form = document.getElementById("editCategoryForm");
    const formData = new FormData(form);
    
    // Add method-spoofing field for PUT request
    formData.append('_method', 'PUT');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Show loading state
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    this.disabled = true;

    fetch(`/categories/${categoryId}`, {
        method: "POST", // Always POST with FormData, _method field handles the rest
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest"
        },
        body: formData // Use FormData to include files
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        this.innerHTML = 'Update';
        this.disabled = false;
        
        if (data.success) {
            alert(data.message);
            // Reload the page to show updated list with proper images
            window.location.reload();
        } else {
            alert("Failed to update category: " + (data.message || "Unknown error"));
        }
    })
    .catch(error => {
        console.error("Error:", error);
        this.innerHTML = 'Update';
        this.disabled = false;
        alert("An error occurred while updating the category");
    });
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
        
        // Add explicit Accept header for JSON
        fetch(`/admin/category-requests/${requestId}/decline`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log("Response status:", response.status);
            
            // Always try to parse as JSON first
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch(e) {
                    console.error("Failed to parse response as JSON:", text);
                    throw new Error(`Server returned ${response.status} with invalid JSON response`);
                }
            });
        })
        .then(data => {
            console.log("Response data:", data);
            
            if (data && data.success) {
                // Update the table
                updateRequestRow(requestId, 'declined');
                
                // Close the modal using vanilla JavaScript
                const modal = document.getElementById('declineRequestModal');
                
                // If using Bootstrap 5
                if (typeof bootstrap !== 'undefined') {
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    if (bootstrapModal) {
                        bootstrapModal.hide();
                    }
                } 
                // Fallback method
                else {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    // Remove backdrop if present
                    const backdrops = document.getElementsByClassName('modal-backdrop');
                    while(backdrops.length > 0) {
                        backdrops[0].parentNode.removeChild(backdrops[0]);
                    }
                }
                
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
                alert(data?.message || 'Failed to decline category request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Reset button
            declineModalSubmit.innerHTML = 'Decline Request';
            declineModalSubmit.disabled = false;
            
            alert('An error occurred while processing the request: ' + error.message);
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

// VIEW DESCRIPTION MODAL
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-description')) {
        const button = e.target.closest('.view-description');
        const description = button.getAttribute('data-description');
        
        // Create modal if it doesn't exist
        let descriptionModal = document.getElementById('viewDescriptionModal');
        if (!descriptionModal) {
            const modalHTML = `
                <div id="viewDescriptionModal" class="category-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center;">
                    <div class="category-modal-content" style="background-color: #fefefe; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 80%; max-width: 500px; position: relative;">
                        <div class="category-modal-header" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                            <h4 class="category-modal-title" style="margin: 0; font-size: 1.2rem;">Category Description</h4>
                            <span class="category-modal-close" style="position: absolute; top: 10px; right: 15px; font-size: 1.5rem; cursor: pointer;">&times;</span>
                        </div>
                        <div class="category-modal-body">
                            <div id="fullDescription" style="white-space: pre-wrap; font-size: 0.9rem; line-height: 1.5; max-height: 300px; overflow-y: auto; padding: 1rem; background-color: #f9fafb; border-radius: 4px;"></div>
                        </div>
                        <div class="category-modal-footer" style="margin-top: 15px; text-align: right;">
                            <button type="button" class="category-btn category-btn-secondary" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            descriptionModal = document.getElementById('viewDescriptionModal');
            
            // Add close button handler
            descriptionModal.querySelector('.category-modal-close').addEventListener('click', function() {
                descriptionModal.style.display = 'none';
            });
            
            // Add close button handler
            descriptionModal.querySelector('.category-btn-secondary').addEventListener('click', function() {
                descriptionModal.style.display = 'none';
            });
        }
        
        // Set the description in the modal
        document.getElementById('fullDescription').textContent = description;
        
        // Show the modal
        descriptionModal.style.display = 'flex';
    }
});

// VIEW USERS MODAL
document.addEventListener('click', function(e) {
    if (e.target.closest('.view-users')) {
        const button = e.target.closest('.view-users');
        const categoryId = button.getAttribute('data-id');
        const categoryName = button.getAttribute('data-name');
        
        // Create modal if it doesn't exist
        let usersModal = document.getElementById('viewUsersModal');
        if (!usersModal) {
            const modalHTML = `
                <div id="viewUsersModal" class="category-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center;">
                    <div class="category-modal-content" style="background-color: #fefefe; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 80%; max-width: 500px; position: relative;">
                        <div class="category-modal-header" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                            <h4 class="category-modal-title" style="margin: 0; font-size: 1.2rem;">Users in <span id="categoryNameTitle"></span></h4>
                            <span class="category-modal-close" style="position: absolute; top: 10px; right: 15px; font-size: 1.5rem; cursor: pointer;">&times;</span>
                        </div>
                        <div class="category-modal-body">
                            <div style="max-height: 300px; overflow-y: auto;">
                                <ul id="categoryUsersList" style="list-style: none; padding: 0; margin: 0;">
                                    <li style="text-align: center; padding: 1rem; color: #6b7280;">Loading users...</li>
                                </ul>
                            </div>
                        </div>
                        <div class="category-modal-footer" style="margin-top: 15px; text-align: right;">
                            <button type="button" class="category-btn category-btn-secondary" style="background-color: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Close</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            usersModal = document.getElementById('viewUsersModal');
            
            // Add close button handler
            usersModal.querySelector('.category-modal-close').addEventListener('click', function() {
                usersModal.style.display = 'none';
            });
            
            // Add close button handler
            usersModal.querySelector('.category-btn-secondary').addEventListener('click', function() {
                usersModal.style.display = 'none';
            });
        }
        
        // Set category name in modal title
        document.getElementById('categoryNameTitle').textContent = categoryName;
        
        // Show loading state
        document.getElementById('categoryUsersList').innerHTML = '<li style="text-align: center; padding: 1rem; color: #6b7280;">Loading users...</li>';
        
        // Show the modal
        usersModal.style.display = 'flex';
        
        // Fetch users for this category
        fetch(`/categories/${categoryId}/users`)
            .then(response => response.json())
            .then(data => {
                const usersList = document.getElementById('categoryUsersList');
                usersList.innerHTML = '';
                
                if (data.users && data.users.length > 0) {
                    data.users.forEach(user => {
                        const li = document.createElement('li');
                        li.style.padding = '0.75rem';
                        li.style.borderBottom = '1px solid #e5e7eb';
                        li.style.display = 'flex';
                        li.style.alignItems = 'center';
                        
                        li.innerHTML = `
                            <img src="${user.avatar || '/images/defaultprofile.jpg'}" alt="${user.name}" 
                                style="width: 32px; height: 32px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                            <div>
                                <div style="font-weight: 600;">${user.name}</div>
                                <div style="font-size: 0.8rem; color: #6b7280;">${user.email}</div>
                            </div>
                        `;
                        usersList.appendChild(li);
                    });
                } else {
                    usersList.innerHTML = '<li style="text-align: center; padding: 1rem; color: #6b7280;">No users in this category</li>';
                }
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                document.getElementById('categoryUsersList').innerHTML = 
                    '<li style="text-align: center; padding: 1rem; color: #ef4444;">Error loading users. Please try again.</li>';
            });
    }
});




</script>