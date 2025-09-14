<section class="post-section">
    <div class="details-section" id="postContainer" style="display: none;">
        <!-- Header Section -->
        <div class="post-section-header">
            <div class="header-content">
                <div class="header-title">
                    <h2><i class='bx bx-briefcase'></i> My Services</h2>
                    <p class="header-subtitle">Manage your service listings and track their status</p>
                </div>
                <div class="header-actions">
                    @if(auth()->user()->is_suspended || auth()->user()->is_banned)
                        <button disabled class="btn-disabled">
                            <i class="fas fa-ban"></i>
                            @if(auth()->user()->is_suspended)
                                Account Suspended
                            @else
                                Account Banned
                            @endif
                        </button>
                        <div class="status-message error">
                            @if(auth()->user()->is_suspended)
                                Your account is suspended until {{ auth()->user()->suspended_until->format('M d, Y') }}
                            @else
                                Your account has been banned. Please contact support.
                            @endif
                        </div>
                    @elseif(auth()->user()->is_restricted && (!auth()->user()->restriction_end || now()->lessThan(auth()->user()->restriction_end)))
                        <button disabled class="btn-disabled warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Posting Restricted
                        </button>
                        <div class="status-message warning">
                            Your account is restricted from creating new posts.
                            @if(auth()->user()->restriction_end)
                                <br>Restriction ends on {{ auth()->user()->restriction_end->format('M d, Y') }}.
                                <br>({{ \Carbon\Carbon::parse(auth()->user()->restriction_end)->diffForHumans(now()) }})
                            @endif
                        </div>
                    @else
                        <button id="createPostBtn" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Create New Service
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Posts Table -->
        <div class="posts-table-container">
            @if($posts->isNotEmpty())
                <div class="table-wrapper">
                    <table class="posts-table">
                        <thead>
                            <tr>
                                <th>Service Details</th>
                                <th>Category</th>
                                <th>Rate</th>
                                <th>Status</th>
                                <th>Performance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr class="post-row">
                                <!-- Service Details -->
                                <td class="service-details">
                                    <div class="service-info">
                                        <div class="service-images">
                                            @if($post->pictures->isNotEmpty())
                                                <img src="{{ asset('storage/' . $post->pictures->first()->image_path) }}" 
                                                     alt="Service preview" class="service-preview-img">
                                                @if($post->pictures->count() > 1)
                                                    <span class="image-count">+{{ $post->pictures->count() - 1 }}</span>
                                                @endif
                                            @else
                                                <div class="no-image">
                                                    <i class='bx bx-image'></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="service-content">
                                            <h4 class="service-title">{{ Str::limit($post->title ?? 'Service Title', 30) }}</h4>
                                            <p class="service-description">{{ Str::limit($post->description, 60) }}</p>
                                            <div class="service-subservices">
                                                @if($post->subServices->isNotEmpty())
                                                    @foreach($post->subServices->take(2) as $subService)
                                                        <span class="subservice-tag">{{ $subService->sub_service }}</span>
                                                    @endforeach
                                                    @if($post->subServices->count() > 2)
                                                        <span class="subservice-more">+{{ $post->subServices->count() - 2 }} more</span>
                                                    @endif
                                                @else
                                                    <span class="no-subservices">No sub-services</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="category-cell">
                                    <div class="category-info">
                                        @if($post->freelancer->categories->isNotEmpty())
                                            @foreach($post->freelancer->categories as $category)
                                                <span class="category-badge">{{ $category->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="category-badge unassigned">Not Assigned</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Rate -->
                                <td class="rate-cell">
                                    <div class="rate-info">
                                        @if($post->rate && $post->rate_type)
                                            <span class="rate-amount">₱{{ number_format($post->rate, 2) }}</span>
                                            <span class="rate-type">/ {{ ucfirst($post->rate_type) }}</span>
                                        @else
                                            <span class="rate-not-set">Rate not set</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="status-cell">
                                    <span class="status-badge status-{{ $post->status }}">
                                        @if($post->status == 'approved')
                                            <i class='bx bx-check-circle'></i> Approved
                                        @elseif($post->status == 'pending')
                                            <i class='bx bx-clock'></i> Pending Review
                                        @else
                                            <i class='bx bx-x-circle'></i> Rejected
                                        @endif
                                    </span>
                                </td>

                                <!-- Performance -->
                                <td class="performance-cell">
                                    <div class="performance-info">
                                        @php
                                            $averageRating = $post->averageRating();
                                            $totalReviews = $post->totalReviews();
                                        @endphp
                                        <div class="rating-display">
                                            <div class="stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($averageRating))
                                                        <i class='bx bxs-star'></i>
                                                    @elseif ($i - 0.5 <= $averageRating)
                                                        <i class='bx bxs-star-half'></i>
                                                    @else
                                                        <i class='bx bx-star'></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="rating-text">{{ number_format($averageRating, 1) }} ({{ $totalReviews }})</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="actions-cell">
                                    <div class="action-buttons">
                                        <button class="btn-action view-btn" onclick="viewPost({{ $post->id }})" title="View Details">
                                            <i class='bx bx-show'></i>
                                        </button>
                                        <button class="btn-action edit-btn" data-post-id="{{ $post->id }}" title="Edit Service">
                                            <i class='bx bx-edit'></i>
                                        </button>
                                        <button class="btn-action delete-btn" data-post-id="{{ $post->id }}" title="Delete Service">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class='bx bx-briefcase'></i>
                    </div>
                    <h3>No services yet</h3>
                    <p>Create your first service to showcase what you offer</p>
                    @if(!auth()->user()->is_suspended && !auth()->user()->is_banned && 
                        !(auth()->user()->is_restricted && (!auth()->user()->restriction_end || now()->lessThan(auth()->user()->restriction_end))))
                        <button id="createFirstPostBtn" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Create Your First Service
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>

<!-- View Post Modal -->
<div id="viewPostModal" class="post-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-content view-modal">
        <div class="modal-header">
            <h3><i class='bx bx-show'></i> Service Details</h3>
            <button class="modal-close" onclick="closeViewModal()">
                <i class='bx bx-x'></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="view-content">
                <!-- Service Images -->
                <div class="service-gallery" id="serviceGallery">
                    <!-- Images will be populated here -->
                </div>
                
                <!-- Service Information -->
                <div class="service-details-view">
                    <div class="detail-group">
                        <label>Service Title:</label>
                        <span id="viewTitle"></span>
                    </div>
                    
                    <div class="detail-group">
                        <label>Category:</label>
                        <span id="viewCategory"></span>
                    </div>
                    
                    <div class="detail-group">
                        <label>Description:</label>
                        <p id="viewDescription"></p>
                    </div>
                    
                    <div class="detail-group">
                        <label>Sub-services:</label>
                        <div id="viewSubservices" class="subservices-list"></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-group">
                            <label>Rate:</label>
                            <span id="viewRate"></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Duration:</label>
                            <span id="viewDuration"></span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-group">
                            <label>Buffer Time:</label>
                            <span id="viewBufferTime"></span>
                        </div>
                        
                        <div class="detail-group">
                            <label>Scheduling Mode:</label>
                            <span id="viewSchedulingMode"></span>
                        </div>
                    </div>
                    
                    <div class="detail-group">
                        <label>Location Restriction:</label>
                        <span id="viewLocationRestriction"></span>
                    </div>
                    
                    <div class="detail-group">
                        <label>Status:</label>
                        <span id="viewStatus" class="status-badge"></span>
                    </div>
                    
                    <!-- Performance Section -->
                    <div class="performance-section">
                        <h4>Performance</h4>
                        <div class="performance-stats">
                            <div class="stat-item">
                                <span class="stat-label">Rating:</span>
                                <div class="rating-display">
                                    <div id="viewRatingStars" class="stars"></div>
                                    <span id="viewRatingText"></span>
                                </div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Bookings:</span>
                                <span id="viewBookings">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeViewModal()">
                <i class='bx bx-x'></i> Close
            </button>
           
        </div>
    </div>
</div>
    </section>

    <section>
      <!-----------------------------------------------Create post----------------------------------------->
     <!-- CREATE POST MODAL -->

     
<div id="createPostModal" class="con-modal">
    <div class="modal-backdrop"></div>
    <div class="modern-modal-content">
        <!-- Modal Header -->
        <div class="modern-modal-header">
            <div class="header-icon">
                <i class='bx bx-plus-circle'></i>
            </div>
            <div class="header-text">
                <h3>Create New Service</h3>
                <p>Showcase your skills and attract new clients</p>
            </div>
            <button class="modern-close-btn close-modal">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="modern-modal-body">
            <form id="create-post-form">
                <!-- Service Category -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-category'></i>
                        <span>Service Category</span>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <div class="input-wrapper readonly-wrapper">
                            <i class='bx bx-tag'></i>
                            <input type="text" id="category" 
                                   value="{{ $freelancerCategory ? $freelancerCategory->name : 'No category assigned' }}" 
                                   readonly class="form-control readonly-input">
                            <span class="readonly-badge">Assigned</span>
                        </div>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-info-circle'></i>
                        <span>Service Details</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Service Description</label>
                        <div class="textarea-wrapper">
                            <textarea name="description" id="description" class="form-control modern-textarea" 
                                      placeholder="Describe your service in detail. What makes you unique?"></textarea>
                            <div class="char-counter">
                                <span id="descCharCount">0</span>/500
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subservices">Sub-services</label>
                        <div id="subservices-container">
                            <div class="input-wrapper">
                                <i class='bx bx-list-ul'></i>
                                <input type="text" name="sub_services[]" class="form-control" 
                                       placeholder="e.g., Logo Design, Business Cards">
                            </div>
                        </div>
                        <button type="button" id="addSubserviceBtn" class="add-more-btn">
                            <i class='bx bx-plus'></i>
                            Add Another Sub-service
                        </button>
                    </div>
                </div>

                <!-- Portfolio Images -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-images'></i>
                        <span>Portfolio Images</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="recentWorks">Upload Your Work</label>
                        <div class="file-upload-area">
                            <input type="file" name="post_picture[]" id="recentWorks" 
                                   class="file-input" accept="image/*" multiple>
                            <div class="upload-content">
                                <i class='bx bx-cloud-upload'></i>
                                <h4>Drag & drop images here</h4>
                                <p>or click to browse</p>
                                <span class="file-info">PNG, JPG up to 10MB each</span>
                            </div>
                        </div>
                        <div id="imagePreviewContainer" class="image-preview-grid"></div>
                    </div>
                </div>

                <!-- Pricing & Schedule -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-money'></i>
                        <span>Pricing & Schedule</span>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rate">Service Rate</label>
                            <div class="input-wrapper">
                                <i class='bx bx-peso'></i>
                                <input type="number" name="rate" id="rate" class="form-control" 
                                       min="0" step="0.01" placeholder="500">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rate_type">Rate Type</label>
                            <div class="select-wrapper">
                                <i class='bx bx-time'></i>
                                <select name="rate_type" id="rate_type" class="form-control">
                                    <option value="hourly">Hourly</option>
                                    <option value="daily">Daily</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="service_duration">Service Duration</label>
                            <div class="select-wrapper">
                                <i class='bx bx-stopwatch'></i>
                                <select name="service_duration" id="service_duration" class="form-control">
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60" selected>1 hour</option>
                                    <option value="90">1.5 hours</option>
                                    <option value="120">2 hours</option>
                                    <option value="180">3 hours</option>
                                    <option value="240">4 hours</option>
                                    <option value="360">6 hours</option>
                                    <option value="480">8 hours (Full day)</option>
                                </select>
                            </div>
                            <small class="form-hint">How long does your service typically take?</small>
                        </div>
                        <div class="form-group">
                            <label for="buffer_time">Buffer Time</label>
                            <div class="select-wrapper">
                                <i class='bx bx-timer'></i>
                                <select name="buffer_time" id="buffer_time" class="form-control">
                                    <option value="0">No buffer</option>
                                    <option value="15" selected>15 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="60">1 hour</option>
                                </select>
                            </div>
                            <small class="form-hint">Time between appointments for travel/preparation</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="scheduling_mode">Scheduling Mode</label>
                        <div class="select-wrapper">
                            <i class='bx bx-calendar'></i>
                            <select name="scheduling_mode" id="scheduling_mode" class="form-control">
                                <option value="hourly">Hourly (1-3 hour jobs)</option>
                                <option value="half_day">Half Day (4 hour block)</option>
                                <option value="full_day">Full Day (8 hour commitment)</option>
                            </select>
                        </div>
                        <small class="form-hint">How should customers book your service?</small>
                    </div>

                    <div class="form-group">
                        <label for="location_restriction">Service Area</label>
                        <div class="select-wrapper">
                            <i class='bx bx-map'></i>
                            <select name="location_restriction" id="location_restriction" class="form-control">
                                <option value="minglanilla_only">Only available in Minglanilla</option>
                                <option value="open">Available outside Minglanilla</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modern-modal-footer">
            <button type="button" class="btn-secondary close-modal">
                <i class='bx bx-x'></i>
                Cancel
            </button>
            <button type="submit" form="create-post-form" class="btn-primary">
                <span class="btn-text">
                    <i class='bx bx-check'></i>
                    Create Service
                </span>
                <span class="btn-spinner" style="display:none;">
                    <i class='bx bx-loader-alt bx-spin'></i>
                </span>
            </button>
        </div>
    </div>
</div>

   
  
  

    </section>

    <!-- ----------------------------------------------edit post ------------------------------->
    <section>
        <!-- EDIT POST MODAL -->
    <div id="editPostModal" class="con-modal">
    <div class="modal-backdrop"></div>
    <div class="modern-modal-content">
        <!-- Modal Header -->
        <div class="modern-modal-header">
            <div class="header-icon">
                <i class='bx bx-edit'></i>
            </div>
            <div class="header-text">
                <h3>Edit Service</h3>
                <p>Update your service details and portfolio</p>
            </div>
            <button class="modern-close-btn close-modal">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="modern-modal-body">
            <form id="edit-post-form">
                <input type="hidden" name="post_id" id="editPostId">

                <!-- Service Details -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-info-circle'></i>
                        <span>Service Details</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-description">Service Description</label>
                        <div class="textarea-wrapper">
                            <textarea id="edit-description" name="description" class="form-control modern-textarea" 
                                      placeholder="Describe your service in detail..."></textarea>
                            <div class="char-counter">
                                <span id="editDescCharCount">0</span>/500
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit-sub-services">Sub-services</label>
                        <div id="edit-sub-services-container">
                            <!-- Dynamic content will be populated here -->
                        </div>
                        <button type="button" id="addEditSubserviceBtn" class="add-more-btn">
                            <i class='bx bx-plus'></i>
                            Add Another Sub-service
                        </button>
                    </div>
                </div>

                <!-- Current Images -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-images'></i>
                        <span>Current Images</span>
                    </div>
                    
                    <div class="form-group">
                        <label>Current Portfolio Images</label>
                        <div id="currentImageWrapper" class="current-images-grid"></div>
                        <input type="hidden" name="delete_images" id="delete_images" value="[]">
                    </div>
                </div>

                <!-- New Images -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-cloud-upload'></i>
                        <span>Add New Images</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-post-picture">Upload New Images</label>
                        <div class="file-upload-area">
                            <input type="file" id="edit-post-picture" name="post_picture[]" 
                                   class="file-input" accept="image/*" multiple>
                            <div class="upload-content">
                                <i class='bx bx-cloud-upload'></i>
                                <h4>Drag & drop new images here</h4>
                                <p>or click to browse</p>
                                <span class="file-info">PNG, JPG up to 10MB each</span>
                            </div>
                        </div>
                        <div id="newImagePreviewContainer" class="image-preview-grid"></div>
                    </div>
                </div>

                <!-- Pricing & Schedule -->
                <div class="modern-form-section">
                    <div class="section-header">
                        <i class='bx bx-money'></i>
                        <span>Pricing & Schedule</span>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-rate">Service Rate</label>
                            <div class="input-wrapper">
                                <i class='bx bx-peso'></i>
                                <input type="number" name="rate" id="edit-rate" class="form-control" 
                                       min="0" step="0.01" placeholder="500">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit-rate-type">Rate Type</label>
                            <div class="select-wrapper">
                                <i class='bx bx-time'></i>
                                <select name="rate_type" id="edit-rate-type" class="form-control">
                                    <option value="hourly">Hourly</option>
                                    <option value="daily">Daily</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-service-duration">Service Duration</label>
                            <div class="select-wrapper">
                                <i class='bx bx-stopwatch'></i>
                                <select name="service_duration" id="edit-service-duration" class="form-control">
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60">1 hour</option>
                                    <option value="90">1.5 hours</option>
                                    <option value="120">2 hours</option>
                                    <option value="180">3 hours</option>
                                    <option value="240">4 hours</option>
                                    <option value="360">6 hours</option>
                                    <option value="480">8 hours (Full day)</option>
                                </select>
                            </div>
                            <small class="form-hint">How long does your service typically take?</small>
                        </div>
                        <div class="form-group">
                            <label for="edit-buffer-time">Buffer Time</label>
                            <div class="select-wrapper">
                                <i class='bx bx-timer'></i>
                                <select name="buffer_time" id="edit-buffer-time" class="form-control">
                                    <option value="0">No buffer</option>
                                    <option value="15">15 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="60">1 hour</option>
                                </select>
                            </div>
                            <small class="form-hint">Time between appointments</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit-scheduling-mode">Scheduling Mode</label>
                        <div class="select-wrapper">
                            <i class='bx bx-calendar'></i>
                            <select name="scheduling_mode" id="edit-scheduling-mode" class="form-control">
                                <option value="hourly">Hourly (1-3 hour jobs)</option>
                                <option value="half_day">Half Day (4 hour block)</option>
                                <option value="full_day">Full Day (8 hour commitment)</option>
                            </select>
                        </div>
                        <small class="form-hint">How should customers book your service?</small>
                    </div>

                    <div class="form-group">
                        <label for="edit-location-restriction">Service Area</label>
                        <div class="select-wrapper">
                            <i class='bx bx-map'></i>
                            <select name="location_restriction" id="edit-location-restriction" class="form-control">
                                <option value="minglanilla_only">Only available in Minglanilla</option>
                                <option value="open">Available outside Minglanilla</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="modern-modal-footer">
            <button type="button" class="btn-secondary" id="cancelEditBtn">
                <i class='bx bx-x'></i>
                Cancel
            </button>
            <button type="submit" form="edit-post-form" class="btn-primary">
                <span class="btn-text">
                    <i class='bx bx-check'></i>
                    Save Changes
                </span>
                <span class="btn-spinner" style="display:none;">
                    <i class='bx bx-loader-alt bx-spin'></i>
                </span>
            </button>
        </div>
    </div>
</div>


    </section>
  </main>

  <!-- JavaScript -->
  <script>
document.addEventListener('DOMContentLoaded', () => {
    // Global variable to store current post data for view modal
    let currentViewPostId = null;

    // Function to toggle modals
    function toggleModal(modalId, show = true) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = show ? 'flex' : 'none';
        } else {
            console.error(`Modal with ID "${modalId}" not found.`);
        }
    }

    // ===============================================
    // VIEW POST FUNCTIONALITY
    // ===============================================
    
    // View post function
    window.viewPost = function(postId) {
        console.log('Viewing post:', postId);
        
        // Show loading state
        const modal = document.getElementById('viewPostModal');
        modal.style.display = 'flex';
        document.querySelector('.modal-body .view-content').innerHTML = '<div class="loading-spinner"><i class="bx bx-loader-alt bx-spin"></i> Loading...</div>';
        
        // Fetch post details
        fetch(`/posts/${postId}/view`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                currentViewPostId = postId;
                populateViewModal(data);
            })
            .catch(error => {
                console.error('Error fetching post details:', error);
                document.querySelector('.modal-body .view-content').innerHTML = 
                    '<div class="error-message"><i class="bx bx-error"></i> Error loading post details. Please try again.</div>';
            });
    };

    // Populate view modal with data
    function populateViewModal(data) {
        const post = data.post;
        
        // Restore the modal HTML structure
        document.querySelector('.modal-body .view-content').innerHTML = `
            <!-- Service Images -->
            <div class="service-gallery" id="serviceGallery">
                <!-- Images will be populated here -->
            </div>
            
            <!-- Service Information -->
            <div class="service-details-view">
                <div class="detail-group">
                    <label>Service Title:</label>
                    <span id="viewTitle"></span>
                </div>
                
                <div class="detail-group">
                    <label>Category:</label>
                    <span id="viewCategory"></span>
                </div>
                
                <div class="detail-group">
                    <label>Description:</label>
                    <p id="viewDescription"></p>
                </div>
                
                <div class="detail-group">
                    <label>Sub-services:</label>
                    <div id="viewSubservices" class="subservices-list"></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-group">
                        <label>Rate:</label>
                        <span id="viewRate"></span>
                    </div>
                    
                    <div class="detail-group">
                        <label>Duration:</label>
                        <span id="viewDuration"></span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-group">
                        <label>Buffer Time:</label>
                        <span id="viewBufferTime"></span>
                    </div>
                    
                    <div class="detail-group">
                        <label>Scheduling Mode:</label>
                        <span id="viewSchedulingMode"></span>
                    </div>
                </div>
                
                <div class="detail-group">
                    <label>Location Restriction:</label>
                    <span id="viewLocationRestriction"></span>
                </div>
                
                <div class="detail-group">
                    <label>Status:</label>
                    <span id="viewStatus" class="status-badge"></span>
                </div>
                
                <!-- Performance Section -->
                <div class="performance-section">
                    <h4>Performance</h4>
                    <div class="performance-stats">
                        <div class="stat-item">
                            <span class="stat-label">Rating:</span>
                            <div class="rating-display">
                                <div id="viewRatingStars" class="stars"></div>
                                <span id="viewRatingText"></span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Total Bookings:</span>
                            <span id="viewBookings">0</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Populate gallery
        const gallery = document.getElementById('serviceGallery');
        gallery.innerHTML = '';
        if (data.post_pictures && data.post_pictures.length > 0) {
            data.post_pictures.forEach(picture => {
                const img = document.createElement('img');
                img.src = picture.startsWith('/storage/') ? picture : `/storage/${picture}`;
                img.className = 'gallery-image';
                img.alt = 'Service image';
                img.onclick = () => openImageModal(img.src);
                gallery.appendChild(img);
            });
        } else {
            gallery.innerHTML = '<p class="text-gray-500">No images available</p>';
        }
        
        // Populate details
        document.getElementById('viewTitle').textContent = post.title || 'Service Title';
        document.getElementById('viewCategory').textContent = data.category || 'Not assigned';
        document.getElementById('viewDescription').textContent = post.description || 'No description';
        
        // Populate subservices
        const subservicesContainer = document.getElementById('viewSubservices');
        subservicesContainer.innerHTML = '';
        if (data.sub_services && data.sub_services.length > 0) {
            data.sub_services.forEach(service => {
                const span = document.createElement('span');
                span.className = 'subservice-item';
                span.textContent = service;
                subservicesContainer.appendChild(span);
            });
        } else {
            subservicesContainer.innerHTML = '<span class="text-gray-500">No sub-services</span>';
        }
        
        // Populate rate and other details
        const rate = post.rate ? `₱${parseFloat(post.rate).toFixed(2)} / ${post.rate_type || 'hour'}` : 'Not set';
        document.getElementById('viewRate').textContent = rate;
        
        const duration = post.service_duration ? `${post.service_duration} minutes` : 'Not set';
        document.getElementById('viewDuration').textContent = duration;
        
        const bufferTime = post.buffer_time ? `${post.buffer_time} minutes` : 'No buffer';
        document.getElementById('viewBufferTime').textContent = bufferTime;
        
        const schedulingMode = post.scheduling_mode ? post.scheduling_mode.replace('_', ' ').toUpperCase() : 'Not set';
        document.getElementById('viewSchedulingMode').textContent = schedulingMode;
        
        const locationRestriction = post.location_restriction === 'minglanilla_only' ? 'Minglanilla only' : 'Available outside Minglanilla';
        document.getElementById('viewLocationRestriction').textContent = locationRestriction;
        
        // Status
        const statusElement = document.getElementById('viewStatus');
        statusElement.textContent = post.status ? post.status.charAt(0).toUpperCase() + post.status.slice(1) : 'Unknown';
        statusElement.className = `status-badge status-${post.status || 'unknown'}`;
        
        // Performance data
       const averageRating = parseFloat(data.performance?.average_rating) || 0;
        const totalReviews = parseInt(data.performance?.total_reviews) || 0;
        const totalBookings = parseInt(data.performance?.total_bookings) || 0;

        // Rating stars
       const starsContainer = document.getElementById('viewRatingStars');
        starsContainer.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('i');
            if (i <= Math.floor(averageRating)) {
                star.className = 'bx bxs-star';
            } else if (i - 0.5 <= averageRating) {
                star.className = 'bx bxs-star-half';
            } else {
                star.className = 'bx bx-star';
            }
            starsContainer.appendChild(star);
        }
        
        document.getElementById('viewRatingText').textContent = `${averageRating.toFixed(1)} (${totalReviews} reviews)`;
        document.getElementById('viewBookings').textContent = totalBookings;
    }

    // Close view modal
    window.closeViewModal = function() {
        document.getElementById('viewPostModal').style.display = 'none';
        currentViewPostId = null;
    };

    

    // Image modal function
    window.openImageModal = function(src) {
        const modalHtml = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 2000; display: flex; align-items: center; justify-content: center;" onclick="this.remove()">
                <img src="${src}" style="max-width: 90%; max-height: 90%; object-fit: contain;" onclick="event.stopPropagation()">
                <button style="position: absolute; top: 20px; right: 20px; background: white; border: none; padding: 10px; border-radius: 50%; cursor: pointer;" onclick="this.parentElement.remove()">✕</button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    };

    // Close view modal when clicking outside
    document.getElementById('viewPostModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewModal();
        }
    });

    // ===============================================
    // EXISTING FUNCTIONALITY (PRESERVED)
    // ===============================================

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

    // Handle create first post button for empty state
    document.getElementById('createFirstPostBtn')?.addEventListener('click', () => {
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
         const editButton = event.target.closest('.edit-btn');
    
        if (editButton) {
            console.log('Edit button clicked!');
            const postId = editButton.getAttribute('data-post-id');
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
                    document.getElementById('edit-rate').value = data.post.rate ?? '';
                    document.getElementById('edit-rate-type').value = data.post.rate_type ?? 'hourly';
                    document.getElementById('edit-location-restriction').value = data.post.location_restriction ?? 'minglanilla_only';
                    document.getElementById('edit-service-duration').value = data.post.service_duration ?? '60';
                    document.getElementById('edit-buffer-time').value = data.post.buffer_time ?? '15';
                    document.getElementById('edit-scheduling-mode').value = data.post.scheduling_mode ?? 'hourly';
                    
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
                                e.preventDefault();
                                div.remove();
                                handleImageDeletion(image);
                            });

                            div.appendChild(img);
                            div.appendChild(deleteBtn);
                            imageWrapper.appendChild(div);
                        });
                    }

                    toggleModal('editPostModal', true);
                })
                .catch(error => console.error('❌ Error fetching post data:', error))
        }
    });

    // editpost image preview
    function handleImageDeletion(imageToDelete) {
        let deleteInput = document.querySelector('input[name="delete_images"]');
        
        if (!deleteInput) {
            deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete_images';
            document.getElementById('edit-post-form').appendChild(deleteInput);
        }

        let existingImages = deleteInput.value ? deleteInput.value.split(',') : [];
        
        if (!existingImages.includes(imageToDelete)) {
            existingImages.push(imageToDelete);
        }

        deleteInput.value = existingImages.join(',');
        console.log("🛠️ Updated delete_images:", deleteInput.value);    
    }

    // Fix Image Preview Deletion on Edit Post Modal
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('delete-image')) {
            const imageToDelete = event.target.getAttribute('data-image');
            
            event.target.closest('.image-preview').remove();
            
            handleImageDeletion(imageToDelete);
        }
    });

    // Close modals when clicking the close button
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.closest('.con-modal').id;

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
                if (modal.id === 'createPostModal') {
                    resetCreatePostModal();
                }
                toggleModal(modal.id, false);
            }
        });
    });

    // addsubservices button for edit modal
    document.getElementById('addEditSubserviceBtn')?.addEventListener('click', function () {
        const subservicesContainer = document.getElementById('edit-sub-services-container');
        
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'sub_services[]';
        newInput.classList.add('form-control');
        newInput.placeholder = 'Enter subservice';

        subservicesContainer.appendChild(newInput);
    });

    // Handle Edit Post Form Submission
    document.getElementById('edit-post-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validatePostForm(this)) return;
        
        const submitBtn = this.querySelector('button[type="submit"]');
        showSpinnerOnButton(submitBtn);
        
        const formData = new FormData(this);
        formData.append('_method', 'PUT'); 

        const postId = document.getElementById('editPostId').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const deleteImagesInput = document.querySelector('input[name="delete_images"]');
        if (deleteImagesInput && deleteImagesInput.value) {
            formData.append('delete_images', deleteImagesInput.value);
        }
        
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
            restoreButton(this, 'Save Changes');
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

    // Handle Create Post Form Submission
    document.getElementById('create-post-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validatePostForm(this)) return;
        
        const submitBtn = this.querySelector('button[type="submit"]');
        showSpinnerOnButton(submitBtn);
        
        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/posts', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            restoreButton(this, 'Create Post');
            if (data.success) {
                alert('Post created successfully!');
                location.reload();
            } else {
                showValidationErrors(data.errors, 'createPostErrors');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // addsubservices for create modal
    document.getElementById('addSubserviceBtn')?.addEventListener('click', function () {
        const subservicesContainer = document.getElementById('subservices-container');
        
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'sub_services[]';
        newInput.classList.add('form-control');
        newInput.placeholder = 'Enter subservice';

        subservicesContainer.appendChild(newInput);
    });

    // image preview
    document.getElementById('recentWorks')?.addEventListener('change', function(event) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';

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
   document.addEventListener('click', function(event) {
    const deleteButton = event.target.closest('.delete-btn');
    
    if (deleteButton) {
        const postId = deleteButton.getAttribute('data-post-id');
        if (!postId) {
            console.error('No post ID found on delete button');
            return;
        }

        if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
            showSpinnerOnButton(deleteButton);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/posts/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    restoreButton(button, 'Delete Post');
                    if (!response.ok) {
                        throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const postRow = button.closest('.post-row');
                        if (postRow) {
                            postRow.remove();
                            alert(data.message || 'Post deleted successfully!');
                        } else {
                            console.warn('Post row parent element not found, refreshing page');
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
        }
    });
    
    // Character counter for description fields
document.addEventListener('DOMContentLoaded', function() {
    // Create post description counter
    const createDesc = document.getElementById('description');
    const createCounter = document.getElementById('descCharCount');
    
    if (createDesc && createCounter) {
        createDesc.addEventListener('input', function() {
            const count = this.value.length;
            createCounter.textContent = count;
            createCounter.style.color = count > 450 ? '#ef4444' : count > 350 ? '#f59e0b' : '#9ca3af';
        });
    }
    
    // Edit post description counter
    const editDesc = document.getElementById('edit-description');
    const editCounter = document.getElementById('editDescCharCount');
    
    if (editDesc && editCounter) {
        editDesc.addEventListener('input', function() {
            const count = this.value.length;
            editCounter.textContent = count;
            editCounter.style.color = count > 450 ? '#ef4444' : count > 350 ? '#f59e0b' : '#9ca3af';
        });
    }
    
    // File upload drag and drop enhancement
    const fileUploadAreas = document.querySelectorAll('.file-upload-area');
    
    fileUploadAreas.forEach(area => {
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            // Handle file drop if needed
        });
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

    // Helper functions (you'll need to add these if they don't exist)
    function showSpinnerOnButton(button) {
        const btnText = button.querySelector('.btn-text');
        const btnSpinner = button.querySelector('.btn-spinner');
        if (btnText && btnSpinner) {
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';
        }
        button.disabled = true;
    }

    function restoreButton(form, originalText) {
        const button = form.querySelector('button[type="submit"]');
        const btnText = button.querySelector('.btn-text');
        const btnSpinner = button.querySelector('.btn-spinner');
        if (btnText && btnSpinner) {
            btnText.style.display = 'inline-block';
            btnSpinner.style.display = 'none';
            btnText.textContent = originalText;
        }
        button.disabled = false;
    }
});

// Helper functions outside DOMContentLoaded
function resetCreatePostModal() {
    const createPostForm = document.getElementById('create-post-form');
    const subservicesContainer = document.getElementById('subservices-container');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    createPostForm.reset();

    subservicesContainer.innerHTML = `
        <input type="text" name="sub_services[]" class="form-control" placeholder="Enter subservice">
    `;

    imagePreviewContainer.innerHTML = '';
}

function validatePostForm(form) {
    let valid = true;
    let errors = [];

    // Subservices
    const subservices = form.querySelectorAll('input[name="sub_services[]"]');
    if (subservices.length === 0 || Array.from(subservices).every(input => !input.value.trim())) {
        valid = false;
        errors.push('At least one subservice is required.');
    }

    // Description
    const description = form.querySelector('textarea[name="description"]');
    if (!description.value.trim()) {
        valid = false;
        errors.push('Description is required.');
    }

    // Salary Rate
    const rate = form.querySelector('input[name="rate"]');
    if (!rate.value.trim() || isNaN(rate.value) || Number(rate.value) <= 0) {
        valid = false;
        errors.push('Salary rate must be a positive number.');
    }

    // Rate Type
    const rateType = form.querySelector('select[name="rate_type"]');
    if (!rateType.value) {
        valid = false;
        errors.push('Rate type is required.');
    }

    // Show errors
    let errorContainer = form.querySelector('.modal-errors');
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.className = 'modal-errors text-red-500 mb-2';
        form.prepend(errorContainer);
    }
    errorContainer.innerHTML = '';
    errors.forEach(msg => {
        const p = document.createElement('p');
        p.textContent = msg;
        errorContainer.appendChild(p);
    });

    return valid;
}
</script>