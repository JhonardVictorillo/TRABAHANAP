<!-- <div id="category" class="section" style="display: none;"> 
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
                <td>{{ $category->created_at->format('Y-m-d H:i') }}</td> 
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
</div> -->

 <!-- Modal -->
 <!-- <div id="categoryModal" class="modal" style="display: none;">
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
</div> -->

<!-- Categories Section -->
<div class="details-section" id="categoriesSection" style="display: none;">
      <h2>Categories</h2>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {

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

// DELETE CATEGORY AJAX
document.querySelector("#categoriesSection table tbody").addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("delete-btn")) {
        e.preventDefault();

        const categoryId = e.target.getAttribute("data-id");
        if (!confirm("Are you sure you want to delete this category?")) return;

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

                // Remove the row from the DOM
                const row = e.target.closest("tr");
                if (row) {
                    row.remove();
                }
            } else {
                alert("Failed to delete category.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
});
// ADD NEW CATEGORY ROW IN TABLE
function addCategoryRow(category) {
    const tbody = document.querySelector("#categoriesSection table tbody");
    const newRow = document.createElement("tr");
    newRow.setAttribute("id", `categoryRow-${category.id}`);
    newRow.setAttribute("data-id", category.id); // 👈 Add this so it works with updateCategoryRow()

    newRow.innerHTML = `
        <td>${category.id}</td>
        <td>${category.name}</td>
        <td>${new Date(category.created_at).toLocaleString()}</td>
        <td>
            <button class="verify-btn edit-category-btn" data-id="${category.id}">Edit</button>
            <form action="/categories/${category.id}" method="POST" style="display:inline;">
                <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="delete-btn">Delete</button>
            </form>
        </td>
    `;
    tbody.appendChild(newRow);

    // 👇 Attach event listener to the newly created edit button!
    newRow.querySelector(".edit-category-btn").addEventListener("click", function () {
        const categoryId = this.getAttribute("data-id");
        openEditModal(categoryId);
    });
}


// UPDATE CATEGORY ROW IN TABLE
function updateCategoryRow(category) {
    const categoryRow = document.querySelector(`tr[data-id="${category.id}"]`);

    if (categoryRow) {
        const nameCell = categoryRow.querySelector('td:nth-child(2)');
        nameCell.textContent = category.name; // Immediately update the cell
    }
}

// REMOVE CATEGORY ROW FROM TABLE
function removeCategoryRow(categoryId) {
    const row = document.querySelector(`#categoryRow-${categoryId}`);
    if (row) {
        row.remove();
    }
}

});

</script>