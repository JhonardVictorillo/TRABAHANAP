<main  id="main-content" class="freelancers-section">
    <h2>Top Freelancers</h2>

     @if(isset($searchTerm))
        <p>Search Results for: <strong>{{ $searchTerm }}</strong></p>
    @endif
                <div class="freelancer-cards">
                  
                @foreach ($posts as $post)
              <div class="freelancer-card">
                <img src="{{ asset('storage/' . $post->freelancer->profile_picture) }}" alt="Freelancer Photo" class="freelancer-photo">
                <h3>{{ $post->freelancer->firstname }} {{ $post->freelancer->lastname }}</h3>
                <p>  @if($post->freelancer->categories->isEmpty())
                    Category Not Assigned
                @else
                    @foreach($post->freelancer->categories as $category)
                       <h4>{{ $category->name }}</h4> 
                    @endforeach
                @endif
                <div class="rating">
                  <i class="fas fa-star"></i>
                    @if ($post->average_rating)
                     {{ number_format($post->average_rating, 1) }} ({{ $post->review_count }})
                    @else
                        No ratings yet
                    @endif
                </div>
                <div class="sub-services">
                  @if($post->subServices->isNotEmpty())
                      @foreach ($post->subServices as $subService)
                          <span>{{ $subService->sub_service }}</span>
                      @endforeach
                  @else
                      <span>No sub-services available</span>
                  @endif
                </div>
                <p class="description">{{ $post->description }}</p>


                <div class="recent-work">
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
                
                <button class="see-profile">
                <a href="{{ route('freelancer.profile', $post->freelancer->id) }}">See Profile</a>
              </button>
              </div>
            @endforeach
                  
         
      
    </div>
    <section class="popular-services">
        <h2>Popular Services</h2>
        <div class="service-cards">
          <div class="service-card">
            <img src="images/housekeeping.jpg" alt="House Keeping" class="service-image">
            <h3>House Keeping</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/grooming.jpg" alt="Grooming" class="service-image">
            <h3>Grooming</h3>
            <div class="favorite-icon liked">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/art&media.jpg" alt="Art & Media" class="service-image">
            <h3>Art & Media</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/technician.jpg" alt="Art & Media" class="service-image">
            <h3>Technician</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/housekeeping.jpg" alt="House Keeping" class="service-image">
            <h3>House Keeping</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/art&media.jpg" alt="Art & Media" class="service-image">
            <h3>Art & Media</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/technician.jpg" alt="Art & Media" class="service-image">
            <h3>Technician</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
          <div class="service-card">
            <img src="images/housekeeping.jpg" alt="House Keeping" class="service-image">
            <h3>House Keeping</h3>
            <div class="favorite-icon">
              <i class="fa fa-heart"></i>
            </div>
          </div>
        </div>
        
          
        </div>
      </section>

     
  </main>
