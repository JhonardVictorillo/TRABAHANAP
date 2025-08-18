
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
            <!-- Filter Dropdown -->
            <div class="relative inline-block text-left">
                <button id="filterButton" type="button" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="ri-filter-3-line"></i>
                    Filters
                </button>
                
                <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white z-10">
                    <div class="py-1">
                        <form action="{{ route('customer.dashboard') }}" method="GET">
                            <!-- Preserve sort parameter if it exists -->
                            @if(request()->has('sort'))
                                <input type="hidden" name="sort" value="{{ request()->sort }}">
                            @endif
                            
                            <!-- Category Filter -->
                            <div class="px-4 py-2">
                                <p class="text-sm font-medium text-gray-700 mb-1">Category</p>
                                <select name="category" class="w-full py-1 px-2 text-sm border rounded">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Price Range Filter -->
                            <div class="px-4 py-2">
                                <p class="text-sm font-medium text-gray-700 mb-1">Price Range</p>
                                <select name="price_range" class="w-full py-1 px-2 text-sm border rounded">
                                    <option value="">Any Price</option>
                                    <option value="low" {{ request('price_range') == 'low' ? 'selected' : '' }}>
                                        Low (Under ₱1,000)
                                    </option>
                                    <option value="medium" {{ request('price_range') == 'medium' ? 'selected' : '' }}>
                                        Medium (₱1,000 - ₱3,000)
                                    </option>
                                    <option value="high" {{ request('price_range') == 'high' ? 'selected' : '' }}>
                                        High (Over ₱3,000)
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Rating Filter -->
                            <div class="px-4 py-2">
                                <p class="text-sm font-medium text-gray-700 mb-1">Minimum Rating</p>
                                <select name="rating" class="w-full py-1 px-2 text-sm border rounded">
                                    <option value="">Any Rating</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                </select>
                            </div>
                            
                            <!-- Apply Filters Button -->
                            <div class="px-4 py-2 border-t">
                                <button type="submit" class="w-full py-1 px-2 bg-primary text-white text-sm rounded">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sort Dropdown -->
            <div class="relative inline-block text-left">
                <button id="sortButton" type="button" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg">
                    <i class="ri-sort-desc"></i>
                    Sort by
                </button>
                
                <div id="sortDropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white z-10">
                    <div class="py-1">
                        <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'rating'])) }}" 
                          class="block px-4 py-2 text-sm {{ request('sort') == 'rating' ? 'bg-gray-100 font-medium' : 'text-gray-700' }}">
                            Highest Rating
                        </a>
                        <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}" 
                          class="block px-4 py-2 text-sm {{ request('sort') == 'price_low' ? 'bg-gray-100 font-medium' : 'text-gray-700' }}">
                            Price: Low to High
                        </a>
                        <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}" 
                          class="block px-4 py-2 text-sm {{ request('sort') == 'price_high' ? 'bg-gray-100 font-medium' : 'text-gray-700' }}">
                            Price: High to Low
                        </a>
                        <a href="{{ route('customer.dashboard', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" 
                          class="block px-4 py-2 text-sm {{ request('sort') == 'newest' ? 'bg-gray-100 font-medium' : 'text-gray-700' }}">
                            Newest First
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Show active filters if any -->
            @if(request()->hasAny(['category', 'price_range', 'rating']))
                <div class="ml-auto">
                    <a href="{{ route('customer.dashboard') }}" class="text-xs text-[#ef4444] hover:text-red-600">
                        Clear filters
                    </a>
                </div>
            @endif
        </div>
        </div>
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
            <a href="{{ route('freelancer.profile', $post->freelancer->id) }}" class="block">
               <button class="w-full py-2 text-sm font-medium text-white bg-[#2563eb] hover:bg-[#1d4ed8] rounded-lg">
                    See Profile
                </button>
            </a>
        </div>
    @endforeach
</div>
    <h2 class="text-xl sm:text-2xl font-semibold font-poppins mt-10 sm:mt-12 mb-6 sm:mb-8">
  Popular Services
</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
  @forelse($popularCategories as $category)
    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow service-card">
      <div class="relative h-48">
        <img
          src="{{ isset($category->image_path) ? asset('storage/' . $category->image_path) : asset('images/category_' . $category->id . '.jpg') }}"
          alt="{{ $category->name }}"
          class="w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-4 left-4">
          <span class="text-white font-medium">{{ $category->name }}</span>
        </div>
      </div>
      <div class="p-4">
       
        <a href="{{ route('customer.dashboard', ['category' => $category->id]) }}" class="text-[#2563eb] text-sm hover:underline">
          Browse freelancers
        </a>
      </div>
    </div>
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
        // Filter dropdown toggle
        const filterButton = document.getElementById('filterButton');
        const filterDropdown = document.getElementById('filterDropdown');
        
        filterButton.addEventListener('click', function() {
            filterDropdown.classList.toggle('hidden');
            
            // Hide sort dropdown if open
            sortDropdown.classList.add('hidden');
        });
        
        // Sort dropdown toggle
        const sortButton = document.getElementById('sortButton');
        const sortDropdown = document.getElementById('sortDropdown');
        
        sortButton.addEventListener('click', function() {
            sortDropdown.classList.toggle('hidden');
            
            // Hide filter dropdown if open
            filterDropdown.classList.add('hidden');
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
                filterDropdown.classList.add('hidden');
            }
            
            if (!sortButton.contains(event.target) && !sortDropdown.contains(event.target)) {
                sortDropdown.classList.add('hidden');
            }
        });
    });
</script>