
    <main class="max-w-7xl mx-auto px-8 py-8">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-semibold font-poppins">
          Top Rated Freelancers
        </h1>
        @if(isset($searchTerm))
        <p>Search Results for: <strong>{{ $searchTerm }}</strong></p>
    @endif
        <div class="flex items-center gap-4">
          <button
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 !rounded-button"
          >
            <div class="w-4 h-4 flex items-center justify-center">
              <i class="ri-filter-3-line"></i>
            </div>
            Filters
          </button>
          <button
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 !rounded-button"
          >
            <div class="w-4 h-4 flex items-center justify-center">
              <i class="ri-sort-desc"></i>
            </div>
            Sort by
          </button>
        </div>
      </div>
      <div class="grid grid-cols-3 gap-6">
    @foreach ($posts as $post)
        <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <!-- Profile Picture -->
                <img
                    src="{{ $post->freelancer && $post->freelancer->profile_picture ? asset('storage/' . $post->freelancer->profile_picture) : asset('images/defaultprofile.png') }}"
                    alt="{{ $post->freelancer->firstname ?? 'Freelancer' }}"
                    class="w-20 h-20 rounded-full object-cover"
                />
                <div class="flex-1">
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
                        <span class="px-2 py-1 text-xs font-medium bg-blue-50 text-primary rounded-full">
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

            <!-- See Profile Button -->
            <a href="{{ route('freelancer.profile', $post->freelancer->id) }}" class="block">
                <button class="w-full py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg">
                    See Profile
                </button>
            </a>
        </div>
    @endforeach
</div>
      <h2 class="text-2xl font-semibold font-poppins mt-12 mb-8">
        Popular Services
      </h2>
      <div class="grid grid-cols-4 gap-6">
        <div
          class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow service-card"
        >
          <div class="relative h-48">
            <img
              src="https://readdy.ai/api/search-image?query=professional%20photography%20equipment%20and%20camera%20setup%20in%20studio%20with%20modern%20lighting%2C%20high-end%20photography%20gear&width=300&height=200&seq=13&orientation=landscape"
              alt="Photography"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
            ></div>
            <div class="absolute bottom-4 left-4">
              <span class="text-white font-medium">Photography</span>
            </div>
          </div>
          <div class="p-4">
            <p class="text-sm text-gray-600">Starting from</p>
            <p class="font-medium">$100 per session</p>
          </div>
        </div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow service-card"
        >
          <div class="relative h-48">
            <img
              src="https://readdy.ai/api/search-image?query=modern%20home%20interior%20cleaning%20service%20with%20professional%20equipment%20and%20supplies%2C%20clean%20organized%20space&width=300&height=200&seq=14&orientation=landscape"
              alt="House Cleaning"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
            ></div>
            <div class="absolute bottom-4 left-4">
              <span class="text-white font-medium">House Cleaning</span>
            </div>
          </div>
          <div class="p-4">
            <p class="text-sm text-gray-600">Starting from</p>
            <p class="font-medium">$50 per visit</p>
          </div>
        </div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow service-card"
        >
          <div class="relative h-48">
            <img
              src="https://readdy.ai/api/search-image?query=professional%20pet%20grooming%20service%20with%20modern%20equipment%20and%20clean%20environment%2C%20cute%20groomed%20dog&width=300&height=200&seq=15&orientation=landscape"
              alt="Pet Grooming"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
            ></div>
            <div class="absolute bottom-4 left-4">
              <span class="text-white font-medium">Pet Grooming</span>
            </div>
          </div>
          <div class="p-4">
            <p class="text-sm text-gray-600">Starting from</p>
            <p class="font-medium">$40 per session</p>
          </div>
        </div>
        <div
          class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow service-card"
        >
          <div class="relative h-48">
            <img
              src="https://readdy.ai/api/search-image?query=artisanal%20bakery%20with%20fresh%20pastries%20and%20cakes%2C%20professional%20baking%20presentation&width=300&height=200&seq=16&orientation=landscape"
              alt="Baking & Pastries"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
            ></div>
            <div class="absolute bottom-4 left-4">
              <span class="text-white font-medium">Baking & Pastries</span>
            </div>
          </div>
          <div class="p-4">
            <p class="text-sm text-gray-600">Starting from</p>
            <p class="font-medium">$30 per order</p>
          </div>
        </div>
      </div>
    </main>