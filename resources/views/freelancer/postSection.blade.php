<section class="post-section" >
      
    <div class="details-section" id="postContainer" style="display: none;">
       <div class="section-header flex justify-between items-center flex-wrap mb-4">
            <h2 class="text-lg font-bold text-blue-600 mb-2 sm:mb-0">My Posts</h2>
            @if(auth()->user()->is_suspended || auth()->user()->is_banned)
                <button disabled class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center">
                    <i class="fas fa-ban mr-2"></i>
                    @if(auth()->user()->is_suspended)
                        Account Suspended
                    @else
                        Account Banned
                    @endif
                </button>
                <p class="text-xs text-red-600 mt-2">
                    @if(auth()->user()->is_suspended)
                        Your account is suspended until {{ auth()->user()->suspended_until->format('M d, Y') }}
                    @else
                        Your account has been banned. Please contact support.
                    @endif
                </p>
            @elseif(auth()->user()->is_restricted && (!auth()->user()->restriction_end || now()->lessThan(auth()->user()->restriction_end)))
                <button disabled class="px-4 py-2 bg-yellow-400 text-white rounded-lg cursor-not-allowed flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Posting Restricted
                </button>
                <p class="text-xs text-yellow-600 mt-2">
                    Your account is restricted from creating new posts.
                    @if(auth()->user()->restriction_end)
                        <br>
                        Restriction ends on {{ auth()->user()->restriction_end->format('M d, Y') }}.
                        <br>
                        ({{ \Carbon\Carbon::parse(auth()->user()->restriction_end)->diffForHumans(now()) }})
                    @endif
                    Please contact support for more information.
                </p>
            @else
                <button id="createPostBtn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Create New Post
                </button>
            @endif
        </div>

      
        <div class="post-grid mt-4">
          <!-- Post Card -->
          @foreach ($posts as $post)
          <div class="post-card">
                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold
                @if($post->status == 'approved') bg-green-100 text-green-700
                @elseif($post->status == 'pending') bg-yellow-100 text-yellow-700
                @else bg-red-100 text-red-700
                @endif
            ">
                @if($post->status == 'approved')
                    Approved
                @elseif($post->status == 'pending')
                    Pending Review
                @else
                    Rejected
                @endif
            </span>
            <div class="text-center">
            <img src="{{ asset('storage/' . ($post->freelancer->profile_picture ?? 'defaultprofile.jpg')) }}" class="w-20 h-20 rounded-full mx-auto" />
             
              <h4 class="text-lg font-bold mt-2">{{ $post->freelancer->firstname ?? 'Unknown' }} , {{ $post->freelancer->lastname ?? 'Unknown' }}</h4>
              <p class="text-gray-500">  @if($post->freelancer->categories->isEmpty())
                    Category Not Assigned
                @else
                    @foreach($post->freelancer->categories as $category)
                        {{ $category->name }}
                    @endforeach
                @endif
            </p>
            <div class= "ratings">
            @php
                        $averageRating = $post->averageRating(); // Calculate average rating
                        $totalReviews = $post->totalReviews();   // Total number of reviews
                        $starCount = floor($averageRating);      // Full stars
                        $halfStar = ($averageRating - $starCount) >= 0.5; // Half-star logic
                    @endphp

                    @for ($i = 0; $i < $starCount; $i++)
                        <i class="fas fa-star"></i>
                    @endfor

                
                    @if ($halfStar)
                        <i class="fas fa-star-half-alt"></i>
                    @endif

                    @for ($i = $starCount + ($halfStar ? 1 : 0); $i < 5; $i++)
                        <i class="far fa-star"></i>
                    @endfor
              <p class="text-yellow-500 text-sm">{{ number_format($averageRating, 1) }} / 5 • {{ $totalReviews }} reviews</p>
              </div>
            </div>
      
            <div class="tags">
              
            @if($post->subServices->isNotEmpty())
            @foreach ($post->subServices as $subService)
                <span>{{ $subService->sub_service }}</span>
            @endforeach
        @else
            <span>No sub-services available</span>
        @endif
            </div>
      
            <p class="mt-2 text-gray-600 text-sm">  {{ $post->description }}</p>
      
            <div class="flex justify-center mt-2">
            @php
                $postPictures = $post->pictures->take(3);
            @endphp
            @if ($postPictures  ->isNotEmpty())
                @foreach ($postPictures as $picture)
                    <img src="{{ asset('storage/' . $picture->image_path) }}" class="w-20 h-20 rounded-md mx-1">
                @endforeach
            @endif

            @if ($post->pictures->count() > 3)
                <span class="more-work">+{{ $post->pictures->count() - 3 }} More</span>
            @endif
            </div>
            <div class="salary-rate text-blue-600 font-semibold mt-2">
                @if($post->rate && $post->rate_type)
                    ₱{{ number_format($post->rate, 2) }} / {{ ucfirst($post->rate_type) }}
                @else
                    <span class="text-gray-400">No rate specified</span>
                @endif
            </div>
      
            <div class="flex justify-center mt-2 space-x-4">
            <button class="edit-btn flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg" data-post-id="{{ $post->id }}">
                <i class="fas fa-edit mr-1"></i> Edit Post
            </button>
            <button class="delete-btn flex items-center px-4 py-2 bg-red-500 text-white rounded-lg" data-post-id="{{ $post->id }}">
                <i class="fas fa-trash-alt mr-1"></i> Delete Post
            </button>
            </div>
          </div>
          @endforeach

            @if($posts->isEmpty())
          <div class="col-span-full text-center py-10">
            <div class="text-gray-400 mb-4">
              <i class="fas fa-file-alt text-5xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No posts yet</h3>
            <p class="text-gray-500 mb-4">Create your first post to showcase your services</p>
          </div>
          @endif
        </div>
      </div>
    
    </section>

    <section>
      <!-----------------------------------------------Create post----------------------------------------->
     <!-- CREATE POST MODAL -->
