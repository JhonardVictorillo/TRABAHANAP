
   <main class="max-w-7xl mx-auto px-4 sm:px-8 py-6 sm:py-8">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl font-semibold font-poppins mb-2 sm:mb-0">
      Freelancers Offered Services
    </h1>
    @if(isset($searchTerm))
      <p class="text-sm">Search Results for: <strong>{{ $searchTerm }}</strong></p>
    @endif
  </div>
    
<div class="flex flex-wrap items-center gap-2 sm:gap-4 mb-6">
    <!-- Filter Button -->
    <div class="relative inline-block">
        <button id="filterButton" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 shadow-sm transition-all">
            <i class="ri-filter-line"></i>
            <span>Filters</span>
            <i class="ri-arrow-down-s-line"></i>
        </button>
        
        <!-- Filter Dropdown (New) -->
        <div id="filterDropdown" class="hidden absolute left-0 mt-2 w-72 rounded-xl overflow-hidden shadow-lg bg-white z-20 border border-gray-100">
            <div class="p-4 space-y-5">
                <!-- Category Filter -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Category</h4>
                    <select name="category" id="categorySelect" class="w-full py-2 px-3 text-sm border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Price Range Filter -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Price Range</h4>
                    <div class="flex items-center justify-between gap-2">
                        <div class="relative w-1/2">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">₱</span>
                            </div>
                            <input type="number" id="minPriceInput" min="0" placeholder="Min" 
                                value="{{ request('min_price', '') }}" 
                                class="pl-7 w-full py-2 text-sm border border-gray-300 rounded-lg">
                        </div>
                        <div class="relative w-1/2">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500">₱</span>
                            </div>
                            <input type="number" id="maxPriceInput" min="0" placeholder="Max" 
                                value="{{ request('max_price', '') }}" 
                                class="pl-7 w-full py-2 text-sm border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>
                
                <!-- Rating Filter -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Rating</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="ratingFilter" value="" 
                                {{ !request('rating') ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Any Rating</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="ratingFilter" value="4" 
                                {{ request('rating') == '4' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 flex items-center">
                                <span class="flex text-yellow-400 mr-1">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-line"></i>
                                </span>
                                & up
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="ratingFilter" value="3" 
                                {{ request('rating') == '3' ? 'checked' : '' }} class="h-4 w-4 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 flex items-center">
                                <span class="flex text-yellow-400 mr-1">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-line"></i>
                                    <i class="ri-star-line"></i>
                                </span>
                                & up
                            </span>
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-2">
                    <button type="button" id="clearFilters" class="text-sm text-gray-600 hover:text-gray-800">
                        Clear filters
                    </button>
                    <button type="button" id="applyFilters" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sort Button -->
    <div class="relative inline-block">
        <button id="sortButton" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 shadow-sm transition-all">
            <i class="ri-sort-desc"></i>
            <span>{{ request('sort') ? ucfirst(str_replace('_', ' ', request('sort'))) : 'Relevance' }}</span>
            <i class="ri-arrow-down-s-line"></i>
        </button>
        
        <div id="sortDropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl overflow-hidden shadow-lg bg-white z-20 border border-gray-100">
            <div class="py-1">
                <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'relevance'])) }}" 
                  class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 {{ !request('sort') || request('sort') == 'relevance' ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                    <i class="ri-star-line mr-2"></i> Relevance
                </a>
                <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'rating'])) }}" 
                  class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 {{ request('sort') == 'rating' ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                    <i class="ri-star-fill mr-2"></i> Top Rated
                </a>
                <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}" 
                  class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 {{ request('sort') == 'price_low' ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                    <i class="ri-arrow-up-line mr-2"></i> Price: Low to High
                </a>
                <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}" 
                  class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 {{ request('sort') == 'price_high' ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                    <i class="ri-arrow-down-line mr-2"></i> Price: High to Low
                </a>
                <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" 
                  class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 {{ request('sort') == 'newest' ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700' }}">
                    <i class="ri-time-line mr-2"></i> Newest First
                </a>
            </div>
        </div>
    </div>
    
    <!-- Active Filters Display -->
    @if(request()->hasAny(['category', 'price_range', 'rating', 'min_price', 'max_price']))
        <div class="ml-auto flex flex-wrap gap-2 items-center">
            @if(request('category'))
                @php $categoryName = $categories->where('id', request('category'))->first()->name ?? 'Category'; @endphp
                <div class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-xs font-medium flex items-center">
                    {{ $categoryName }}
                    <a href="{{ route('customer.dashboard', array_merge(request()->except('category'), [])) }}" class="ml-2 text-blue-500 hover:text-blue-700">
                        <i class="ri-close-line"></i>
                    </a>
                </div>
            @endif
            
            @if(request('rating'))
                <div class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-xs font-medium flex items-center">
                    {{ request('rating') }}+ Stars
                    <a href="{{ route('customer.dashboard', array_merge(request()->except('rating'), [])) }}" class="ml-2 text-blue-500 hover:text-blue-700">
                        <i class="ri-close-line"></i>
                    </a>
                </div>
            @endif
            
            @if(request('min_price') || request('max_price'))
                <div class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full text-xs font-medium flex items-center">
                    ₱{{ request('min_price', 0) }} - ₱{{ request('max_price', '10000+') }}
                    <a href="{{ route('customer.dashboard', array_merge(request()->except(['min_price', 'max_price']), [])) }}" class="ml-2 text-blue-500 hover:text-blue-700">
                        <i class="ri-close-line"></i>
                    </a>
                </div>
            @endif
            
            <a href="{{ route('customer.dashboard') }}" class="text-xs text-red-500 hover:text-red-600 font-medium ml-1">
                Clear all filters
            </a>
        </div>
    @endif
</div>

       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach ($posts as $post)
          <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-3 sm:gap-4">
                <!-- Profile Picture -->
                <img
                    src="{{ $post->freelancer && $post->freelancer->profile_picture ? asset('storage/' . $post->freelancer->profile_picture) : asset('images/defaultprofile.png') }}"
                    alt="{{ $post->freelancer->firstname ?? 'Freelancer' }}"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover"
                  />
                 <div class="flex-1 min-w-0">
                    <!-- Freelancer Name -->
                    <h3 class="text-lg font-semibold font-poppins capitalize">
                        {{ $post->freelancer->firstname ?? 'N/A' }} {{ $post->freelancer->lastname ?? '' }}
                    </h3>

                    <!-- Categories -->
                    @if($post->freelancer->categories->isNotEmpty())
                        <p class="text-sm text-gray-600 mb-2">
                            {{ $post->freelancer->categories->pluck('name')->join(', ') }}
                        </p>
                    @else
                        <p class="text-sm text-gray-600 mb-2">Category Not Assigned</p>
                    @endif

                    <!-- Average Rating -->
                    <div class="flex items-center gap-1 text-sm">
                        <div class="flex items-center text-yellow-400">
                            <i class="ri-star-fill"></i>
                        </div>
                        <span class="font-medium">
                        @if ($post->average_rating)
                          {{ number_format($post->average_rating, 1) }} ({{ $post->review_count }})
                          @else
                              No ratings yet
                          @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sub-Services -->
            <div class="flex flex-wrap gap-2 my-4">
                @if($post->subServices->isNotEmpty())
                    @foreach ($post->subServices as $subService)
                        <span class="px-2 py-1 text-xs font-medium bg-blue-50 text-[#2563eb] rounded-full">
                            {{ $subService->sub_service }}
                        </span>
                    @endforeach
                @else
                    <span class="px-2 py-1 text-xs font-medium bg-blue-50 text-primary rounded-full">
                        No sub-services available
                    </span>
                @endif
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-600 mb-4">
                {{ $post->description ?? 'No description available' }}
            </p>

            <!-- Recent Work Images -->
            <div class="flex gap-2 mb-4">
                @php
                    $postPictures = $post->pictures->take(3);
                @endphp
                @if ($postPictures->isNotEmpty())
                    @foreach ($postPictures as $picture)
                        <img
                            src="{{ asset('storage/' . $picture->image_path) }}"
                            alt="Recent work"
                            class="w-20 h-20 rounded object-cover"
                        />
                    @endforeach
                @else
                    <p class="text-sm text-gray-400">No recent work available</p>
                @endif
            </div>

            <!-- Rate and Rate Type Display -->
            <div class="mb-4">
                @if($post->rate && $post->rate_type)
                    <span class="text-blue-600 font-semibold text-base">
                        ₱{{ number_format($post->rate, 2) }} / {{ ucfirst($post->rate_type) }}
                    </span>
                @else
                    <span class="text-gray-400 text-sm">No rate specified</span>
                @endif
            </div>

            <!-- See Profile Button -->
            <a href="{{ route('freelancer.profile', ['id' => $post->freelancer_id, 'postId' => $post->id]) }}" class="block">
               <button class="w-full py-2 text-sm font-medium text-white bg-[#2563eb] hover:bg-[#1d4ed8] rounded-lg">
                    See Profile
                </button>
            </a>
        </div>
    @endforeach
</div>

<!-- Pagination Links -->
<!-- Simplified Pagination Links for Post Services -->
@if($posts->total() > $posts->perPage())
    <div class="mt-8 flex flex-col items-center justify-center gap-2">
        <div class="flex w-full justify-between items-center">
            <div>
            @if (!$posts->onFirstPage())
                <a href="{{ $posts->previousPageUrl() }}" class="px-4 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition flex items-center gap-2">
                    <i class="ri-arrow-left-s-line"></i>
                    Previous
                </a>
            @endif
        </div>
        
        <span class="text-sm text-gray-500">
            Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
        </span>
        
        <div>
            @if ($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="px-4 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition flex items-center gap-2">
                    Next
                    <i class="ri-arrow-right-s-line"></i>
                </a>
            @endif
        </div>
    </div>

    <!-- Results Summary -->
    <div class="mt-2 text-center text-sm text-gray-600">
        Showing {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} services
    </div>
@endif

   <h2 class="text-xl sm:text-2xl font-semibold font-poppins mt-10 sm:mt-12 mb-6 sm:mb-8">
  Popular Services
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
  @forelse($popularCategories as $category)
    <a href="{{ route('customer.dashboard', ['category' => $category->id]) }}" 
       class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 block group service-card">
      <div class="relative h-48">
        <img
          src="{{ isset($category->image_path) ? asset('storage/' . $category->image_path) : asset('images/category_' . $category->id . '.jpg') }}"
          alt="{{ $category->name }}"
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent group-hover:from-black/80 transition-opacity duration-300"></div>
        <div class="absolute inset-0 flex flex-col justify-end p-4">
          <span class="text-white font-medium text-lg mb-1">{{ $category->name }}</span>
          <div class="flex items-center text-white/80 text-sm group-hover:translate-x-2 transition-transform duration-300">
            <span>Browse freelancers</span>
            <i class="ri-arrow-right-line ml-1 transform group-hover:translate-x-1 transition-transform duration-300"></i>
          </div>
        </div>
      </div>
    </a>
  @empty
    <!-- Fallback content if needed -->
    <div class="col-span-full text-center py-8">
      <p class="text-gray-500">No popular services available at the moment.</p>
    </div>
  @endforelse
</div>

       
       
      </div>
    </main>


    
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Filter button functionality
    const filterButton = document.getElementById('filterButton');
    const filterDropdown = document.getElementById('filterDropdown');
    const applyFilters = document.getElementById('applyFilters');
    const clearFilters = document.getElementById('clearFilters');
    
    // Filter form fields
    const categorySelect = document.getElementById('categorySelect');
    const minPriceInput = document.getElementById('minPriceInput');
    const maxPriceInput = document.getElementById('maxPriceInput');
    
    // Sort button functionality
    const sortButton = document.getElementById('sortButton');
    const sortDropdown = document.getElementById('sortDropdown');
    
    // Toggle filter dropdown
    if (filterButton && filterDropdown) {
        filterButton.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('hidden');
            // Hide sort dropdown when filter dropdown is shown
            if (sortDropdown && !sortDropdown.classList.contains('hidden')) {
                sortDropdown.classList.add('hidden');
            }
        });
    }
    
    // Toggle sort dropdown
    if (sortButton && sortDropdown) {
        sortButton.addEventListener('click', function(e) {
            e.stopPropagation();
            sortDropdown.classList.toggle('hidden');
            // Hide filter dropdown when sort dropdown is shown
            if (filterDropdown && !filterDropdown.classList.contains('hidden')) {
                filterDropdown.classList.add('hidden');
            }
        });
    }
    
    // Close dropdowns when clicking elsewhere
    document.addEventListener('click', function(event) {
        // Close filter dropdown
        if (filterDropdown && !filterDropdown.classList.contains('hidden') && 
            !filterDropdown.contains(event.target) && 
            !filterButton.contains(event.target)) {
            filterDropdown.classList.add('hidden');
        }
        
        // Close sort dropdown
        if (sortDropdown && !sortDropdown.classList.contains('hidden') && 
            !sortDropdown.contains(event.target) && 
            !sortButton.contains(event.target)) {
            sortDropdown.classList.add('hidden');
        }
    });
    
    // Apply filters
    if (applyFilters) {
        applyFilters.addEventListener('click', function() {
            // Build query parameters
            const params = new URLSearchParams(window.location.search);
             this.innerHTML = '<i class="ri-loader-4-line animate-spin mr-1"></i> Applying...';
             this.disabled = true;
        
            // Preserve sort parameter if exists
            if (!params.has('sort') && window.location.search.includes('sort=')) {
                const sortMatch = window.location.search.match(/sort=([^&]*)/);
                if (sortMatch && sortMatch[1]) {
                    params.set('sort', sortMatch[1]);
                }
            }
            
            // Update with filter values
            if (categorySelect && categorySelect.value) {
                params.set('category', categorySelect.value);
            } else {
                params.delete('category');
            }
            
            // Price range
            if (minPriceInput && minPriceInput.value) {
                params.set('min_price', minPriceInput.value);
            } else {
                params.delete('min_price');
            }
            
            if (maxPriceInput && maxPriceInput.value) {
                params.set('max_price', maxPriceInput.value);
            } else {
                params.delete('max_price');
            }
            
            // Rating
            const ratingInputs = document.querySelectorAll('input[name="ratingFilter"]');
            let selectedRating = '';
            ratingInputs.forEach(input => {
                if (input.checked && input.value) {
                    selectedRating = input.value;
                }
            });
            
            if (selectedRating) {
                params.set('rating', selectedRating);
            } else {
                params.delete('rating');
            }
            
            // Redirect to filtered URL
          setTimeout(() => {
            window.location.href = '{{ route('customer.dashboard') }}' + 
                (params.toString() ? '?' + params.toString() : '');
        }, 300);
    });
    }
    
    // Clear filters
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            window.location.href = '{{ route('customer.dashboard') }}';
        });
    }
});
</script>