<div id="createPostModal" class="con-modal" >
    <div class="postmodal-content">
        <div class="modal-header">
            <h3>Create New Post</h3>
            <button  class="close-modal">&times;</button>
        </div>
        <form id="create-post-form">
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category"  value="{{ $freelancerCategory ? $freelancerCategory->name : 'No category assigned' }}"  readonly class="form-control">
            </div>

            <div class="form-group">
                <label for="subservices">Subservices</label>
                <div id="subservices-container">
                    <input type="text" name="sub_services[]" class="form-control" placeholder="Enter subservice">
                </div>
                <button type="button" id="addSubserviceBtn" class="btn btn-secondary">Add Another</button>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="Write a brief description"></textarea>
            </div>

            <div class="form-group">
                <label for="recentWorks">Recent Works</label>
                <input type="file" name="post_picture[]" id="recentWorks" class="form-control-file" accept="image/*" multiple>
                <div id="imagePreviewContainer" class="image-preview-container"></div> 
            </div>
            <div class="form-group">
                <label for="rate">Salary Rate</label>
                <input type="number" name="rate" id="rate" class="form-control" min="0" step="0.01" placeholder="Enter rate (e.g. 500)">
            </div>
            <div class="form-group">
                <label for="rate_type">Rate Type</label>
                <select name="rate_type" id="rate_type" class="form-control">
                    <option value="hourly">Hourly</option>
                    <option value="daily">Daily</option>
                    <option value="fixed">Fixed</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create Post</button>
               
            </div>
        </form>
    </div>
</div>


   
  
  

    </section>

    <!-- ----------------------------------------------edit post ------------------------------->
    <section>
        <!-- EDIT POST MODAL -->
    <div id="editPostModal" class="con-modal" >
        <div class="postmodal-content">
            <div class="modal-header">
                <h3>Edit Post</h3>
                <button  class="close-modal">&times;</button>
            </div>
            <form id="edit-post-form">
                <input type="hidden" name="post_id" id="editPostId">

                <div class="form-group">
                    <label for="edit-description">Description</label>
                    <textarea id="edit-description" name="description" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit-sub-services">Subservices</label>
                    <div id="edit-sub-services-container"></div>
                    <button type="button" id="addEditSubserviceBtn" class="btn btn-secondary">Add Another</button>
                </div>

                <div class="form-group">
                    <label for="edit-recent-works">Current Images</label>
                    <div id="currentImageWrapper" class="image-preview-container"></div>
                    <input type="hidden" name="delete_images" id="delete_images" value="[]">
                </div>

                <div class="form-group">
                    <label for="edit-post-picture">Upload New Images</label>
                    <input type="file" id="edit-post-picture" name="post_picture[]" class="form-control-file" multiple>
                </div>
                <div id="newImagePreviewContainer" class="image-preview-container"></div>

                <div class="form-group">
                    <label for="edit-rate">Salary Rate</label>
                    <input type="number" name="rate" id="edit-rate" class="form-control" min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label for="edit-rate-type">Rate Type</label>
                    <select name="rate_type" id="edit-rate-type" class="form-control">
                        <option value="hourly">Hourly</option>
                        <option value="daily">Daily</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-danger" id="cancelEditBtn">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    </section>
  </main>

  <!-- JavaScript -->
  <script>
document.addEventListener('DOMContentLoaded', () => {
  

    // Function to toggle modals
    function toggleModal(modalId, show = true) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = show ? 'flex' : 'none';
        } else {
            console.error(`Modal with ID "${modalId}" not found.`);
        }
    }

    // Open Create Post Modal
    document.getElementById('createPostBtn')?.addEventListener('click', () => {
        @if(auth()->user()->is_suspended || auth()->user()->is_banned || 
        (auth()->user()->is_restricted && (!auth()->user()->restriction_end || now()->lessThan(auth()->user()->restriction_end))))
        
        alert(`
            @if(auth()->user()->is_suspended)
                Your account is currently suspended until {{ auth()->user()->suspended_until->format('M d, Y') }}.
            @elseif(auth()->user()->is_banned)
                Your account has been banned due to policy violations.
            @else
                Your account is currently restricted from creating new services due to policy violations.
                Restrictions will be lifted on {{ auth()->user()->restriction_end->format('M d, Y') }}.
            @endif
            Please contact support for assistance.
        `);
        return;
    @endif
        toggleModal('createPostModal');
    });

        // Open Edit Post Modal
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('edit-btn')) {
                console.log('Edit button clicked!');
                const postId = event.target.getAttribute('data-post-id');
                console.log('Post ID:', postId);

                if (!postId) {
                    console.error('❌ Post ID not found!');
                    return;
                }

                fetch(`/posts/${postId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched Data:', data);

                        // Ensure the modal elements exist
                        const editPostId = document.getElementById('editPostId');
                        const editDescription = document.getElementById('edit-description');
                        const subServicesContainer = document.getElementById('edit-sub-services-container');
                        const imageWrapper = document.getElementById('currentImageWrapper');

                        if (!editPostId || !editDescription || !subServicesContainer || !imageWrapper) {
                            console.error('❌ Some modal elements are missing!');
                            return;
                        }

                        // Fill the form with fetched data
                        editPostId.value = postId;
                        editDescription.value = data.post.description;

                        // Fix potential undefined array issue
                    // Fix sub-services
            subServicesContainer.innerHTML = '';
            if (Array.isArray(data.sub_services)) {
                data.sub_services.forEach(service => {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'sub_services[]';
                    input.value = service;
                    input.classList.add('form-control', 'my-1');
                    subServicesContainer.appendChild(input);
                });
            }

            // Fix image display
           imageWrapper.innerHTML = '';
                if (Array.isArray(data.post_pictures)) {
                    data.post_pictures.forEach(image => {
                        const div = document.createElement('div');
                        div.classList.add('image-preview');

                        const img = document.createElement('img');
                        img.src = image.startsWith('/storage/') ? image : `/storage/${image}`;
                        img.alt = "Post image";

                        const deleteBtn = document.createElement('button');
                        deleteBtn.textContent = 'X';
                        deleteBtn.classList.add('delete-image');
                        deleteBtn.setAttribute('data-image', image);
                        deleteBtn.addEventListener('click', function(e) {
                            e.preventDefault(); // Prevent form submission
                            div.remove(); // Remove the image preview
                            handleImageDeletion(image);
                        });

                        div.appendChild(img);
                        div.appendChild(deleteBtn);
                        imageWrapper.appendChild(div);
                    });
                }

                toggleModal('editPostModal', true); // Fix: use toggleModal instead of openModal
            })
            .catch(error => console.error('❌ Error fetching post data:', error))
    }
        });
            // editpost image preview////////////////////////////////////////////
            function handleImageDeletion(imageToDelete) {
                let deleteInput = document.querySelector('input[name="delete_images"]');
                
                // If the delete_images hidden input doesn't exist, create it
                if (!deleteInput) {
                    deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_images';
                    document.getElementById('edit-post-form').appendChild(deleteInput);
                }

                // Get the existing delete_images value or initialize an empty array
                let existingImages = deleteInput.value ? deleteInput.value.split(',') : [];
                
                // Add the image to the delete list if it hasn't been added already
                if (!existingImages.includes(imageToDelete)) {
                    existingImages.push(imageToDelete);
                }

                // Update the delete_images field with the new list
                deleteInput.value = existingImages.join(','); // Store as a comma-separated string
                console.log("🛠️ Updated delete_images:", deleteInput.value);    
            }

            // Fix Image Preview Deletion on Edit Post Modal
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('delete-image')) {
                    const imageToDelete = event.target.getAttribute('data-image');
                    
                    // Remove the image preview from the modal
                    event.target.closest('.image-preview').remove();
                    
                    // Update the hidden input with the image to delete
                    handleImageDeletion(imageToDelete);
                }
            });

            

        // Close modals when clicking the close button
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function () {
                const modalId = this.closest('.con-modal').id;

                // Reset the Create Post Modal if it's being closed
                if (modalId === 'createPostModal') {
                    resetCreatePostModal();
                }

                toggleModal(modalId, false);
            });
        });

        document.getElementById('cancelEditBtn')?.addEventListener('click', () => {
        toggleModal('editPostModal', false);
    });
        // Close modals if clicking outside the content
        document.querySelectorAll('.con-modal').forEach(modal => {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    // Reset the Create Post Modal if it's being closed
                    if (modal.id === 'createPostModal') {
                        resetCreatePostModal();
                    }
                    toggleModal(modal.id, false);
                }
            });
        });
            // addsubservicess button //////////////////////////////////////////  
       
             document.getElementById('addEditSubserviceBtn')?.addEventListener('click', function () {
    const subservicesContainer = document.getElementById('edit-sub-services-container');
    
            // Create a new input field
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'sub_services[]';
            newInput.classList.add('form-control');
            newInput.placeholder = 'Enter subservice';

            // Append the new input to the container
            subservicesContainer.appendChild(newInput);
        });

        // Handle Edit Post Form Submission
        document.getElementById('edit-post-form')?.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_method', 'PUT'); 

            const postId = document.getElementById('editPostId').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Get delete_images input and append manually to formData
            const deleteImagesInput = document.querySelector('input[name="delete_images"]');
            if (deleteImagesInput && deleteImagesInput.value) {
                formData.append('delete_images', deleteImagesInput.value); // Ensure it is sent
            }
            // Log delete_images value before sending request
            console.log("🚀 delete_images before submit:", formData.get("delete_images"));

            fetch(`/posts/${postId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text); });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Post updated successfully!');
                    location.reload();
                } else {
                    showValidationErrors(data.errors, 'editPostErrors');
                }
            })
            .catch(error => {
                console.error('Server Error:', error);
            });
        });

    // Handle Create Post Form Submission//////////////////////////
    document.getElementById('create-post-form')?.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/posts', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Post created successfully!');
                location.reload();
            } else {
                showValidationErrors(data.errors, 'createPostErrors');
            }
        })
        .catch(error => console.error('Error:', error));
    });
            //addsubservices////
    document.getElementById('addSubserviceBtn')?.addEventListener('click', function () {
    const subservicesContainer = document.getElementById('subservices-container');
    
            // Create a new input field
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'sub_services[]';
            newInput.classList.add('form-control');
            newInput.placeholder = 'Enter subservice';

            // Append the new input to the container
            subservicesContainer.appendChild(newInput);
        });

            // image preview////////////////////////////////////////////
        document.getElementById('recentWorks')?.addEventListener('change', function(event) {
    const previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = ''; // Clear previous previews

    const files = event.target.files;

    if (files.length > 0) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    previewContainer.appendChild(imgElement);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});


  
  // Handle Delete Post
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
        const postId = button.getAttribute('data-post-id');
        if (!postId) {
            console.error('No post ID found on delete button');
            return;
        }
        
        if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Find the parent post-card element and remove it
                    const postCard = button.closest('.post-card');
                    if (postCard) {
                        postCard.remove();
                        alert(data.message || 'Post deleted successfully!');
                    } else {
                        console.warn('Post card parent element not found, refreshing page');
                        location.reload();
                    }
                } else {
                    alert(data.message || 'Failed to delete the post.');
                }
            })
            .catch(error => {
                console.error('Error deleting post:', error);
                alert('Failed to delete post. Please try again.');
            });
        }
    });
});
    // Function to display validation errors
    function showValidationErrors(errors, errorContainerId) {
        const errorContainer = document.getElementById(errorContainerId) || document.createElement('div');
        errorContainer.id = errorContainerId;
        errorContainer.className = 'text-red-500 mt-2';
        errorContainer.innerHTML = '';

        for (const key in errors) {
            const errorMsg = document.createElement('p');
            errorMsg.textContent = errors[key][0];
            errorContainer.appendChild(errorMsg);
        }

        document.getElementById('create-post-form')?.prepend(errorContainer);
    }
});

        function resetCreatePostModal() {
            const createPostForm = document.getElementById('create-post-form');
            const subservicesContainer = document.getElementById('subservices-container');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            // Reset the form fields
            createPostForm.reset();

            // Clear dynamically added sub-services
            subservicesContainer.innerHTML = `
                <input type="text" name="sub_services[]" class="form-control" placeholder="Enter subservice">
            `;

            // Clear image previews
            imagePreviewContainer.innerHTML = '';
        }
</script>
